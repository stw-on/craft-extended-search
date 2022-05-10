<?php


namespace stwon\craftcms\extendedsearch\services;

use craft\elements\Asset;
use stwon\craftcms\extendedsearch\models\ExtendedSearchModel;

use Craft;
use craft\base\Component;
use craft\base\Element;
use craft\elements\Entry;

class ExtendedSearchService extends Component
{
	// Public Methods
	// =========================================================================

	/**
	 * Searches entries for the given term
	 *
	 * @param string $term
	 * @return array the search results
	 */
	public function search($term, $settings): array
    {
		$default = [
			'type' => 'entry',
			'sections' => null,
			'sectionId' => null,
			'length' => 300,
			'limit' => 0,
			'offset' => 0,
			'subLeft' => true,
			'subRight' => true,
		];
		$settings = (object)array_merge($default, $settings);

		$query = $term;
		if ($settings->subLeft) {
			$query = '*' . $query;
		}
		if ($settings->subRight) {
			$query = $query . '*';
		}

		switch ($settings->type) {
			case Entry::refHandle():
				$elementType = Entry::class;
				break;
			case Asset::refHandle():
				$elementType = Asset::class;
				break;
			default:
				throw new \InvalidArgumentException(
					'Invalid type "' .
					$settings->type .
					'". Must be one of: ' .
					implode(', ', [Entry::refHandle(), Asset::refHandle()])
				);
		}

		$elementQuery = $elementType::find()
			->search('*' . $query . '*');

		if ($elementType === Entry::class) {
			$elementQuery
				->section($settings->sections)
				->sectionId($settings->sectionId);
		}

		$elementQuery->orderBy('score');

		if ($settings->offset > 0) {
			$elementQuery = $elementQuery->offset($settings->offset);
		}
		if ($settings->limit > 0) {
			$elementQuery = $elementQuery->limit($settings->limit);
		}
		$results = [];
		foreach ($elementQuery->all() as $entry) {
			$results[] = $this->expandSearchResults($entry, $term, $settings->length);
		}

		return $results;
	}

	/**
	 * Sets up a array of ExpandedSearchModels with highlights
	 *
	 * @param $entry
	 * @param string $term
	 * @param int $length
	 * @return ExtendedSearchModel
	 */
	public function expandSearchResults($entry, string $term, int $length = 300): ExtendedSearchModel
    {
		$result = new ExtendedSearchModel();
		$result->element = $entry;
		[$result->matchedField, $result->matchedValue, $result->relatedValues] = $this->findMatchesInFieldSet($entry, $term, $length);
		return $result;
	}

	/**
	 * Converts an Element into a kvp array of its fields
	 *
	 * @param Element $element
	 * @return array
	 */
	protected function getFieldSetValues(Element $element): array
    {
		$values = [];
		foreach ($element->getFieldLayout()->getCustomFields() as $fieldLayoutField) {
			$fieldHandle = Craft::$app->getFields()->getFieldById($fieldLayoutField->id)->handle;
			$fieldContents = $element->getFieldValue($fieldHandle);
			$values[$fieldHandle] = $fieldContents;
		}
		return $values;
	}

	/**
	 * Gets a normalized representation of the given value
	 *
	 * @param mixed $value
	 * @return string
	 */
	protected function getNormalizedValue($value)
	{
		if (is_object($value)) {
			return get_class($value);
		}
		return $value;
	}

	/**
	 * Cleans up the value and adds bold tag to search term
	 * Also shortens the value if needed
	 *
	 * @param string $content
	 * @param string $term
	 * @param int $length
	 * @return string
	 */
	protected function contextualizeHit($content, $term, $length)
	{
		$content = strip_tags($content);
		$pattern = '/(' . preg_quote($term, '/') . ')/im';
		$midway = round($length / 2);
		if (strlen($content) > $length) {
			// if the hit is after the middle, we need to shorten the text on both sides
			$strpos = stripos($content, $term);
			if ($strpos > $midway) {
				$content = '…' . mb_substr($content, $strpos - $midway);
			}
			if (strlen($content) > $length) {
				$content = mb_substr($content, 0, $length) . '…';
			}
		}
		return preg_replace($pattern, '<span class="search-hit">${1}</span>', $content);
	}

	/**
	 * Finds matches in an element's field values
	 *
	 * @param Element $element
	 * @param string $term the search term
	 * @return array indexed array consisting of
	 *                - The field handle
	 *                - The matched value
	 *                - Associative array of related values (handle => value)
	 */
	protected function findMatchesInFieldSet(Element $element, $term, $length)
	{
		foreach ($this->getFieldSetValues($element) as $fieldHandle => $fieldContents) {
			//dump($fieldContents);
			if (is_scalar($fieldContents)) {
				if (stripos($fieldContents, $term) !== false) {
					return [$fieldHandle, $this->contextualizeHit($fieldContents, $term, $length), []];
				}
			} elseif (is_object($fieldContents) && $fieldContents instanceof \verbb\supertable\elements\db\SuperTableBlockQuery) {
				//TODO: fix this somehow
			} elseif (is_object($fieldContents) && $fieldContents instanceof \craft\elements\db\MatrixBlockQuery) {
				$relatedValues = [];
				$matchedValue = '';
				foreach ($fieldContents->all() as $matrixBlock) {
					$matrixMatches = $this->findMatchesInFieldSet($matrixBlock, $term, $length);
					if (!is_null($matrixMatches)) {
						$matchedValue = $matrixMatches[1];
						foreach ($this->getFieldSetValues($matrixBlock) as $k => $v) {
							$relatedValues[$k] = $this->getNormalizedValue($v);
						}
					}
				}
				// TODO: Should we append the matched sub-field handle to the higher-level handle?
				return [$fieldHandle, $matchedValue, $relatedValues];
			} elseif (is_object($fieldContents) && $fieldContents instanceof \craft\redactor\FieldData) {
				//dump(stripos($fieldContents->getParsedContent(), $term));
				if (stripos($fieldContents->getParsedContent(), $term)) {
					return [$fieldHandle, $this->contextualizeHit($fieldContents->getParsedContent(), $term, $length), []];
				}
			} else {
				// TODO: handle more data types
			}
		}
		return null;
	}
}
