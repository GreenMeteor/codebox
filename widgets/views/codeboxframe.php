<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use humhub\widgets\PanelMenu;

\humhub\modules\codebox\Assets::register($this);
?>

<div class="panel panel-default panel-codebox" id="panel-codebox">
    <?= PanelMenu::widget(['id' => 'panel-codebox']); ?>
  <div class="panel-heading">
    <?= Yii::t('CodeboxModule.base', '<strong>Codebox</strong>'); ?>
  </div>
  <div class="panel-body">

<?php $form = ActiveForm::begin([]); ?>
<?= Html::beginTag('div') ?>
<?= $form->field($model, 'htmlCode'); ?>
<?= Html::endTag('div');
<?php ActiveForm::end(); ?>

</div>
</div>
