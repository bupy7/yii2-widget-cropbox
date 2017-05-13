yii2-widget-cropbox
===================

[![Latest Stable Version](https://poser.pugx.org/bupy7/yii2-widget-cropbox/v/stable)](https://packagist.org/packages/bupy7/yii2-widget-cropbox) 
[![Total Downloads](https://poser.pugx.org/bupy7/yii2-widget-cropbox/downloads)](https://packagist.org/packages/bupy7/yii2-widget-cropbox) 
[![License](https://poser.pugx.org/bupy7/yii2-widget-cropbox/license)](https://packagist.org/packages/bupy7/yii2-widget-cropbox)
[![Build Status](https://travis-ci.org/bupy7/yii2-widget-cropbox.svg?branch=master)](https://travis-ci.org/bupy7/yii2-widget-cropbox)
[![Coverage Status](https://coveralls.io/repos/github/bupy7/yii2-widget-cropbox/badge.svg?branch=master)](https://coveralls.io/github/bupy7/yii2-widget-cropbox?branch=master)

This is Yii2 widget wrapper for [js-cropbox](https://github.com/bupy7/js-cropbox).

Demo and documentation of plugin
--------------------------------

[js-cropbox Demo](http://bupy7.github.io/js-cropbox/)

[js-cropbox README](https://github.com/bupy7/js-cropbox/blob/master/README.md)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run
```
$ php composer.phar require --prefer-dist bupy7/yii2-widget-cropbox "*"
```

or add
```
"bupy7/yii2-widget-cropbox": "*"
```

to the **require** section of your **composer.json** file.

If you use v4.1.2 then go to [v4.1.2](https://github.com/bupy7/yii2-widget-cropbox/tree/v4.1.2).

If you use v3.0.1 then go to [v3.0.1](https://github.com/bupy7/yii2-widget-cropbox/tree/v3.0.1).

If you use v2.2 then go to [v2.2](https://github.com/bupy7/yii2-widget-cropbox/tree/v2.2).

If you use v1.0 then go to [v1.0](https://github.com/bupy7/yii2-widget-cropbox/tree/v1.0).

Options
-------

#### `$pluginOptions`

Contain configuration of js-cropbox wrapper.

##### (array) `$variants`: Variants of cropping image.
More info: https://github.com/bupy7/js-cropbox#object-variants

##### (array) `[$selectors]`: CSS selectors for attach events of cropbox.

- (string) fileInput
- (string) btnCrop
- (string) btnReset
- (string) btnScaleIn
- (string) btnScaleOut
- (string) croppedContainer
- (string) croppedDataInput
- (string) messageContainer

##### (array) `[$messages]`: Alert messages for each a variant.

Usage
-----

For example, I will use **Imagine extensions for Yii2** https://github.com/yiisoft/yii2-imagine . You can use something other.

**Add in action to your controller:**

```php
...

if ($model->load(Yii::$app->request->post()))
{   
    $model->image = \yii\web\UploadedFile::getInstance($model, 'image');
    
    if ($model->save()) 
    {
        return $this->redirect(['index']);
    }
}

...
```

**Add to your view:**

```php
use bupy7\cropbox\CropboxWidget;

$form = ActiveForm::begin([
    'options' => ['enctype'=>'multipart/form-data'],
]);

...

echo $form->field($model, 'image')->widget(CropboxWidget::className(), [
    'croppedDataAttribute' => 'crop_info',
]);

...
```

**Add to your model:**

```php
...

use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\helpers\Json;
use Imagine\Image\Box;
use Imagine\Image\Point;

...

public $image;
public $crop_info;

...

public function rules()
{
    ...
    
    [
        'image', 
        'image', 
        'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
        'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],
    ],
    ['crop_info', 'safe'],
    
    ...
}

...

public function afterSave($insert, $changedAttributes)
{
    ...

    // open image
    $image = Image::getImagine()->open($this->image->tempName);

    // rendering information about crop of ONE option 
    $cropInfo = Json::decode($this->crop_info)[0];
    $cropInfo['dWidth'] = (int)$cropInfo['dWidth']; //new width image
    $cropInfo['dHeight'] = (int)$cropInfo['dHeight']; //new height image
    $cropInfo['x'] = $cropInfo['x']; //begin position of frame crop by X
    $cropInfo['y'] = $cropInfo['y']; //begin position of frame crop by Y
    // Properties bolow we don't use in this example
    //$cropInfo['ratio'] = $cropInfo['ratio'] == 0 ? 1.0 : (float)$cropInfo['ratio']; //ratio image. 
    //$cropInfo['width'] = (int)$cropInfo['width']; //width of cropped image
    //$cropInfo['height'] = (int)$cropInfo['height']; //height of cropped image
    //$cropInfo['sWidth'] = (int)$cropInfo['sWidth']; //width of source image
    //$cropInfo['sHeight'] = (int)$cropInfo['sHeight']; //height of source image

    //delete old images
    $oldImages = FileHelper::findFiles(Yii::getAlias('@path/to/save/image'), [
        'only' => [
            $this->id . '.*',
            'thumb_' . $this->id . '.*',
        ], 
    ]);
    for ($i = 0; $i != count($oldImages); $i++) {
        @unlink($oldImages[$i]);
    }

    //saving thumbnail
    $newSizeThumb = new Box($cropInfo['dWidth'], $cropInfo['dHeight']);
    $cropSizeThumb = new Box(200, 200); //frame size of crop
    $cropPointThumb = new Point($cropInfo['x'], $cropInfo['y']);
    $pathThumbImage = Yii::getAlias('@path/to/save/image') 
        . '/thumb_' 
        . $this->id 
        . '.' 
        . $this->image->getExtension();  

    $image->resize($newSizeThumb)
        ->crop($cropPointThumb, $cropSizeThumb)
        ->save($pathThumbImage, ['quality' => 100]);

    //saving original
    $this->image->saveAs(
        Yii::getAlias('@path/to/save/image') 
        . '/' 
        . $this->id 
        . '.' 
        . $this->image->getExtension()
    );
}

...
```

Configuration
-------------

### Preview exist image of item

If you want to show uploaded and cropped image, you must add following code:

```php
echo $form->field($model, 'image')->widget(CropboxWidget::className(), [

    ...

    'croppedImagesUrl' => [
        'url/to/small/image'
    ],
    'originalImageUrl' => 'url/to/original/image',
]);
```

If you will click on preview image you see original image.

### Crop with save real size of image

Difference from previous methods in that we don't resize image before crop it.
We cropped image as we see it in editor box with saving real size.

For this we will use property `ratio` from `$cropInfo`.

```php
$cropInfo = Json::decode($this->crop_info)[0];
$cropInfo['dWidth'] = (int)$cropInfo['dWidth'];
$cropInfo['dHeight'] = (int)$cropInfo['dHeight'];
$cropInfo['x'] = abs($cropInfo['x']);
$cropInfo['y'] = abs($cropInfo['y']);
$cropInfo['ratio'] = $cropInfo['ratio'] == 0 ? 1.0 : (float)$cropInfo['ratio'];
 
$image = Image::getImagine()->open($this->image->tempName);
 
$cropSizeLarge = new Box(200 / $cropInfo['ratio'], 200 / $cropInfo['ratio']);
$cropPointLarge = new Point($cropInfo['x'] / $cropInfo['ratio'], $cropInfo['y'] / $cropInfo['ratio']);
$pathLargeImage = Yii::getAlias('path/to/save') . '/' . $this->id . '.' . $this->image->getExtension();
 
$image->crop($cropPointLarge, $cropSizeLarge)
    ->save($pathLargeImage, ['quality' => $module->qualityLarge]);
```

### Cropping more once option

If you will set few veriants crop on plugin you need make following:

**In model:**

```php
...

public function afterSave($insert, $changedAttributes)
{
    ...
    
    // open image
    $image = Image::getImagine()->open($this->image->tempName);
    
    $variants = [
        [
            'width' => 150,
            'height' => 150,
        ],
        [
            'width' => 350,
            'height' => 200,
        ],
    ];
    for($i = 0; $i != count(Json::decode($this->crop_info)); $i++) {
        $cropInfo = Json::decode($this->crop_info)[$i];
        $cropInfo['dWidth'] = (int)$cropInfo['dWidth']; //new width image
        $cropInfo['dHeight'] = (int)$cropInfo['dHeight']; //new height image
        $cropInfo['x'] = abs($cropInfo['x']); //begin position of frame crop by X
        $cropInfo['y'] = abs($cropInfo['y']); //begin position of frame crop by Y
        //$cropInfo['ratio'] = $cropInfo['ratio'] == 0 ? 1.0 : (float)$cropInfo['ratio']; //ratio image. We don't use in this example

        //delete old images
        $oldImages = FileHelper::findFiles(Yii::getAlias('@path/to/save/image'), [
            'only' => [
                $this->id . '.' . $i . '.*',
                'thumb_' . $this->id . '.' . $i . '.*',
            ], 
        ]);
        for ($j = 0; $j != count($oldImages); $j++) {
            @unlink($oldImages[$j]);
        }

        //saving thumbnail
        $newSizeThumb = new Box($cropInfo['dWidth'], $cropInfo['dHeight']);
        $cropSizeThumb = new Box($variants[$i]['width'], $variants[$i]['height']); //frame size of crop
        $cropPointThumb = new Point($cropInfo['x'], $cropInfo['y']);
        $pathThumbImage = Yii::getAlias('@path/to/save/image') . '/thumb_' . $this->id . '.' . $i . '.' . $this->image->getExtension();  

        $image->copy()
            ->resize($newSizeThumb)
            ->crop($cropPointThumb, $cropSizeThumb)
            ->save($pathThumbImage, ['quality' => 100]);

        //saving original
        $this->image->saveAs(Yii::getAlias('@path/to/save/image') . $this->id . '.' . $i . '.' . $this->image->getExtension());
    }
}

...

```

### Use resizing

If you want use resizing you need pointer min and max size of image in `variants` of `pluginOptions`.

**In model:**

```php
// open image
$image = Image::getImagine()->open($this->image->tempName);

// rendering information about crop of ONE option 
$cropInfo = Json::decode($this->crop_info)[0];
$cropInfo['dWidth'] = (int)$cropInfo['dWidth']; //new width image
$cropInfo['dHeight'] = (int)$cropInfo['dHeight']; //new height image
$cropInfo['x'] = abs($cropInfo['x']); //begin position of frame crop by X
$cropInfo['y'] = abs($cropInfo['y']); //begin position of frame crop by Y
$cropInfo['width'] = (int)$cropInfo['width']; //width of cropped image
$cropInfo['height'] = (int)$cropInfo['height']; //height of cropped image
// Properties bolow we don't use in this example
//$cropInfo['ratio'] = $cropInfo['ratio'] == 0 ? 1.0 : (float)$cropInfo['ratio']; //ratio image. 

//delete old images
$oldImages = FileHelper::findFiles(Yii::getAlias('@path/to/save/image'), [
    'only' => [
        $this->id . '.*',
        'thumb_' . $this->id . '.*',
    ], 
]);
for ($i = 0; $i != count($oldImages); $i++) {
    @unlink($oldImages[$i]);
}

//saving thumbnail
$newSizeThumb = new Box($cropInfo['dWidth'], $cropInfo['dHeight']);
$cropSizeThumb = new Box($cropInfo['width'], $cropInfo['height']); //frame size of crop
$cropPointThumb = new Point($cropInfo['x'], $cropInfo['y']);
$pathThumbImage = Yii::getAlias('@path/to/save/image') . '/thumb_' . $this->id . '.' . $this->image->getExtension();  

$image->resize($newSizeThumb)
    ->crop($cropPointThumb, $cropSizeThumb)
    ->save($pathThumbImage, ['quality' => 100]);
    
//saving original
$this->image->saveAs(Yii::getAlias('@path/to/save/image') . $this->id . '.' . $this->image->getExtension());
```

## License

yii2-widget-cropbox is released under the BSD 3-Clause License.

