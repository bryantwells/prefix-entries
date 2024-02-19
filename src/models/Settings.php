<?php
/**
 * Prefix Entries plugin for Craft CMS 3.x
 *
 * Prefix selected elements in the entries field with parent names
 *
 * @link      https://github.com/bryantwells
 * @copyright Copyright (c) 2020 Bryant Wells
 */

namespace bryantwells\prefixentries\models;

use craft\base\Model;

/**
 * @author    Bryant Wells
 * @package   PrefixEntries
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var array
     */
    public $fieldTypes = [];

}
