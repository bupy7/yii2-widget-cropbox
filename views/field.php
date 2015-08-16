<?php

use yii\helpers\Html;
use bupy7\cropbox\Cropbox;
use kartik\slider\Slider;
?>
<div id="<?= $this->context->id; ?>" class="cropbox">
    <div class="workarea-cropbox">
        <div class="bg-cropbox">
            <img class="image-cropbox">
            <div class="membrane-cropbox"></div>
        </div>
        <div class="frame-cropbox"></div>
    </div>
    <p class="message"></p>
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
<!--    <div class="form-horizontal">
        <div class="form-group resize-width">
            <label for="<?= $this->context->id; ?>_cbox_resize_width" class="col-md-3">
                <?= Cropbox::t('Width'); ?>
            </label>
            <div class="col-md-6">
                <?=
                Slider::widget([
                    'name' => $this->context->id . '_cbox_resize_width',
                    'sliderColor' => Slider::TYPE_GREY,
                    'handleColor' => Slider::TYPE_PRIMARY,
                    'pluginOptions' => [
                        'orientation' => 'horizontal',
                        'handle' => 'round',
                        'step' => 1,
                        'tooltip' => 'hide',
                    ],
                    'pluginEvents' => [
                        'slide' => "function(e) {
                            $('#{$this->context->id}').cropbox('resizeThumbBox', {width: e.value});
                        }",
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="form-group resize-height">
            <label for="<?= $this->context->id; ?>_cbox_resize_height" class="col-md-3">
                <?= Cropbox::t('Height'); ?>
            </label>
            <div class="col-md-6">
                <?=
                Slider::widget([
                    'name' => $this->context->id . '_cbox_resize_height',
                    'sliderColor' => Slider::TYPE_GREY,
                    'handleColor' => Slider::TYPE_PRIMARY,
                    'pluginOptions' => [
                        'orientation' => 'horizontal',
                        'handle' => 'round',
                        'step' => 1,
                        'tooltip' => 'hide',
                    ],
                    'pluginEvents' => [
                        'slide' => "function(e) {
                            $('#{$this->context->id}').cropbox('resizeThumbBox', {height: e.value});
                        }",
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>-->
    <div class="cropped">
        <?php
        if (is_string($this->context->originalUrl) && !empty($this->context->originalUrl)) {
            echo Html::a(Icon::show('eye') . Cropbox::t('SHOW_ORIGINAL'), $this->context->originalUrl, [
                'target' => '_blank',
                'class' => 'btn btn-info',
            ]);
        }
        if (!empty($this->context->previewUrl)) {
            foreach ($this->context->previewUrl as $url) {
                echo Html::img($url, ['class' => 'img-thumbnail']);
            }
        }
        ?>
    </div>
</div>
<?php
echo Html::activeHiddenInput($this->context->model, $this->context->attributeCropInfo);
