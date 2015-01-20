yii2-widget-cropbox
============

This is widget wrapper and fork of Cropbox https://github.com/hongkhanh/cropbox . This widget allows crop image before upload to server and send informations about crop in JSON format.

![Screenshot](screenshot.png)

##Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run
```
$ php composer.phar require bupy7/yii2-widget-cropbox "1.0"
```

or add
```
"bupy7/yii2-widget-cropbox": "1.0"
```

to the **require** section of your **composer.json** file.

##How use

For example I will be use **Imagine extensions for Yii2** https://github.com/yiisoft/yii2-imagine . You can use something other.

Add to action of controller
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

Add to view
```php
use bupy7\cropbox\Cropbox;

$form = ActiveForm::begin([
    'options' => ['enctype'=>'multipart/form-data'],
]);

...

echo $form->field($model, 'image')->widget(Cropbox::className(), [
    'attributeCropInfo' => 'crop_info',
]);

...
```

Add to model:
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
        'file', 
        'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
        'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],
    ],
    ['crop_info', 'safe'],
    
    ...
}

...

public function afterSave()
{
    ...
    
    // open image
    $image = Image::getImagine()->open($this->image->tempName);
    
    //rendering information about crop
    $cropInfo = Json::decode($this->crop_info);
    $cropInfo['dw'] = (int)$cropInfo['dw']; //new width image
    $cropInfo['dh'] = (int)$cropInfo['dh']; //new height image
    $cropInfo['x'] = abs($cropInfo['x']); //begin position of frame crop by X
    $cropInfo['y'] = abs($cropInfo['y']); //begin position of frame crop by Y
    //$cropInfo['ratio'] = $cropInfo['ratio'] == 0 ? 1.0 : (float)$cropInfo['ratio']; //ratio image. We don't use in this example
    
    //delete old images
    $oldImages = FileHelper::findFiles(Yii::getAlias('@path/to/save/image'), [
        'only' => [
            $this->id . '.*',
            'thumb_' . $id . '.*',
        ], 
    ]);
    for ($i = 0; $i != count($oldImages); $i++)
    {
        @unlink($oldImages[$i]);
    }
    
    //saving thumbnail
    $newSizeThumb = new Box($cropInfo['dw'], $cropInfo['dh']);
    $cropSizeThumb = new Box(200, 200); //frame size of crop
    $cropPointThumb = new Point($cropInfo['x'], $cropInfo['y']);
    $pathThumbImage = Yii::getAlias('@path/to/save/image') . '/thumb_' . $this->id . '.' . $this->image->getExtension();  
    
    $image->resize($newSizeThumb)
        ->crop($cropPointThumb, $cropSizeThumb)
        ->save($pathThumbImage, ['quality' => 100]);
        
    //saving original
    $this->image->saveAs(Yii::getAlias('@path/to/save/image') . $this->id . '.' . $this->image->getExtension());
}

...
```

##Configuration

####Thumbnail box

By default thumbnail box has dimensions 200x200px. You can change their:

```php
echo $form->field($model, 'image')->widget(Cropbox::className(), [
    'attributeCropInfo' => 'crop_info',
    'optionsCropbox' => [
        'thumbWidth' => 350,
        'thumbHeight' => 400,
    ],
]);
```

####Frame cropping

By default frame cropping centrally located. You can change it:

```php
echo $form->field($model, 'image')->widget(Cropbox::className(), [
    'attributeCropInfo' => 'crop_info',
    'optionsCropbox' => [
        'thumbWidth' => 350,
        'thumbHeight' => 400,
        'thumbMarginTop' => 8,
        'thumbMarginLeft' => 3,
    ],
]);
```

####Preview exist image of item

If you want showing uploaded and cropped image, you must add following code:

```php
echo $form->field($model, 'image')->widget(Cropbox::className(), [
    'attributeCropInfo' => 'crop_info',
    'optionsCropbox' => [
        'thumbWidth' => 350,
        'thumbHeight' => 400,
        'thumbMarginTop' => 8,
        'thumbMarginLeft' => 3,
    ],
    'previewUrl' => 'url/to/small/image',
    'originalUrl' => 'url/to/original/image', 
]);
```

If you click to preview image then you see original image.

####Crop with save real size of image

The difference from previous methods in that we do not resize of image before crop it. Here we crop of image as we see it in editor box with saving real size.

For this we will use of property `ratio` from `$cropInfo`.

```php
$cropInfo = Json::decode($this->crop_info);
$cropInfo['dw'] = (int)$cropInfo['dw'];
$cropInfo['dh'] = (int)$cropInfo['dh'];
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

##License

yii2-widget-cropbox is released under the BSD 3-Clause License.

