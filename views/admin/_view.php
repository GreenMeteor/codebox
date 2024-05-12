<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConfigureForm */

// Generate unique IDs for panel menu and panel content
$panelMenuId = 'panel-menu-' . $model->id;
$panelContentId = 'panel-content-' . $model->id;

$js = <<< JS
$(document).ready(function() {
    var timer;
    
    $('#panel-heading-$model->id').hover(
        function() {
            clearTimeout(timer); // Clear the collapse timer
            $('#panel-content-$model->id').collapse('show');
        },
        function() {
            timer = setTimeout(function() {
                $('#panel-content-$model->id').collapse('hide');
            }, 500); // Adjust the delay time as needed (in milliseconds)
        }
    );
    
    // Keep the panel content open when hovering over it
    $('#panel-content-$model->id').hover(
        function() {
            clearTimeout(timer); // Clear the collapse timer
        },
        function() {
            timer = setTimeout(function() {
                $('#panel-content-$model->id').collapse('hide');
            }, 500); // Adjust the delay time as needed (in milliseconds)
        }
    );
});
JS;
$this->registerJs($js);

?>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading" id="panel-heading-<?= $model->id ?>">
            <?= Html::encode($model->title) ?>
        </div>
        <div class="panel-body collapse" id="<?= $panelContentId ?>">
            <?= Html::encode($model->htmlCode) ?>
            <br>
            <div class="form-group">
                <?= Html::a(Yii::t('CodeboxModule.base', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm', 'data-toggle' => 'modal', 'data-target' => '#globalModal']); ?>
                <?= Html::a(Yii::t('CodeboxModule.base', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-sm',
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                ]); ?>
            </div>
        </div>
    </div>
</div>