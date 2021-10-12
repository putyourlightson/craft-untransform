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
use craft\helpers\UrlHelper;
use craft\models\AssetTransform;
use craft\volumes\Local;
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
                $volume = $asset->getVolume();

                if (!($volume instanceof Local)) {
                    return null;
                }

                $baseUrl = rtrim(Untransform::$plugin->settings->baseUrlPrefix, '/');
                $volumeUri = ltrim(str_replace(UrlHelper::baseSiteUrl(), '', $volume->getRootUrl()), '/');
                $folderPath = $asset->getFolder()->path;
                $uri = $asset->filename;
                $appendix = Assets::urlAppendix($volume, $asset);

                if ($transform !== null) {
                    // Get the transform URI
                    $assetTransforms = Craft::$app->getAssetTransforms();
                    $transformIndex = $assetTransforms->getTransformIndex($asset, $transform);
                    $uri = $assetTransforms->getTransformUri($asset, $transformIndex);
                }

                return $baseUrl . '/' . ltrim($volumeUri, '/') . $folderPath . $uri . $appendix;
        }

        return '';
    }
}
