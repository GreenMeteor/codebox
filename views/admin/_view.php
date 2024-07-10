<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\codebox\components\HtmlParser;

/* @var $this yii\web\View */
/* @var $model \humhub\modules\codebox\models\ConfigureForm */

// Generate unique IDs for panel menu and panel content
$panelMenuId = 'panel-menu-' . $model->id;
$panelContentId = 'panel-content-' . $model->id;

$js = <<< JS
$(document).ready(function() {
    var timer;
    
    $('#panel-heading-$model->id').hover(
        function() {
            clearTimeout(timer);
            $('#panel-content-$model->id').collapse('show');
        },
        function() {
            timer = setTimeout(function() {
                $('#panel-content-$model->id').collapse('hide');
            }, 500);
        }
    );
    
    // Keep the panel content open when hovering over it
    $('#panel-content-$model->id').hover(
        function() {
            clearTimeout(timer);
        },
        function() {
            timer = setTimeout(function() {
                $('#panel-content-$model->id').collapse('hide');
            }, 500);
        }
    );
});
JS;
$this->registerJs($js);

// Instantiate HtmlParser and render the HTML code
$htmlParser = new HtmlParser($model->htmlCode);
$renderedHtml = $htmlParser->render();

?>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading" id="panel-heading-<?= $model->id ?>">
            <?= Html::encode($model->title) ?>
        </div>
        <div class="panel-body collapse" id="<?= $panelContentId ?>">
            <?= $renderedHtml ?>
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
