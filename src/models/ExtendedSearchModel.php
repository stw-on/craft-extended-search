<?php
/**
 * Expanded Search plugin for Craft CMS 3.x
 *
 * An expansion of Crafts search
 *
 * @link      mustasj.no
 * @copyright Copyright (c) 2019 Mustasj
 */

namespace stwon\craftcms\extendedsearch\models;

use craft\base\Element;
use craft\base\Model;

/**
 * ExpandedSearchModel Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Mustasj
 * @since     0.0.1
 */
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
