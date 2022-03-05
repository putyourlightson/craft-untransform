<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\untransform\models;

use craft\base\Model;

class SettingsModel extends Model
{
    /**
     * @const int
     */
    public const REPLACE_TRANSFORMS_DISABLED = 0;

    /**
     * @const int
     */
    public const REPLACE_TRANSFORMS_PLACEHOLDER = 1;

    /**
     * @const int
     */
    public const REPLACE_TRANSFORMS_BASE_URL_PREFIX = 2;

    /**
     * @var int
     */
    public int $replaceTransforms = 0;

    /**
     * @var string
     */
    public string $placeholderImage = '';

    /**
     * @var string
     */
    public string $baseUrlPrefix = '';

    /**
     * @inheritdoc
     */
    public function defineRules(): array
    {
        return [
            [['replaceTransforms'], 'required'],
            [['replaceTransforms'], 'integer'],
        ];
    }
}
