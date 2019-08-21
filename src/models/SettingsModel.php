<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\untransform\models;

use Craft;
use craft\base\Model;

class SettingsModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var bool
     */
    public $enabled = true;

    /**
     * @var string|null
     */
    public $baseUrlPrefix = 'https://lynn.edu';

    /**
     * @var string|null
     */
    public $placeholderImage;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['enabled'], 'boolean'],
        ];
    }
}
