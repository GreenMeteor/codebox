<?php

namespace humhub\modules\codebox\components;

use yii\base\Component;

class HtmlParser extends Component
{
    private $html;

    public function __construct($html, $config = [])
    {
        $this->html = $html;
        parent::__construct($config);
    }

    public function render()
    {
        // Render the HTML content as-is
        return $this->html;
    }
}
