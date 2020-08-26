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
	 * @var string
	 */
	public $matchedField;

	/**
	 * The value that the search query matched against
	 *
	 * @var string
	 */
	public $matchedValue;

	/**
	 * The field that the search query matched against
	 *
	 * @var array
	 */
	public $relatedValues = [];

	/**
	 * The element type
	 *
	 * @var string
	 */
	public $type;

	/**
	 * The matched entry
	 *
	 * @var Element
	 */
	public $element;

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
	public function rules()
	{
		return [
			['matchedField', 'string'],
			['matchedValue', 'string'],
			['relatedValues', 'mixed'],
			['element', 'mixed'],
		];
	}
}
