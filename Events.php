<?php

namespace humhub\modules\codebox;

use Yii;
use yii\helpers\Url;
use yii\base\BaseObject;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\codebox\widgets\CodeboxFrame;
use humhub\modules\codebox\models\ConfigureForm;
use humhub\modules\admin\permissions\ManageModules;

class Events extends BaseObject
{

    public static function onAdminMenuInit($event)
    {
        if (!Yii::$app->user->can(ManageModules::class)) {
            return;
        }

        /** @var AdminMenu $menu */
        $menu = $event->sender;

        $menu->addEntry(new MenuLink([
            'label' => Yii::t('CodeboxModule.base', 'Codebox Settings'),
            'url' => Url::toRoute('/codebox/admin/index'),
            'icon' => Icon::get('code'),
            'isActive' => Yii::$app->controller->module && Yii::$app->controller->module->id == 'codebox' && Yii::$app->controller->id == 'admin',
            'sortOrder' => 600,
            'isVisible' => true,
        ]));
    }

    public static function addCodeboxFrame($event)
    {
        $module = Yii::$app->getModule('codebox');

        if ($module !== null) {
            $entries = ConfigureForm::find()->asArray()->all();
            
            // Process entries to ensure they have the correct structure for the enhanced widget
            $processedEntries = [];
            foreach ($entries as $entry) {
                $processedEntries[] = [
                    'title' => $entry['title'] ?? '',
                    'htmlCode' => $entry['htmlCode'] ?? '',
                    'codeType' => $entry['codeType'] ?? 'html', // Default to HTML if not specified
                    'sortOrder' => $entry['sortOrder'] ?? 0,
                    'id' => $entry['id'] ?? null,
                ];
            }

            $event->sender->addWidget(
                CodeboxFrame::class,
                [
                    'entries' => $processedEntries,
                    'enablePhpExecution' => $module->enablePhpExecution ?? true, // Get from module config
                    'showPanelMenu' => true
                ],
                ['sortOrder' => $module->getOrder()]
            );
        }
    }

    /**
     * Process code based on its type for backward compatibility
     * @param string $code The code to process
     * @param string $codeType The type of code (html, php, yii2)
     * @return string Processed code
     */
    public static function processCode($code, $codeType = 'html')
    {
        switch (strtolower($codeType)) {
            case 'php':
                return self::processPhpCode($code);
            case 'yii2':
                return self::processYii2Code($code);
            case 'html':
            case 'javascript':
            case 'css':
            default:
                return self::processHtmlCode($code);
        }
    }

    /**
     * Process HTML/CSS/JavaScript code
     * @param string $code
     * @return string
     */
    protected static function processHtmlCode($code)
    {
        // Generate nonce attribute if needed
        if (strpos($code, '{{nonce}}') !== false) {
            $nonce = Yii::$app->security->generateRandomString(16);
            $code = str_replace('{{nonce}}', $nonce, $code);
        }
        
        return $code;
    }

    /**
     * Process PHP code safely
     * @param string $code
     * @return string
     */
    protected static function processPhpCode($code)
    {
        $module = Yii::$app->getModule('codebox');
        
        if (!$module || !($module->enablePhpExecution ?? true)) {
            return '<pre class="prettyprint lang-php">' . \yii\helpers\Html::encode($code) . '</pre>';
        }

        // Security check
        if (!self::validatePhpCode($code)) {
            return '<div class="alert alert-danger">Security: PHP code contains restricted functions</div>';
        }

        try {
            // Start output buffering
            ob_start();
            
            // Add PHP opening tag if not present
            if (strpos($code, '<?php') === false && strpos($code, '<?=') === false) {
                $code = '<?php ' . $code;
            }

            // Execute PHP code
            eval('?>' . $code);
            
            // Get the output
            $output = ob_get_clean();
            
            return $output;
            
        } catch (\Exception $e) {
            ob_end_clean();
            return '<div class="alert alert-danger">PHP Error: ' . \yii\helpers\Html::encode($e->getMessage()) . '</div>';
        } catch (\ParseError $e) {
            ob_end_clean();
            return '<div class="alert alert-danger">PHP Parse Error: ' . \yii\helpers\Html::encode($e->getMessage()) . '</div>';
        }
    }

    /**
     * Process Yii2 specific code
     * @param string $code
     * @return string
     */
    protected static function processYii2Code($code)
    {
        $module = Yii::$app->getModule('codebox');
        
        if (!$module || !($module->enablePhpExecution ?? true)) {
            return '<pre class="prettyprint lang-php">' . \yii\helpers\Html::encode($code) . '</pre>';
        }

        // Security check
        if (!self::validatePhpCode($code)) {
            return '<div class="alert alert-danger">Security: PHP code contains restricted functions</div>';
        }

        try {
            // Start output buffering
            ob_start();
            
            // Make Yii2 components available in the code context
            $app = Yii::$app;
            $user = Yii::$app->user;
            $request = Yii::$app->request;
            $response = Yii::$app->response;
            
            // Add PHP opening tag if not present
            if (strpos($code, '<?php') === false && strpos($code, '<?=') === false) {
                $code = '<?php ' . $code;
            }

            // Execute Yii2 code with context
            eval('?>' . $code);
            
            // Get the output
            $output = ob_get_clean();
            
            return $output;
            
        } catch (\Exception $e) {
            ob_end_clean();
            return '<div class="alert alert-danger">Yii2 Error: ' . \yii\helpers\Html::encode($e->getMessage()) . '</div>';
        } catch (\ParseError $e) {
            ob_end_clean();
            return '<div class="alert alert-danger">Yii2 Parse Error: ' . \yii\helpers\Html::encode($e->getMessage()) . '</div>';
        }
    }

    /**
     * Validate PHP code for security
     * @param string $code
     * @return bool
     */
    protected static function validatePhpCode($code)
    {
        // List of dangerous functions to block
        $dangerousFunctions = [
            'exec', 'system', 'shell_exec', 'passthru', 'eval', 'file_get_contents',
            'file_put_contents', 'fopen', 'fwrite', 'unlink', 'mkdir', 'rmdir',
            'chmod', 'chown', 'curl_exec', 'curl_init', 'mail', 'header',
            'exit', 'die', 'include', 'require', 'include_once', 'require_once'
        ];

        // Check for dangerous functions
        foreach ($dangerousFunctions as $func) {
            if (strpos($code, $func) !== false) {
                return false;
            }
        }

        return true;
    }
}