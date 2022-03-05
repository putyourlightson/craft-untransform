<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\untransform;

use Craft;
use craft\base\Plugin;
use craft\events\DefineAssetUrlEvent;
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
    public static Untransform $plugin;

    /**
     * @inheritdoc
     */
    public bool $hasCpSettings = true;

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
            Event::on(Assets::class, Assets::EVENT_DEFINE_ASSET_URL,
                function (DefineAssetUrlEvent $event) {
                    $event->url = $this->urlService->getUrl($event->asset, $event->transform);
                }
            );
        }
    }

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
    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('untransform/_settings', [
            'settings' => $this->getSettings(),
            'config' => Craft::$app->getConfig()->getConfigFromFile('untransform'),
        ]);
    }
}
