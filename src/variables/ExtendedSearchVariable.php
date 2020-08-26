<?php
/**
 * Expanded Search plugin for Craft CMS 3.x
 *
 * An expansion of Crafts search
 *
 * @link      mustasj.no
 * @copyright Copyright (c) 2019 Mustasj
 */

namespace stwon\craftcms\extendedsearch\variables;

use stwon\craftcms\extendedsearch\ExtendedSearch;

use Craft;

/**
 * Expanded Search Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.expandedSearch }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Mustasj
 * @since     0.0.1
 */
class ExtendedSearchVariable
{
    // Public Methods
    // =========================================================================

	/**
	 * Whatever you want to output to a Twig template can go into a Variable method.
	 * You can have as many variable functions as you want.  From any Twig template,
	 * call it like this:
	 *
	 *     {{ craft.expandedSearch.search(query) }}
	 *
	 * @param string $term
	 * @param array $settings
	 * @return array
	 */
    public function search($term, $settings = [])
    {
        return ExtendedSearch::$plugin->expandedSearchService->search($term, $settings);
    }
}
