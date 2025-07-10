<?php

namespace humhub\modules\codebox\widgets;

use Yii;
use humhub\libs\Html;
use humhub\widgets\PanelMenu;
use humhub\components\Widget;

/**
 * CodeboxFrame adds HTML snippet code to all layouts extended by config.php
 * Enhanced with PHP/Yii2 support
 */
class CodeboxFrame extends Widget
{
    /**
     * @var array $entries Array of widget entries
     */
    public $entries;

    /**
     * @var bool $showPanelMenu Whether to display the PanelMenu icon
     */
    public $showPanelMenu = true;

    /**
     * @var bool $enablePhpExecution Whether to allow PHP code execution
     */
    public $enablePhpExecution = true;

    /**
     * @var array $allowedPhpFunctions List of allowed PHP functions for security
     */
    public $allowedPhpFunctions = [
        'echo', 'print', 'var_dump', 'print_r', 'count', 'sizeof', 'empty', 'isset',
        'array_merge', 'array_push', 'array_pop', 'implode', 'explode', 'strlen',
        'substr', 'strtolower', 'strtoupper', 'trim', 'date', 'time', 'number_format'
    ];

    /**
     * @inheritdoc
     */
    public function run()
    {
        // Initialize entries as an empty array if it's null
        $entries = is_array($this->entries) ? $this->entries : [];

        // Sort the entries array by 'sortOrder'
        usort($entries, function ($a, $b) {
            return ($a['sortOrder'] ?? 0) <=> ($b['sortOrder'] ?? 0);
        });

        $output = '';

        foreach ($entries as $index => $entry) {
            $title = isset($entry['title']) ? Html::encode($entry['title']) : '';
            $code = isset($entry['htmlCode']) ? $entry['htmlCode'] : '';
            $codeType = isset($entry['codeType']) ? $entry['codeType'] : 'html';
            $sortOrder = isset($entry['sortOrder']) ? $entry['sortOrder'] : '';

            if (!$title || !$code || $sortOrder === '') {
                continue;
            }

            $processedCode = $this->processCode($code, $codeType);

            $panelId = 'panel-codebox-' . $index;

            $codeTypeLabel = $this->getCodeTypeLabel($codeType);

            $output .= '<div class="panel panel-default panel-codebox" id="' . $panelId . '" data-code-type="' . $codeType . '">';
            if ($this->showPanelMenu) {
                $output .= PanelMenu::widget(['id' => $panelId]);
            }

            $output .= '<div class="panel-heading">';
            $output .= '<div class="pull-left"><strong>' . $title . '</strong></div>';
            $output .= '<div class="pull-right">';
            //$output .= '<span class="label ' . $this->getCodeTypeLabelClass($codeType) . '">' . $codeTypeLabel . '</span>';
            $output .= '</div>';
            $output .= '<div class="clearfix"></div>';
            $output .= '</div>';
            $output .= '<div class="panel-body">';

            $output .= $processedCode;
            $output .= '</div>';
            $output .= '</div>';
        }

        return $output;
    }

    /**
     * Process code based on its type
     * @param string $code The code to process
     * @param string $codeType The type of code (html, php, yii2, javascript, css)
     * @return string Processed code
     */
    protected function processCode($code, $codeType)
    {
        switch (strtolower($codeType)) {
            case 'php':
                return $this->processPhpCode($code);
            case 'yii2':
                return $this->processYii2Code($code);
            case 'javascript':
                return $this->processJavaScriptCode($code);
            case 'css':
                return $this->processCssCode($code);
            case 'html':
            default:
                return $this->processHtmlCode($code);
        }
    }

    /**
     * Process HTML/CSS/JavaScript code
     * @param string $code
     * @return string
     */
    protected function processHtmlCode($code)
    {
        $nonce = Html::nonce();

        return str_replace('nonce={{nonce}}', 'nonce=' . $nonce, $code);
    }

