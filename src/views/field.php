<?php

use yii\helpers\Html;
use bupy7\cropbox\CropboxWidget;

?>
<div id="<?= $this->context->id; ?>" class="cropbox">
    <div class="alert alert-info message-container-cropbox"></div>
    <div class="plugin"></div>
    <div class="btn btn-primary btn-file">
        <?php
        echo '<i class="glyphicon glyphicon-folder-open"></i> ' . CropboxWidget::t('Browse');
        if ($hasModel) {
            echo Html::activeFileInput($this->context->model, $this->context->attribute, $this->context->options);
        } else {
            echo Html::fileInput($this->context->name, $this->context->value, $this->context->options);
        }
        ?>
    </div>
    <div class="btn-group">
        <?= Html::button('<i class="glyphicon glyphicon-scissors"></i> ' . CropboxWidget::t('Crop'), [
            'class' => 'btn btn-success btn-crop',
        ]); ?>
        <?= Html::button('<i class="glyphicon glyphicon-repeat"></i> ' . CropboxWidget::t('Reset'), [
            'class' => 'btn btn-warning btn-reset',
        ]); ?>
        <?= Html::button('<i class="glyphicon glyphicon-minus"></i> ', [
            'class' => 'btn btn-default btn-scale-out',
        ]); ?>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> ', [
            'class' => 'btn btn-default btn-scale-in',
        ]); ?>
    </div>
    <div class="cropped-images-cropbox">
        <p>
            <?php 
            if (!empty($this->context->originalImageUrl)) {
                echo Html::a(
                    '<i class="glyphicon glyphicon-eye-open"></i> ' . CropboxWidget::t('Show original'),
                    $this->context->originalImageUrl,
                    [
                        'target' => '_blank',
                        'class' => 'btn btn-info',
                    ]
                );
            }
            ?>
        </p>
        <?php
        $croppedImagesUrl = (array) $this->context->croppedImagesUrl;
        foreach ($croppedImagesUrl as $url) {
            echo Html::img($url, ['class' => 'img-thumbnail']);
        }
        ?>
    </div>
    <?php
    if ($hasModel) {
        echo Html::activeHiddenInput($this->context->model, $this->context->croppedDataAttribute);
    } else {
        echo Html::hiddenInput($this->context->croppedDataName, $this->context->croppedDataValue);
    }
    ?>
</div>