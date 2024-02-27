<?php

use humhub\modules\ui\form\widgets\CodeMirrorInputWidget;
use humhub\modules\ui\form\widgets\SortOrderField;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\libs\Html;

?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('CodeboxModule.base', '<strong>Codebox</strong> module configuration'); ?></div>
    <div class="panel-body">

<?php $form = ActiveForm::begin(['id' => 'configure-form']); ?>
<div class="form-group">
    <?= $form->field($model, Html::encode('title'))->textInput(['class' => 'form-control', 'placeholder' => 'Title', 'disabled' => false])->label(true) ?>
    <?= $form->field($model, Html::encode('htmlCode'))->widget(CodeMirrorInputWidget::class); ?>
    <?= $form->field($model, 'sortOrder')->widget(SortOrderField::class) ?>
</div>
<hr>

<div class="form-group">
    <?= Html::submitButton(Yii::t('CodeboxModule.base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']); ?>
</div>

<?php ActiveForm::end(); ?>
</div>
</div>
