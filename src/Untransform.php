<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\untransform;

use Craft;
use craft\base\Plugin;
use craft\events\GetAssetUrlEvent;
use craft\services\Assets;
use putyourlightson\untransform\models\SettingsModel;
use putyourlightson\untransform\services\UrlService;
use yii\base\Event;

/**
 * @property UrlService $urlService
 * @property SettingsModel settings
 */
class Untransform extends Plugin
{
    /**
     * @var Untransform
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        self::$plugin = $this;

        $this->setComponents([
            'urlService' => UrlService::class,
        ]);

        if ($this->settings->replaceTransforms !== 0) {
            Event::on(
                Assets::class, Assets::EVENT_GET_ASSET_URL,
                function (GetAssetUrlEvent $event) {
                    $event->url = $this->urlService->getUrl($event->asset, $event->transform);
                }
            );
        }
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): SettingsModel
    {
        return new SettingsModel();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('untransform/_settings', [
            'settings' => $this->getSettings(),
            'config' => Craft::$app->getConfig()->getConfigFromFile('untransform'),
        ]);
    }
}
