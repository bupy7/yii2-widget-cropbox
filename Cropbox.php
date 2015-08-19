<?php

namespace bupy7\cropbox;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\base\Model;
use yii\base\InvalidConfigException;

/**
 * Class file CropboxWidget.
 * Crop image via jQuery before upload image.
 *
 * GitHub repository this widget: https://github.com/bupy7/yii2-widget-cropbox
 * 
 * @author Vasilij "BuPy7" Belosludcev http://mihaly4.ru
 * @since 1.0.0
 */
class Cropbox extends Widget
{
    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;
    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;
    /**
     * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
     */
    public $name;
    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var array Attribute name that content information about crop image.
     */
    public $attributeCropInfo;
    /**
     * @var array Options of jQuery plugin.
     */
    public $pluginOptions = [];
    /**
     * @string URL to image for display before upload to original URL.
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
    public $previewImagesUrl;
    /**
     * @var string Path to view of cropbox field.
     * 
     * Example: '@app/path/to/view'
     */
    public $pathToView = 'field';
    
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->name === null && !$this->hasModel()) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : false;
        }
        parent::init();
        
        WidgetAsset::register($this->view);
        $this->registerTranslations();
        
        $this->options = array_merge([
            'accept' => 'image/*',
        ], $this->options);
        $this->pluginOptions = array_merge([
            'selectors' => [
                'inputFile' => '#' . $this->id . ' input[type="file"]',
                'btnCrop' => '#' . $this->id . ' .btn-crop',
                'btnReset' => '#' . $this->id . ' .btn-reset',
                'resultContainer' => '#' . $this->id . ' .cropped',
                'messageBlock' => '#' . $this->id . ' .alert',
            ],
            'imageOptions' => [
                'class' => 'img-thumbnail',
            ],
        ], $this->pluginOptions);
        $this->pluginOptions['selectors']['inputInfo'] = '#' 
            . $this->id 
            . ' input[name="' 
            . Html::getInputName($this->model, $this->attributeCropInfo) 
            . '"]';
        $optionsCropbox = Json::encode($this->pluginOptions);
        
        $js = "$('#{$this->id}').cropbox({$optionsCropbox});";
        $this->view->registerJs($js, View::POS_READY);
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render($this->pathToView);
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
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model && $this->attribute !== null;
    }
}
