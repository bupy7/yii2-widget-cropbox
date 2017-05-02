<?php

namespace bupy7\cropbox;

use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\base\InvalidConfigException;
use bupy7\cropbox\assets\WidgetAsset;

/**
 * @author Vasilij "BuPy7" Belosludcev http://mihaly4.ru
 * @since 1.0.0
 */
class Cropbox extends InputWidget
{
    /**
     * @var array Attribute name that content information about crop image.
     */
    public $attributeCropInfo;
    /**
     * @var array Options of js-cropbox plugin.
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
    public $previewImagesUrl;
    /**
     * @var string Path to view of cropbox field. Example: '@app/path/to/view'
     */
    public $pathToView = 'field';
    
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();        
        WidgetAsset::register($this->view);
        $this->registerTranslations();      
        $this->options = array_merge(['accept' => 'image/*'], $this->options);
        $this->pluginOptions = array_merge([
            'selectors' => [
                'inputFile' => sprintf('#%s input[type="file"]', $this->id),
                'btnCrop' => sprintf('#%s .btn-crop', $this->id),
                'btnReset' => sprintf('#%s .btn-reset', $this->id),
                'resultContainer' => sprintf('#%s .cropped', $this->id),
                'messageBlock' => sprintf('#%s .alert', $this->id),
            ],
            'imageOptions' => [
                'class' => 'img-thumbnail',
            ],
        ], $this->pluginOptions);
        $inputInfoName = $this->attributeCropInfo;
        if ($this->hasModel()) {
            $inputInfoName = Html::getInputName($this->model, $inputInfoName);
        }
        $this->pluginOptions['selectors']['inputInfo'] = sprintf('#%s input[name="%s"]', $this->id, $inputInfoName);
        $optionsCropbox = Json::encode($this->pluginOptions);       
        $js = "$('#{$this->options['id']}').cropbox({$optionsCropbox});";
        $this->view->registerJs($js, View::POS_READY);
    }
    
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
}
