<?php

namespace stwon\craftcms\extendedsearch\variables;

use stwon\craftcms\extendedsearch\ExtendedSearch;

use Craft;

class ExtendedSearchVariable
{
    // Public Methods
    // =========================================================================

	/**
	 * Whatever you want to output to a Twig template can go into a Variable method.
	 * You can have as many variable functions as you want.  From any Twig template,
	 * call it like this:
	 *
	 *     {{ craft.extendedSearch.search(query) }}
	 *
	 * @param string $term
	 * @param array $settings
	 * @return array
	 */
    public function search($term, $settings = [])
    {
        return ExtendedSearch::$plugin->extendedSearchService->search($term, $settings);
    }
}
