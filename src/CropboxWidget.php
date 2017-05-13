<?php

namespace bupy7\cropbox;

use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use bupy7\cropbox\assets\WidgetAsset;

/**
 * @author Vasilij "BuPy7" Belosludcev http://mihaly4.ru
 * @since 5.0.0
 */
class CropboxWidget extends InputWidget
{
    /**
     * @var string Attribute name that content information about cropped images.
     */
    public $croppedDataAttribute;
    /**
     * @var string Input name that content information about cropped images.
     */
    public $croppedDataName;
    /**
     * @var string Input value with information about cropped images.
     */
    public $croppedDataValue;
    /**
     * @var array Options of plugin:
     * - (array) variants: Variants of cropping image. More info: https://github.com/bupy7/js-cropbox#object-variants
     * - (array) [selectors]: CSS selectors for attach events of cropbox.
     *      # (string) fileInput
     *      # (string) btnCrop
     *      # (string) btnReset
     *      # (string) btnScaleIn
     *      # (string) btnScaleOut
     *      # (string) croppedContainer
     *      # (string) croppedDataInput
     *      # (string) messageContainer
     * - (array) [messages]: Alert messages for each a variant.
     */
    public $pluginOptions = [];
    /**
     * @var string URL to image for display before upload to original URL.
     */
    public $originalImageUrl;
    /**
     * @var array URL to images for display before upload to preview URL.
     *
     * Example:
     * [
     *      '/uploads/1.png',
     *      '/uploads/2.png',
     * ];
     *
     * or simply string to image.
     */
    public $croppedImagesUrl;
    /**
     * @var string Path to view of cropbox field. Example: '@app/path/to/view'
     */
    public $pathToView = 'field';
    
    public function init()
    {
        parent::init();
        WidgetAsset::register($this->view);
        $this->registerTranslations();
        $this->configuration();
    }
    
    public function run()
    {
        $pluginOptions = Json::encode($this->pluginOptions);
        $this->view->registerJs("$('#{$this->id} .plugin').cropbox({$pluginOptions});", View::POS_READY);
        return $this->render($this->pathToView, [
            'hasModel' => $this->hasModel(),
        ]);
    }
        
    /**
     * Translates a message to the specified language.
     *
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current of application
     * language.
     * @return string
     */
    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('bupy7/cropbox', $message, $params, $language);
    }
    
    /**
     * Registration of translation class.
     */
    protected function registerTranslations()
    {
        Yii::$app->i18n->translations['bupy7/cropbox'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@bupy7/cropbox/messages',
            'fileMap' => [
                'bupy7/cropbox' => 'core.php',
            ],
        ];
    }

    /**
     * Configuration the widget.
     */
    protected function configuration()
    {
        $this->options = array_merge(['accept' => 'image/*'], $this->options);
        $croppedDataInput = $this->croppedDataAttribute;
        if ($this->hasModel()) {
            $croppedDataInput = Html::getInputName($this->model, $croppedDataInput);
        } else {
            $croppedDataInput = $this->croppedDataName;
        }
        $this->pluginOptions = array_merge([
            'selectors' => [
                'fileInput' => sprintf('#%s input[type="file"]', $this->id),
                'btnCrop' => sprintf('#%s .btn-crop', $this->id),
                'btnReset' => sprintf('#%s .btn-reset', $this->id),
                'btnScaleIn' => sprintf('#%s .btn-scale-in', $this->id),
                'btnScaleOut' => sprintf('#%s .btn-scale-out', $this->id),
                'croppedContainer' => sprintf('#%s .cropped-images-cropbox', $this->id),
                'croppedDataInput' => sprintf('#%s input[name="%s"]', $this->id, $croppedDataInput),
                'messageContainer' => sprintf('#%s .message-container-cropbox', $this->id),
            ],
        ], $this->pluginOptions);
    }
}
