# Extended Search plugin for Craft CMS 3.x

This repository is a fork of https://github.com/mustasj-as/expanded-search, which is
https://github.com/composedcreative/craft-expandedsearch ported from Craft 2 to Craft 3.
It is an expansion of Crafts search, which gives you a context for search hits.

## Requirements

This plugin requires Craft CMS 3.1.0 or later.

## Installation

To install the plugin, follow these instructions.

1.  Open your terminal and go to your Craft project:

        cd /path/to/project

2.  Then tell Composer to load the plugin:

        composer require stw-on/craft-extended-search

3.  In the Control Panel, go to Settings → Plugins and click the “Install” button for Extended Search.

## Using Expanded Search

The first parameter is the search term. Which will be salted automatically: `*{term}*`
The second is settings.

| Setting   | Type         | Purpose                                             | Default             |
| --------- | ------------ | --------------------------------------------------- | ------------------- |
| length    | int          | Cuts off the search value at given length           | 300                 |
| section   | array/string | section names to search in                          | null (all sections) |
| sectionId | array/int    | id of sections to search in                         | null (all sections) |
| limit     | int          | how many results to return (pagination)             | 0 (all)             |
| offset    | int          | how many results to skip (pagination)               | 0                   |
| subLeft   | bool         | to use fuzzy search left                            | true                |
| subRight  | bool         | to use fuzzy search right                           | true                |
| type      | string       | element type to search for (e.g. `entry` or `asset` | entry               |

In your search results template

```twig
{% set results = craft.expandedSearch.search(query, { sections: ['news'], length: 150 }) %}
{% for result in results %}
    <strong data-field="{{ result.matchedField }}">{{ result.entry.title }}</strong><br>
    <p>{{result.matchedValue}}</p>
    <a href="{{ result.element.url }}">{{ result.element.url }}</a>
{% else %}
    <p>Sorry, no results for {{ query }}.</p>
{% endfor %}
```