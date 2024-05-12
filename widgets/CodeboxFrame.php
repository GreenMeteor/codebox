<?php

namespace humhub\modules\codebox\widgets;

use Yii;
use humhub\libs\Html;
use humhub\widgets\PanelMenu;
use humhub\components\Widget;

/**
 * CodeboxFrame adds HTML snippet code to all layouts extended by config.php
 */
class CodeboxFrame extends Widget
{
    /**
     * @var array $entries Array of widget entries
     */
    public $entries;

    /**
     * @inheritdoc
     */
    public function run()
    {
        // Initialize entries as an empty array if it's null
        $entries = is_array($this->entries) ? $this->entries : [];

        $module = Yii::$app->getModule('codebox');

        $output = '';

        foreach ($entries as $index => $entry) {
            $title = isset($entry['title']) ? Html::encode($entry['title']) : '';
            $htmlCode = isset($entry['htmlCode']) ? $entry['htmlCode'] : '';
            $sortOrder = isset($entry['sortOrder']) ? $entry['sortOrder'] : '';

            if (!$title || !$htmlCode || !$sortOrder) {
                continue;
            }

            // Generate nonce attribute
            $nonce = Html::nonce();

            // Replace {{nonce}} with the generated nonce value
            $htmlCode = str_replace('nonce={{nonce}}', 'nonce=' . $nonce, $htmlCode);

            // Define unique ID for each panel
            $panelId = 'panel-codebox-' . $index;

            // Construct HTML output with unique ID for each panel
            $output .= '<div class="panel panel-default panel-codebox" id="' . $panelId . '">';
            $output .= PanelMenu::widget(['id' => $panelId]);
            $output .= '<div class="panel-heading"><strong>' . $title . '</strong></div>';
            $output .= '<div class="panel-body">' . $htmlCode . '</div>';
            $output .= '</div>';
        }

        return $output;
    }
}