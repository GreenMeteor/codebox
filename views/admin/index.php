<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<div class="panel panel-default">

    <div class="panel-heading"><?= Yii::t('CodeboxModule.base', '<strong>Codebox</strong> module configuration'); ?></div>
    <div class="panel-body">

<?php $form = ActiveForm::begin(['id' => 'configure-form']); ?>
<div class="form-group">
    <?= $form->field($model, 'title')->textInput(['class' => 'form-control', 'placeholder' => 'Title', 'disabled' => false])->label(true) ?>
    <?= $form->field($model, 'htmlCode')->textarea(['rows' => '8']); ?>
    <?= $form->field($model, 'sortOrder')->textInput(['class' => 'form-control', 'placeholder' => 'Select a sort order...', 'disabled' => false])->label(true) ?>
</div>
<hr>

<div class="form-group">
    <?= Html::submitButton(Yii::t('CodeboxModule.base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']); ?>
</div>

<?php ActiveForm::end(); ?>
</div>
</div>
