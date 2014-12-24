<?php
use yii\helpers\Html;
use bupy7\cropbox\Cropbox;
?>
<div id="<?= $idWidget; ?>" class="cropbox">
    <div class="imageBox">
        <div class="thumbBox"></div>
    </div>
    <div class="cropped">
        <?php
        if ($previewUrl && !$originalUrl)
        {
            echo Html::img($previewUrl, ['class' => 'img-thumbnail']);
        }
        elseif ($previewUrl && $originalUrl)
        {
            echo Html::a(Html::img($previewUrl, ['class' => 'img-thumbnail']), $originalUrl, [
                'target' => '_blank',
            ]);
        }
        ?>
    </div>
    <div class="btn-group">
        <span class="btn btn-primary btn-file">
            <?= Cropbox::t('core', 'Browse') . Html::activeFileInput($model, $attribute, $options); ?>
        </span>
        <?php
        echo Html::button('+', array(
            'class' => 'btn btn-default btnZoomIn',
        ));
        echo Html::button('-', array(
            'class' => 'btn btn-default btnZoomOut',
        ));
        echo Html::button(Cropbox::t('core', 'Crop'), array(
            'class' => 'btn btn-success btnCrop',
        ));
        ?>
    </div>
</div>
<?php
echo Html::activeHiddenInput($model, $attributeCropInfo);
