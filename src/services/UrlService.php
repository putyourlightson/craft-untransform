<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\untransform\services;

use Craft;
use craft\base\Component;
use craft\elements\Asset;
use craft\fs\Local;
use craft\helpers\Assets;
use craft\helpers\UrlHelper;
use craft\models\ImageTransform;
use putyourlightson\untransform\models\SettingsModel;
use putyourlightson\untransform\Untransform;

class UrlService extends Component
{
    /**
     * Return the URL for an asset's image transform.
     */
    public function getUrl(Asset $asset, ImageTransform|string|array|null $transform): ?string
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
                $filesystem = $volume->getFs();

                if (!($filesystem instanceof Local)) {
                    return null;
                }

                $baseUrl = rtrim(Untransform::$plugin->settings->baseUrlPrefix, '/');
                $volumeUri = ltrim(str_replace(UrlHelper::baseSiteUrl(), '', $filesystem->getRootUrl()), '/');
                $folderPath = $asset->getFolder()->path;
                $uri = $asset->filename;
                $appendix = Assets::urlAppendix($volume, $asset);

                if ($transform !== null) {
                    // Get the transformed URI
                    $transformedUrl = $asset->getUrl($transform);

                    if ($transformedUrl) {
                        $uri = str_replace($folderPath, '', $transformedUrl);
                    }
                }

                return $baseUrl . '/' . ltrim($volumeUri, '/') . $folderPath . $uri . $appendix;
        }

        return '';
    }
}
