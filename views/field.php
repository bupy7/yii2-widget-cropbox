<?php

use yii\helpers\Html;
use bupy7\cropbox\Cropbox;
?>
<div class="container-cropbox">
    <div id="<?= $this->context->id; ?>"></div>
    <div class="btn-group">
        <span class="btn btn-primary btn-file">
            <?php
            echo '<i class="glyphicon glyphicon-folder-open"></i> ' . Cropbox::t('Browse');
            //if ($this->context->hasModel()) {
                echo Html::activeFileInput($this->context->model, $this->context->attribute, $this->context->options);
            //} else {
            //    echo Html::fileInput($this->context->name, $this->context->value, $this->context->options);
            //}
            ?>
        </span>
        <?= Html::button('<i class="glyphicon glyphicon-scissors"></i> ' . Cropbox::t('Crop'), [
            'class' => 'btn btn-success btn-crop',
        ]); ?>
        <?= Html::button('<i class="glyphicon glyphicon-repeat"></i> ' . Cropbox::t('Reset'), [
            'class' => 'btn btn-warning btn-reset',
        ]); ?>
    </div>
    <div class="cropped-images-cropbox">
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