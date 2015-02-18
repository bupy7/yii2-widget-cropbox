<?php
use yii\helpers\Html;
use bupy7\cropbox\Cropbox;
?>
<div id="<?= $idWidget; ?>" class="cropbox">
    <div class="imageBox">
        <div class="thumbBox"></div>
    </div>
    <p class="message"></p>
    <div class="btn-group">
        <span class="btn btn-primary btn-file">
            <?= Cropbox::t('Browse') . Html::activeFileInput($model, $attribute, $options); ?>
        </span>
        <?php
        echo Html::button('+', array(
            'class' => 'btn btn-default btnZoomIn',
        ));
        echo Html::button('-', array(
            'class' => 'btn btn-default btnZoomOut',
        ));
        echo Html::button(Cropbox::t('Crop'), array(
            'class' => 'btn btn-success btnCrop',
        ));
        ?>
    </div>
    <div class="cropped">
        <?php
        if (is_string($originalUrl) && !empty($originalUrl))
        {
            echo Html::a(Cropbox::t('Show original'), $originalUrl, [
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
