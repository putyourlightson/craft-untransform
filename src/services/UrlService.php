<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\untransform\services;

use Craft;
use craft\base\Component;
use craft\elements\Asset;
use craft\errors\AssetTransformException;
use craft\helpers\Assets;
use craft\models\AssetTransform;
use putyourlightson\untransform\assets\UntransformAsset;
use putyourlightson\untransform\models\SettingsModel;
use putyourlightson\untransform\Untransform;

class UrlService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * @param Asset $asset
     * @param AssetTransform|string|array|null $transform
     *
     * @return string|null
     * @throws AssetTransformException
     */
    public function getUrl(Asset $asset, $transform)
    {
        switch (Untransform::$plugin->settings->replaceTransforms) {
            case SettingsModel::REPLACE_TRANSFORMS_DISABLED:
                return null;

            case SettingsModel::REPLACE_TRANSFORMS_PLACEHOLDER:
                // Return user defined placeholder image if it exists
                if (Untransform::$plugin->settings->placeholderImage) {
                    return Untransform::$plugin->settings->placeholderImage;
                }

                // Return default placeholder
                return Craft::$app->getAssetManager()->getPublishedUrl(
                    '@putyourlightson/untransform/resources/placeholder.svg',
                    true
                );

            case SettingsModel::REPLACE_TRANSFORMS_BASE_URL_PREFIX:
                // Return the transform URL with the base URL prefix prepended
                $uri = $asset->filename;

                if ($transform !== null) {
                    // Get the transform URI
                    $assetTransforms = Craft::$app->getAssetTransforms();
                    $transformIndex = $assetTransforms->getTransformIndex($asset, $transform);
                    $uri = $assetTransforms->getTransformUri($asset, $transformIndex);
                }

                $volume = $asset->getVolume();
                $baseUrl = Untransform::$plugin->settings->baseUrlPrefix . $volume->getRootUrl();
                $folderPath = $asset->getFolder()->path;
                $appendix = Assets::urlAppendix($volume, $asset);

                return $baseUrl . $folderPath . $uri . $appendix;
        }

        return '';
    }
}
