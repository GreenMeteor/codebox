<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\codebox\components\HtmlParser;
use humhub\modules\codebox\Events;

/* @var $this yii\web\View */
/* @var $model \humhub\modules\codebox\models\ConfigureForm */

// Generate unique IDs for panel menu and panel content
$panelMenuId = 'panel-menu-' . $model->id;
$panelContentId = 'panel-content-' . $model->id;

// Determine code type - check if codeType attribute exists, otherwise detect from content
$codeType = $model->codeType ?? 'html';

// Auto-detect code type if not specified
if ($codeType === 'html' && !empty($model->htmlCode)) {
    // Simple detection logic
    if (strpos($model->htmlCode, '<?php') !== false || strpos($model->htmlCode, '<?=') !== false) {
        $codeType = 'php';
    } elseif (strpos($model->htmlCode, 'Yii::$app') !== false || strpos($model->htmlCode, '$user') !== false) {
        $codeType = 'yii2';
    }
}

// Process the code based on its type
if ($codeType === 'php' || $codeType === 'yii2') {
    // Use the Events class methods for PHP/Yii2 processing
    $renderedHtml = Events::processCode($model->htmlCode, $codeType);
} else {
    // Use existing HtmlParser for HTML/CSS/JavaScript
    $htmlParser = new HtmlParser($model->htmlCode);
    $renderedHtml = $htmlParser->render();
}

// Get code type label for display
$codeTypeLabels = [
    'html' => 'HTML',
    'php' => 'PHP',
    'yii2' => 'Yii2',
    'javascript' => 'JS',
    'css' => 'CSS'
];
$codeTypeLabel = $codeTypeLabels[$codeType] ?? 'HTML';

?>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-left">
                <?= Html::encode($model->title) ?>
            </div>
            <div class="pull-right">
                <span class="label label-info"><?= $codeTypeLabel ?></span>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <!-- Use details/summary for expandable content (no JS required) -->
        <details class="panel-details">
            <summary class="panel-summary">View Code Output</summary>
            <div class="panel-body">
                <?php if ($codeType === 'php' || $codeType === 'yii2'): ?>
                    <!-- PHP/Yii2 code output -->
                    <div class="codebox-output">
                        <?= $renderedHtml ?>
                    </div>
                <?php else: ?>
                    <!-- HTML/CSS/JavaScript output -->
                    <?= $renderedHtml ?>
                <?php endif; ?>
                
                <br>
                <div class="form-group">
                    <?= Html::a(Yii::t('CodeboxModule.base', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm', 'data-toggle' => 'modal', 'data-target' => '#globalModal']); ?>
                    <?= Html::a(Yii::t('CodeboxModule.base', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger btn-sm',
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
                    
                    <?php if ($codeType === 'php' || $codeType === 'yii2'): ?>
                        <!-- Code type change form -->
                        <div class="code-type-form pull-right">
                            <?= Html::beginForm(['change-code-type'], 'post', ['class' => 'form-inline']) ?>
                                <?= Html::hiddenInput('id', $model->id) ?>
                                <?= Html::dropDownList('codeType', $codeType, [
                                    'html' => 'HTML',
                                    'php' => 'PHP',
                                    'yii2' => 'Yii2'
                                ], ['class' => 'form-control input-sm', 'id' => 'codeType-' . $model->id]) ?>
                                <?= Html::submitButton('Change Type', ['class' => 'btn btn-default btn-sm']) ?>
                            <?= Html::endForm() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </details>
    </div>
</div>

<style>
.codebox-output {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 10px;
    margin-bottom: 10px;
}

.codebox-output pre {
    background: transparent;
    border: none;
    padding: 0;
    margin: 0;
}

.panel-heading .label {
    font-size: 10px;
}

/* Style the details/summary elements */
.panel-details {
    border: none;
    margin: 0;
}

.panel-summary {
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    border-top: none;
    padding: 10px 15px;
    cursor: pointer;
    list-style: none;
    outline: none;
}

.panel-summary:hover {
    background-color: #e8e8e8;
}

.panel-summary::-webkit-details-marker {
    display: none;
}

.panel-summary::before {
    content: "▶ ";
    display: inline-block;
    margin-right: 5px;
    transition: transform 0.2s;
}

.panel-details[open] .panel-summary::before {
    transform: rotate(90deg);
}

.panel-body {
    border: 1px solid #ddd;
    border-top: none;
    padding: 15px;
}

.code-type-form {
    display: inline-block;
}

.code-type-form .form-control {
    width: auto;
    display: inline-block;
    margin-right: 5px;
}

.form-inline .form-control {
    vertical-align: middle;
}
</style>