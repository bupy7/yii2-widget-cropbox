<?php
use yii\helpers\Html;
?>
<div id="<?= $this->id; ?>" class="cropbox">
    <div class="imageBox">
        <div class="thumbBox"></div>
    </div>
    <div class="cropped">
        <?php
        if ($this->thumbUrl && $this->originalUrl)
        {
            echo Html::a(Html::img($this->thumbUrl, '', array('class' => 'img-thumbnail')), $this->originalUrl, [
                'target' => '_blank',
            ]);
        }
        ?>
    </div>
    <?= Html::activeFileInput($this->model, $this->attribute, $this->options); ?>
    <div class="control-group">
        <div class="controls">
            <?php
            echo Html::button(Yii::t('CropboxWidget.core', 'Crop'), array(
                'class' => 'btn-success btnCrop',
            ));
            echo Html::button('', array(
                'class' => 'btnZoomIn',
            ));
            echo Html::button('', array(
                'class' => 'btnZoomOut',
            ));
            ?>
        </div>
    </div>
    <?= Html::activeHiddenInput($this->model, $this->attributeCropInfo); ?>
</div>
