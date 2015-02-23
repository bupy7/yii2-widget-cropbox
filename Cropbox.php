<?php
namespace bupy7\cropbox;

use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\InvalidConfigException;

/**
 * Class file CropboxWidget.
 * Crop image via jQuery before upload image.
 *
 * GitHub repository JS library: https://github.com/hongkhanh/cropbox
 * GitHub repository this widget: https://github.com/bupy7/yii2-cropbox
 * 
 * @author Vasilij "BuPy7" Belosludcev http://mihaly4.ru
 * @version 3.0
 */
class Cropbox extends InputWidget
{
    
    /**
     * @var array Attribute name where will be crop information in JSON format. 
     * After cropping image all information will be added with uses key from $optionsCropbox.
     * Example: [
     *      {
     *          "x":-86,
     *          "y":-17,
     *          "dw":372,
     *          "dh":232,
     *          "ratio":0.5314410000000002
     *          "w":400,
     *          "h":300
     *      },
     *      {
     *          "x":-136,
     *          "y":-67,
     *          "dw":372,
     *          "dh":232,
     *          "ratio":0.5314410000000002,
     *          "w":400,
     *          "h":300
     *      }
     * ]
     * 
     * @property int $x Start crop by X.
     * @property int $y Start crop by Y.
     * @property int $dw Width image after resize.
     * @property int $dh Height image after resize.
     * @property float $ratio Ratio.
     * @property int $w Width of cropped image.
     * @property int $h Height of cropped image.
     */
    public $attributeCropInfo;
    
    /**
     * @var array Cropbox options.
     * 
     * @property int $boxWidth Width of box for thumb image. By default 300.
     * @property int $boxHeight Height of box for thumb image. By default 300.
     * @property array $cropSettings
     * [
     *      int $width: Width of thumbBox. By default 200.
     *      int $heiht: Height of thumbBox. By default 200.
     *      int $minHeight: Min height of thumbBox. By default not used.
     *      int $maxHeight: Max height of thumbBox. By default not used.
     *      int $minWidth: Min width of thumbBox. By default not used.
     *      int $maxWidth: Max width of thumbBox. By default not used.
     * ]
     * @property array $messages Array with messages for croppping options. 
     *
     * and etc. See cropbox.js to assets this widget.
     * 
     * Example use:
     * [   
     *      'cropSettings' => [
     *          [
     *              'width' => 350,
     *              'height' => 400,
     *          ],
     *      ],
     *      'messages' => [
     *          'Preview image of article',
     *      ]
     *  
     * ]
     * 
     * or more once option:
     * [
     *      'cropSettings' => [
     *          [
     *              'width' => 350,
     *              'height' => 400,
     *          ],
     *          [
     *              'width' => 150,
     *              'height' => 150,
     *          ],
     *          'messages' => [
     *              'Preview image of article',
     *              'Thumbnail image of article',
     *          ],
     *      ],
     * ]
     * 
     * Also to "cropSettings" you can pointer "maxHeight", "minHeight" and "maxWidth" and "maxWidth" for each 
     * cropping options if $resizeHeight or $resizeWidth is "true". Example:
     * [
     *      'cropSettings' => [
     *          [
     *              'width' => 350,
     *              'height' => 400,
     *              'minHeight' => 200,
     *              'maxHeight' => 420,
     *          ],
     *          //and etc.
     *      ],
     * ]
     * If you want resizing cropping area then you need uses both property for height or width.
     */
    public $optionsCropbox = [];
    
    /**
     * @string Link to image for display before upload to original URL.
     */
    public $originalUrl;
    
    /**
     * @var mixed Link to images for display before upload to preview URL.
     * Example:
     * [
     *      '/uploads/1.png',
     *      '/uploads/2.png',
     * ];
     * 
     * or simply string to image.
     */
    public $previewUrl;
    
    /**
     * @var string Path to view of cropbox field.
     * Example: '@app/path/to/view'
     */
    public $pathToView = 'field';
    
    public function init()
    {
        parent::init();
        
        foreach ($this->optionsCropbox['cropSettings'] as $option) {
            if (isset($option['minHeight']) || isset($option['maxHeight'])) {
                if (!(isset($option['minHeight']) && isset($option['maxHeight']))) {
                    throw new InvalidConfigException('The property "minHeight" and "maxHeight" must be setting both for resizing cropping area.');
                }
            }
            if (isset($option['minWidth']) || isset($option['maxWidth'])) {
                if (!(isset($option['minWidth']) && isset($option['maxWidth']))) {
                     throw new InvalidConfigException('The property "minWidth" and "maxWidth" must be setting both for resizing cropping area.');
                }
            }
            if (empty($option['height']) || empty($option['width'])) {
                throw new InvalidConfigException('The property "width" and "height" is require.');
            }
        }
        
        CropboxAsset::register($this->view);
        $this->registerTranslations();
        
        $this->optionsCropbox = array_merge([
            'boxWidth' => 300,
            'boxHeight' => 300,
        ], $this->optionsCropbox);
        if (!isset($this->optionsCropbox['cropSettings']) || empty($this->optionsCropbox['cropSettings'])) {
            $this->optionsCropbox['cropSettings'][] = [
                'width' => 200,
                'height' => 200,
            ];
        }
        $this->optionsCropbox['idCropInfo'] = Html::getInputId($this->model, $this->attributeCropInfo);
        $this->options = array_merge([
            'class' => 'file',
        ], $this->options);
        
        $optionsCropbox = Json::encode($this->optionsCropbox);
        
        $js = "$('#{$this->id}').cropbox({$optionsCropbox});";
        $this->view->registerJs($js, \yii\web\View::POS_READY);
    }
    
    public function run()
    {
        return $this->render($this->pathToView, [
            'idWidget' => $this->id,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'previewUrl' => (array)$this->previewUrl,
            'originalUrl' => $this->originalUrl,
            'options' => $this->options,
            'attributeCropInfo' => $this->attributeCropInfo,
        ]);
    }
    
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['bupy7/cropbox/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@bupy7/cropbox/messages',
            'fileMap' => [
                'bupy7/cropbox/core' => 'core.php',
            ],
        ];
    }

    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('bupy7/cropbox/core', $message, $params, $language);
    }

}
