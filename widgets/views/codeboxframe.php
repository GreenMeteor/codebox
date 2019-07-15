<?php

use humhub\libs\Html;
use yii\widgets\ActiveForm;
use humhub\widgets\PanelMenu;

\humhub\modules\codebox\Assets::register($this);
?>

<div class="panel panel-default panel-codebox" id="panel-codebox">
    <?= PanelMenu::widget(['id' => 'panel-codebox']); ?>
  <div class="panel-heading">
    <?= Yii::t('CodeboxModule.base', '<strong>Codebox</strong>'); ?>
  </div>
  <div class="panel-body">

<?php $form = ActiveForm::begin(['id' => 'configure-form']); ?>
        <div class="form-group">
            <?= $form->field($model, 'htmlCode'); ?>
        </div>

        <?php ActiveForm::end(); ?>
</div>
</div>
