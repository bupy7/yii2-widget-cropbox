yii2-widget-cropbox
============

This is widget wrapper and fork of Cropbox https://github.com/hongkhanh/cropbox . This widget allows crop image before upload to server and send informations about crop in JSON format.

![Screenshot](screenshot.png)

##Functional

- Simple! =)
- Cropping image before upload to server.
- Cropping more **once** option.
- Labels for settings of crop.
- You can use custom view.
- Resizing cropping image on-the-fly.


##Installation
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

If you use v2.2 then go to [v2.2](https://github.com/bupy7/yii2-widget-cropbox/tree/v2.2).

If you use v1.0 then go to [v1.0](https://github.com/bupy7/yii2-widget-cropbox/tree/v1.0).

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
    
    // rendering information about crop of ONE option 
    $cropInfo = Json::decode($this->crop_info)[0];
    $cropInfo['dw'] = (int)$cropInfo['dw']; //new width image
    $cropInfo['dh'] = (int)$cropInfo['dh']; //new height image
    $cropInfo['x'] = abs($cropInfo['x']); //begin position of frame crop by X
    $cropInfo['y'] = abs($cropInfo['y']); //begin position of frame crop by Y
    // Properties bolow we don't use in this example
    //$cropInfo['ratio'] = $cropInfo['ratio'] == 0 ? 1.0 : (float)$cropInfo['ratio']; //ratio image. 
    //$cropInfo['w'] = (int)$cropInfo['w']; //width of cropped image
    //$cropInfo['h'] = (int)$cropInfo['h']; //height of cropped image
    
    //delete old images
    $oldImages = FileHelper::findFiles(Yii::getAlias('@path/to/save/image'), [
        'only' => [
            $this->id . '.*',
            'thumb_' . $id . '.*',
        ], 
    ]);
    for ($i = 0; $i != count($oldImages); $i++) {
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
        'boxWidth' => 400,
        'boxHeight' => 300,
        'cropSettings' => [
            [
                'width' => 350,
                'height' => 200,
            ],
        ],
    ],
]);
```

####Preview exist image of item

If you want showing uploaded and cropped image, you must add following code:

```php
echo $form->field($model, 'image')->widget(Cropbox::className(), [
    'attributeCropInfo' => 'crop_info',
    'optionsCropbox' => [
        'boxWidth' => 400,
        'boxHeight' => 300,
        'cropSettings' => [
            [
                'width' => 350,
                'height' => 200,
            ],
        ],
    ],
    'previewUrl' => [
        'url/to/small/image'
    ],
    'originalUrl' => 'url/to/original/image', 
]);
```

If you click to preview image then you see original image.

####Crop with save real size of image

The difference from previous methods in that we do not resize of image before crop it. Here we crop of image as we see it in editor box with saving real size.

For this we will use of property `ratio` from `$cropInfo`.

```php
$cropInfo = Json::decode($this->crop_info)[0];
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

####Cropping more once option

View: 

```php
echo $form->field($model, 'image')->widget(Cropbox::className(), [
    'attributeCropInfo' => 'crop_info',
    'optionsCropbox' => [
        'boxWidth' => 400,
        'boxHeight' => 300,
        'cropSettings' => [
            [
                'width' => 150,
                'height' => 150,
            ],
            [
                'width' => 350,
                'height' => 200,
            ]
        ],
        'messages' => [
            'Thumbnail image',
            'Small image',
        ],
    ],
]);
```

Model:

```php
...

public function afterSave()
{
    ...
    
    // open image
    $image = Image::getImagine()->open($this->image->tempName);
    
    $cropSettings = [
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
        $cropInfo['dw'] = (int)$cropInfo['dw']; //new width image
        $cropInfo['dh'] = (int)$cropInfo['dh']; //new height image
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
        $newSizeThumb = new Box($cropInfo['dw'], $cropInfo['dh']);
        $cropSizeThumb = new Box($cropSettings[$i]['width'], $cropSettings[$i]['height']); //frame size of crop
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

#### Use resizing

If you want use resizing then you need pointer min and max size of image to "cropSettings" of "optionsCropbox".

```php
echo $form->field($model, 'image')->widget(Cropbox::className(), [
    'attributeCropInfo' => 'crop_info',
    'optionsCropbox' => [
        'boxWidth' => 400,
        'boxHeight' => 300,
        'cropSettings' => [
            [
                'width' => 350,
                'height' => 200,
                'minHeight' => 150,
                'maxHeight' => 300,
            ],
        ],
    ],
]);
```

To model:

```php
// open image
$image = Image::getImagine()->open($this->image->tempName);

// rendering information about crop of ONE option 
$cropInfo = Json::decode($this->crop_info)[0];
$cropInfo['dw'] = (int)$cropInfo['dw']; //new width image
$cropInfo['dh'] = (int)$cropInfo['dh']; //new height image
$cropInfo['x'] = abs($cropInfo['x']); //begin position of frame crop by X
$cropInfo['y'] = abs($cropInfo['y']); //begin position of frame crop by Y
$cropInfo['w'] = (int)$cropInfo['w']; //width of cropped image
$cropInfo['h'] = (int)$cropInfo['h']; //height of cropped image
// Properties bolow we don't use in this example
//$cropInfo['ratio'] = $cropInfo['ratio'] == 0 ? 1.0 : (float)$cropInfo['ratio']; //ratio image. 

//delete old images
$oldImages = FileHelper::findFiles(Yii::getAlias('@path/to/save/image'), [
    'only' => [
        $this->id . '.*',
        'thumb_' . $id . '.*',
    ], 
]);
for ($i = 0; $i != count($oldImages); $i++) {
    @unlink($oldImages[$i]);
}

//saving thumbnail
$newSizeThumb = new Box($cropInfo['dw'], $cropInfo['dh']);
$cropSizeThumb = new Box($cropInfo['w'], $cropInfo['h']); //frame size of crop
$cropPointThumb = new Point($cropInfo['x'], $cropInfo['y']);
$pathThumbImage = Yii::getAlias('@path/to/save/image') . '/thumb_' . $this->id . '.' . $this->image->getExtension();  

$image->resize($newSizeThumb)
    ->crop($cropPointThumb, $cropSizeThumb)
    ->save($pathThumbImage, ['quality' => 100]);
    
//saving original
$this->image->saveAs(Yii::getAlias('@path/to/save/image') . $this->id . '.' . $this->image->getExtension());
```

##License

yii2-widget-cropbox is released under the BSD 3-Clause License.

