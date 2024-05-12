<?php

use yii\widgets\ListView;
use humhub\libs\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $models humhub\modules\codebox\models\ConfigureForm */
?>
<div class="container panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('CodeboxModule.base', '<strong>Codebox</strong> module configuration'); ?>
    </div>
    <div class="panel-body row">
        <?= ListView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $models,
                'pagination' => false,
            ]),
            'summary' => false,
            'itemView' => '_view',
            'emptyText' => Yii::t('CodeboxModule.base', 'No entries found.'),
        ]); ?>
    </div>
    <div class="form-group">
        <?= Html::a(Yii::t('CodeboxModule.base', 'Add Entry'), ['create'], ['class' => 'btn btn-success btn-sm', 'data-toggle' => 'modal', 'data-target' => '#globalModal']) ?>
    </div>
</div>