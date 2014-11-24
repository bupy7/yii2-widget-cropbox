<div id="<?= $this->id; ?>" class="cropbox">
    <div class="imageBox">
        <div class="thumbBox"></div>
    </div>
    <div class="cropped">
        <?php
        if ($this->thumbUrl && $this->originalUrl)
        {
            echo TbHtml::link(CHtml::image($this->thumbUrl, '', array('class' => 'img-polaroid')), $this->originalUrl);
        }
        ?>
    </div>
    <?= $this->form->fileFieldRow($this->model, $this->attribute, $this->htmlOptions, $this->rowOptions); ?>
    <div class="control-group">
        <div class="controls">
            <?php
            echo TbHtml::button(Yii::t('CropboxWidget.core', 'Crop'), array(
                'class' => 'btn-success btnCrop',
                'icon' => 'screenshot',
            ));
            echo TbHtml::button('', array(
                'class' => 'btnZoomIn',
                'icon' => 'resize-full',
            ));
            echo TbHtml::button('', array(
                'class' => 'btnZoomOut',
                'icon' => 'resize-small',
            ));
            ?>
        </div>
    </div>
    <?= CHtml::activeHiddenField($this->model, $this->attributeCropInfo); ?>
</div>
