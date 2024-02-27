<?php

use humhub\libs\Html;
use humhub\widgets\PanelMenu;

?>

<div class="panel panel-default panel-codebox" id="panel-codebox">
    <?= PanelMenu::widget(['id' => 'panel-codebox']); ?>
  <div class="panel-heading">
    <strong><?= Html::encode($title) ?></strong>
  </div>
  <div class="panel-body">

<?= Html::beginTag('div') ?>
<?= $htmlCode ?>
<?= Html::endTag('div'); ?>

</div>
</div>
