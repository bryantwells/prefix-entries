<?php
/**
 * Prefix Entries plugin for Craft CMS 3.x
 *
 * Prefix selected elements in the entries field with parent names
 *
 * @link      github.com/bryantwells
 * @copyright Copyright (c) 2020 Bryant Wells
 */

namespace bryantwells\prefixentries\resources;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class PrefixEntriesAssetBundle extends AssetBundle
{
    public function init()
    {
        // path to publishable resources
        $this->sourcePath = '@bryantwells/prefixentries/resources/dist';

        // dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // relative path to CSS/JS files
        $this->js = [
            'app.js',
        ];

        parent::init();
    }
}