<?php

namespace app\forms;

use yii\base\Model;

class CropboxForm extends Model
{
    /**
     * @var \yii\web\UploadedFile
     */
    public $image;
    /**
     * @var string
     */
    public $crop_info;

    public function rules()
    {
        return [
            [
                'image',
                'image',
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],
            ],
            ['crop_info', 'safe'],
        ];
    }
}
