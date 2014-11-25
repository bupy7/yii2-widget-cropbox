yii2-cropbox
============

This is widget wrapper and fork of Cropbox https://github.com/hongkhanh/cropbox . This widget allows crop image before upload to server and send informations about crop in JSON format.

##Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run
```
$ php composer.phar require bupy7/yii2-widget-cropbox "dev-master"
```

or add
```
"bupy7/yii2-widget-cropbox": "dev-master"
```

to the **require** section of your **composer.json** file.

##How use

Add to view
```php
use bupy7\cropbox\Cropbox;

...

echo $form->field($model, 'image')->widget(Cropbox::className(), [
    'attributeCropInfo' => 'crop_info',
]);

...
```

Add to model:
```php
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
    ['crop_info', 'filter', 'filter' => function($value) {
        return \yii\helpers\Json::decode($value);
    }],
    
    ...
}

...

public function beforeSave()
{
    
}

...
```

#IN DEVELOPING!!! DON'T USE!!!

