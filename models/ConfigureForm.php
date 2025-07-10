<?php

namespace humhub\modules\codebox\models;

use Yii;
use humhub\components\ActiveRecord;

/**
 * Codebox ActiveRecord model.
 *
 * @property int $id
 * @property string $title
 * @property string $htmlCode
 * @property string $codeType
 * @property int $sortOrder
 */
class ConfigureForm extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'codebox';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'htmlCode'], 'required'],
            [['title', 'htmlCode'], 'string'],
            [['codeType'], 'string', 'max' => 10],
            [['codeType'], 'in', 'range' => ['html', 'php', 'yii2', 'javascript', 'css']],
            [['codeType'], 'default', 'value' => 'html'],
            [['sortOrder'], 'integer'],
            [['sortOrder'], 'default', 'value' => 0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('CodeboxModule.base', 'ID'),
            'title' => Yii::t('CodeboxModule.base', 'Title:'),
            'htmlCode' => Yii::t('CodeboxModule.base', 'Code snippet:'),
            'codeType' => Yii::t('CodeboxModule.base', 'Code Type:'),
            'sortOrder' => Yii::t('CodeboxModule.base', 'Sort Order'),
        ];
    }

    /**
     * Get available code types
     * @return array
     */
    public static function getCodeTypes()
    {
        return [
            'html' => Yii::t('CodeboxModule.base', 'HTML/CSS/JavaScript'),
            'php' => Yii::t('CodeboxModule.base', 'PHP'),
            'yii2' => Yii::t('CodeboxModule.base', 'Yii2 Framework'),
            'javascript' => Yii::t('CodeboxModule.base', 'JavaScript'),
            'css' => Yii::t('CodeboxModule.base', 'CSS'),
        ];
    }

    /**
     * Get code type label
     * @return string
     */
    public function getCodeTypeLabel()
    {
        $types = self::getCodeTypes();
        return $types[$this->codeType] ?? $types['html'];
    }

    /**
     * Get syntax highlighting class for code editor
     * @return string
     */
    public function getSyntaxHighlightingClass()
    {
        $classMap = [
            'html' => 'html',
            'php' => 'php',
            'yii2' => 'php',
            'javascript' => 'javascript',
            'css' => 'css',
        ];
        
        return $classMap[$this->codeType] ?? 'html';
    }

    /**
     * Get placeholder text for code editor
     * @return string
     */
    public function getCodePlaceholder()
    {
        $placeholders = [
            'html' => Yii::t('CodeboxModule.base', 'Enter HTML, CSS, or JavaScript code here...'),
            'php' => Yii::t('CodeboxModule.base', 'Enter PHP code here (without <?php tags)...'),
            'yii2' => Yii::t('CodeboxModule.base', 'Enter Yii2 code here (use $app, $user, etc.)...'),
            'javascript' => Yii::t('CodeboxModule.base', 'Enter JavaScript code here...'),
            'css' => Yii::t('CodeboxModule.base', 'Enter CSS code here...'),
        ];
        
        return $placeholders[$this->codeType] ?? $placeholders['html'];
    }

    /**
     * Validate PHP code for security if code type is PHP or Yii2
     * @return bool
     */
    public function validatePhpCode()
    {
        if ($this->codeType !== 'php' && $this->codeType !== 'yii2') {
            return true;
        }

        // List of dangerous functions to block
        $dangerousFunctions = [
            'exec', 'system', 'shell_exec', 'passthru', 'eval', 'file_get_contents',
            'file_put_contents', 'fopen', 'fwrite', 'unlink', 'mkdir', 'rmdir',
            'chmod', 'chown', 'curl_exec', 'curl_init', 'mail', 'header',
            'exit', 'die', 'include', 'require', 'include_once', 'require_once',
            'move_uploaded_file', 'copy', 'rename', 'symlink', 'link'
        ];

        // Check for dangerous functions
        foreach ($dangerousFunctions as $func) {
            if (strpos($this->htmlCode, $func) !== false) {
                $this->addError('htmlCode', 
                    Yii::t('CodeboxModule.base', 'Code contains restricted function: {function}', [
                        'function' => $func
                    ])
                );
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Validate PHP code if applicable
        if (!$this->validatePhpCode()) {
            return false;
        }

        // Auto-detect code type if not set
        if (empty($this->codeType) || $this->codeType === 'html') {
            $this->codeType = $this->detectCodeType();
        }

        return true;
    }

    /**
     * Auto-detect code type based on content
     * @return string
     */
    protected function detectCodeType()
    {
        if (empty($this->htmlCode)) {
            return 'html';
        }

        // Check for PHP tags
        if (strpos($this->htmlCode, '<?php') !== false || strpos($this->htmlCode, '<?=') !== false) {
            return 'php';
        }

        // Check for Yii2 specific patterns
        if (preg_match('/\$app|\$user|Yii::|ActiveRecord|Controller/', $this->htmlCode)) {
            return 'yii2';
        }

        // Check for JavaScript patterns
        if (preg_match('/function\s*\(|var\s+\w+|let\s+\w+|const\s+\w+|document\.|window\.|console\./', $this->htmlCode)) {
            return 'javascript';
        }

        // Check for CSS patterns
        if (preg_match('/\{[^}]*\}|@media|@import|@font-face/', $this->htmlCode)) {
            return 'css';
        }

        return 'html';
    }

    /**
     * Get example code for each type
     * @param string $type
     * @return string
     */
    public static function getExampleCode($type)
    {
        $examples = [
            'html' => '<div class="alert alert-info">
    <h4>Welcome!</h4>
    <p>This is an HTML example.</p>
</div>',
            'php' => 'echo "Hello, World!";
echo "<br>";
echo "Current time: " . date("Y-m-d H:i:s");',
            'yii2' => 'echo "Welcome, " . ($user->isGuest ? "Guest" : $user->identity->username);
echo "<br>";
echo "Current URL: " . $app->request->url;',
            'javascript' => 'console.log("Hello from JavaScript!");
document.getElementById("demo").innerHTML = "Dynamic content!";',
            'css' => '.custom-style {
    color: #007bff;
    font-weight: bold;
    margin: 10px 0;
}'
        ];

        return $examples[$type] ?? $examples['html'];
    }

    /**
     * Get security warning message for PHP/Yii2 code
     * @return string|null
     */
    public function getSecurityWarning()
    {
        if ($this->codeType === 'php' || $this->codeType === 'yii2') {
            return Yii::t('CodeboxModule.base', 
                'Warning: PHP code will be executed on the server. Only use trusted code and avoid dangerous functions.'
            );
        }
        
        return null;
    }
}