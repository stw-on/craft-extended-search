<?php

namespace stwon\craftcms\extendedsearch\models;

use craft\base\Element;
use craft\base\Model;

class ExtendedSearchModel extends Model
{
	// Public Properties
	// =========================================================================

	/**
	 * The field that the search query matched against
	 *
	 * @var string|null
	 */
	public string|null $matchedField;

	/**
	 * The value that the search query matched against
	 *
	 * @var string|null
	 */
	public string|null $matchedValue;

	/**
	 * The field that the search query matched against
	 *
	 * @var array|null
	 */
	public array|null $relatedValues = [];

	/**
	 * The element type
	 *
	 * @var string
	 */
	public string $type;

	/**
	 * The matched entry
	 *
	 * @var Element
	 */
	public Element $element;

	// Public Methods
	// =========================================================================

	/**
	 * Returns the validation rules for attributes.
	 *
	 * Validation rules are used by [[validate()]] to check if attribute values are valid.
	 * Child classes may override this method to declare different validation rules.
	 *
	 * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			['matchedField', 'string'],
			['matchedValue', 'string'],
			['relatedValues', 'mixed'],
			['element', 'mixed'],
		];
	}
}
