<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\untransform\models;

use Craft;
use craft\base\Model;

class SettingsModel extends Model
{
    // Constants
    // =========================================================================

    /**
     * @const int
     */
    const REPLACE_TRANSFORMS_DISABLED = 0;

    /**
     * @const int
     */
    const REPLACE_TRANSFORMS_PLACEHOLDER = 1;

    /**
     * @const int
     */
    const REPLACE_TRANSFORMS_BASE_URL_PREFIX = 2;

    // Public Properties
    // =========================================================================

    /**
     * @var int
     */
    public $replaceTransforms = 0;

    /**
     * @var string|null
     */
    public $placeholderImage;

    /**
     * @var string|null
     */
    public $baseUrlPrefix;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['replaceTransforms'], 'required'],
            [['replaceTransforms'], 'integer'],
        ];
    }
}
