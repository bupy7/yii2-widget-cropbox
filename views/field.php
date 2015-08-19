<?php

use yii\helpers\Html;
use bupy7\cropbox\Cropbox;
?> 
<div id="<?= $this->context->id; ?>" class="cropbox">
    <div class="alert alert-info"></div>
    <div class="workarea-cropbox">
        <div class="bg-cropbox">
            <img class="image-cropbox">
            <div class="membrane-cropbox"></div>
        </div>
        <div class="frame-cropbox">
            <div class="resize-cropbox"></div>
        </div>
    </div>
    <div class="btn-group">
        <span class="btn btn-primary btn-file">
        <?= '<i class="glyphicon glyphicon-folder-open"></i> '
            . Cropbox::t('Browse') 
            . Html::activeFileInput($this->context->model, $this->context->attribute, $this->context->options); ?>
        </span>
        <?= Html::button('<i class="glyphicon glyphicon-scissors"></i> ' . Cropbox::t('Crop'), [
            'class' => 'btn btn-success btn-crop',
        ]); ?>
        <?= Html::button('<i class="glyphicon glyphicon-repeat"></i> ' . Cropbox::t('Reset'), [
            'class' => 'btn btn-warning btn-reset',
        ]); ?>
    </div>
    <div class="cropped">
        <p>
            <?php 
            if (!empty($this->context->originalImageUrl)) {
                echo Html::a(
                    '<i class="glyphicon glyphicon-eye-open"></i> ' . Cropbox::t('Show original'), 
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
        if (!empty($this->context->previewImagesUrl)) {
            foreach ($this->context->previewImagesUrl as $url) {
                if (!empty($url)) {
                    echo Html::img($url, ['class' => 'img-thumbnail']);
                }
            }
        }
        ?>
    </div>
    <?= Html::activeHiddenInput($this->context->model, $this->context->attributeCropInfo); ?>
</div>