    /**
     * Process PHP code
     * @param string $code
     * @return string
     */
    protected function processPhpCode($code)
    {
        if (!$this->enablePhpExecution) {
            return '<pre class="prettyprint lang-php">' . Html::encode($code) . '</pre>';
        }

        if (!$this->validatePhpCode($code)) {
            return '<div class="alert alert-danger">Security: PHP code contains restricted functions</div>';
        }

        try {
            ob_start();

            if (strpos($code, '<?php') === false && strpos($code, '<?=') === false) {
                $code = '<?php ' . $code;
            }

            eval('?>' . $code);

            $output = ob_get_clean();

            return $output;

        } catch (Exception $e) {
            ob_end_clean();
            return '<div class="alert alert-danger">PHP Error: ' . Html::encode($e->getMessage()) . '</div>';
        } catch (ParseError $e) {
            ob_end_clean();
            return '<div class="alert alert-danger">PHP Parse Error: ' . Html::encode($e->getMessage()) . '</div>';
        }
    }

    /**
     * Process Yii2 specific code
     * @param string $code
     * @return string
     */
    protected function processYii2Code($code)
    {
        if (!$this->enablePhpExecution) {
            return '<pre class="prettyprint lang-php">' . Html::encode($code) . '</pre>';
        }

        if (!$this->validatePhpCode($code)) {
            return '<div class="alert alert-danger">Security: PHP code contains restricted functions</div>';
        }

        try {
            ob_start();

            $app = Yii::$app;
            $user = Yii::$app->user;
            $request = Yii::$app->request;
            $response = Yii::$app->response;

            if (strpos($code, '<?php') === false && strpos($code, '<?=') === false) {
                $code = '<?php ' . $code;
            }

            eval('?>' . $code);

            $output = ob_get_clean();

            return $output;

        } catch (Exception $e) {
            ob_end_clean();
            return '<div class="alert alert-danger">Yii2 Error: ' . Html::encode($e->getMessage()) . '</div>';
        } catch (ParseError $e) {
            ob_end_clean();
            return '<div class="alert alert-danger">Yii2 Parse Error: ' . Html::encode($e->getMessage()) . '</div>';
        }
    }

    /**
     * Validate PHP code for security
     * @param string $code
     * @return bool
     */
    protected function validatePhpCode($code)
    {
        $dangerousFunctions = [
            'exec', 'system', 'shell_exec', 'passthru', 'eval', 'file_get_contents',
            'file_put_contents', 'fopen', 'fwrite', 'unlink', 'mkdir', 'rmdir',
            'chmod', 'chown', 'curl_exec', 'curl_init', 'mail', 'header',
            'exit', 'die', 'include', 'require', 'include_once', 'require_once'
        ];

        foreach ($dangerousFunctions as $func) {
            if (strpos($code, $func) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get code type label for display
     * @param string $codeType
     * @return string
     */
    protected function getCodeTypeLabel($codeType)
    {
        $labels = [
            'html' => 'HTML',
            'php' => 'PHP',
            'yii2' => 'Yii2',
            'javascript' => 'JS',
            'css' => 'CSS'
        ];

        return $labels[$codeType] ?? 'HTML';
    }

    /**
     * Get CSS class for code type label
     * @param string $codeType
     * @return string
     */
    protected function getCodeTypeLabelClass($codeType)
    {
        $classes = [
            'html' => 'label-default',
            'php' => 'label-primary',
            'yii2' => 'label-success',
            'javascript' => 'label-warning',
            'css' => 'label-info'
        ];

        return $classes[$codeType] ?? 'label-default';
    }

    /**
     * Get supported code types
     * @return array
     */
    public static function getSupportedTypes()
    {
        return [
            'html' => 'HTML/CSS/JavaScript',
            'php' => 'PHP',
            'yii2' => 'Yii 2 Framework',
            'javascript' => 'JavaScript',
            'css' => 'CSS'
        ];
    }

    /**
     * Process JavaScript code
     * @param string $code
     * @return string
     */
    protected function processJavaScriptCode($code)
    {
        $nonce = Html::nonce();

        if (strpos($code, '<script') === false) {
            $code = '<script nonce="' . $nonce . '">' . $code . '</script>';
        } else {
            $code = str_replace('nonce={{nonce}}', 'nonce="' . $nonce . '"', $code);
        }

        return $code;
    }

    /**
     * Process CSS code
     * @param string $code
     * @return string
     */
    protected function processCssCode($code)
    {
        $nonce = Html::nonce();

        if (strpos($code, '<style') === false) {
            $code = '<style nonce="' . $nonce . '">' . $code . '</style>';
        } else {
            $code = str_replace('nonce={{nonce}}', 'nonce="' . $nonce . '"', $code);
        }

        return $code;
    }
}