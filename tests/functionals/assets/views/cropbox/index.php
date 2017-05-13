<?php

use bupy7\cropbox\CropboxWidget;
use yii\widgets\ActiveForm;

$af = ActiveForm::begin([
    'options' => ['enctype'=>'multipart/form-data'],
    'action' => ['cropbox'],
]);

echo $af->field($form, 'image')->widget(CropboxWidget::className(), [
    'croppedDataAttribute' => 'crop_info',
]);

ActiveForm::end();
