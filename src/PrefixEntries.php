<?php
/**
 * Prefix Entries plugin for Craft CMS 3.x
 *
 * Prefix selected elements in the entries field with parent names
 *
 * @link      github.com/bryantwells
 * @copyright Copyright (c) 2020 Bryant Wells
 */

namespace bryantwells\prefixentries;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\elements\Entry;
use craft\helpers\Json;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

use bryantwells\prefixentries\models\Settings;
use bryantwells\prefixentries\resources\PrefixEntriesAssetBundle;

use yii\base\Event;

/**
 * Class PrefixEntries
 *
 * @author    Bryant Wells
 * @package   PrefixEntries
 * @since     1.0.0
 *
 */
class PrefixEntries extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var PrefixEntries
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public bool $hasCpSettings = false;

    /**
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // check for cp request, logged in user, and test url segments for entries page
        if (Craft::$app->getRequest()->getIsCpRequest() 
            && Craft::$app->getUser()->getIdentity()
            && sizeof(Craft::$app->getRequest()->segments) > 2) {

            // Register asset bundle
            Craft::$app->getView()->registerAssetBundle(PrefixEntriesAssetBundle::class);

            // build model of entries & parents
            $entries = array_map(function ($a) {
                return [
                    'id' => $a->id,
                    'title' => $a->title,
                    'parentId' => ($a->parent ? $a->parent->id : null), 
                ];
            }, Entry::find()->all());

            // create variables object to send to JS
            $variables = [
                'fieldTypes' => array_map(function ($a) {
                        return addslashes($a);
                    }, PrefixEntries::$plugin->getSettings()->fieldTypes),
                'entries' => $entries,
            ];

            // register javascript file
            Craft::$app->getView()->registerJs("prefixEntries(" . Json::encode($variables) . ");"); 
        }

        Craft::info(
            Craft::t(
                'prefix-entries',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }
}
