<?php
use yii\helpers\Html;
use bupy7\cropbox\Cropbox;
use kartik\slider\Slider;
use kartik\icons\Icon;

Icon::map($this, Icon::FA);
?>
<div id="<?= $idWidget; ?>" class="cropbox">
    <div class="imageBox">
        <div class="thumbBox"></div>
    </div>
    <p class="message"></p>
    <div class="btn-group">
        <span class="btn btn-primary btn-file">
            <?= Icon::show('folder-open') . Cropbox::t('Browse') . Html::activeFileInput($model, $attribute, $options); ?>
        </span>
        <?php
        echo Html::button(Icon::show('expand '), [
            'class' => 'btn btn-default btnZoomIn',
        ]);
        echo Html::button(Icon::show('compress'), [
            'class' => 'btn btn-default btnZoomOut',
        ]);
        echo Html::button(Icon::show('crop') . Cropbox::t('Crop'), [
            'class' => 'btn btn-success btnCrop',
        ]);
        ?>
    </div>
    <div class="form-horizontal">
        <div class="form-group resizeWidth">
            <label for="<?= $idWidget; ?>_cbox_resize_width" class="col-md-3"><?= Cropbox::t('Width'); ?></label>
            <div class="col-md-6">
                <?= Slider::widget([
                    'name' => $idWidget . '_cbox_resize_width',
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
                            $('#{$idWidget}').cropbox('resizeThumbBox', {width: e.value});
                        }",
                    ],
                ]); ?>
            </div>
        </div>
        <div class="form-group resizeHeight">
            <label for="<?= $idWidget; ?>_cbox_resize_height" class="col-md-3"><?= Cropbox::t('Height'); ?></label>
            <div class="col-md-6">
                <?= Slider::widget([
                    'name' => $idWidget . '_cbox_resize_height',
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
                            $('#{$idWidget}').cropbox('resizeThumbBox', {height: e.value});
                        }",
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <div class="cropped">
        <?php
        if (is_string($originalUrl) && !empty($originalUrl))
        {
            echo Html::a(Icon::show('eye') . Cropbox::t('Show original'), $originalUrl, [
                'target' => '_blank',
                'class' => 'btn btn-info',
            ]);
        }
        if (!empty($previewUrl))
        {
            foreach ($previewUrl as $url) {
                echo Html::img($url, ['class' => 'img-thumbnail']);
            }
        }
        ?>
    </div>
</div>
<?php
echo Html::activeHiddenInput($model, $attributeCropInfo);
