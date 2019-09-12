<?php

use humhub\libs\Html;
use humhub\widgets\PanelMenu;

\humhub\modules\codebox\Assets::register($this);
?>

<div class="panel panel-default panel-codebox" id="panel-codebox">
    <?= PanelMenu::widget(['id' => 'panel-codebox']); ?>
  <div class="panel-heading">
    <?= Yii::t('CodeboxModule.base', '<strong>Codebox</strong>'); ?>
  </div>
  <div class="panel-body">

<?= Html::beginTag('div') ?>
<?= $htmlCode ?>
<?= Html::endTag('div'); ?>

</div>
</div>