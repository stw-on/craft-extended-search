<?php


namespace stwon\craftcms\extendedsearch;

use stwon\craftcms\extendedsearch\variables\ExtendedSearchVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;


class ExtendedSearch extends Plugin
{
	// Static Properties
	// =========================================================================

	/**
	 * Static property that is an instance of this plugin class so that it can be accessed via
	 * ExpandedSearch::$plugin
	 *
	 * @var ExtendedSearch
	 */
	public static $plugin;

	// Public Properties
	// =========================================================================

	/**
	 * To execute your plugin’s migrations, you’ll need to increase its schema version.
	 *
	 * @var string
	 */
	public string $schemaVersion = '0.0.2';

	// Public Methods
	// =========================================================================

	/**
	 * Set our $plugin static property to this class so that it can be accessed via
	 * ExpandedSearch::$plugin
	 *
	 * Called after the plugin class is instantiated; do any one-time initialization
	 * here such as hooks and events.
	 *
	 * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
	 * you do not need to load it in your init() method.
	 *
	 */
	public function init()
	{
		parent::init();
		self::$plugin = $this;

		// Register our variables
		Event::on(
			CraftVariable::class,
			CraftVariable::EVENT_INIT,
			function (Event $event) {
				/** @var CraftVariable $variable */
				$variable = $event->sender;
				$variable->set('extendedSearch', ExtendedSearchVariable::class);
			}
		);

		// Do something after we're installed
		Event::on(
			Plugins::class,
			Plugins::EVENT_AFTER_INSTALL_PLUGIN,
			function (PluginEvent $event) {
				if ($event->plugin === $this) {
					// We were just installed
				}
			}
		);

		/**
		 * Logging in Craft involves using one of the following methods:
		 *
		 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
		 * Craft::info(): record a message that conveys some useful information.
		 * Craft::warning(): record a warning message that indicates something unexpected has happened.
		 * Craft::error(): record a fatal error that should be investigated as soon as possible.
		 *
		 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
		 *
		 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
		 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
		 *
		 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
		 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
		 *
		 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
		 */
		Craft::info(
			Craft::t(
				'extended-search',
				'{name} plugin loaded',
				['name' => $this->name]
			),
			__METHOD__
		);
	}

	// Protected Methods
	// =========================================================================

}
