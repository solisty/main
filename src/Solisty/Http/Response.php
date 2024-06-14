<?php

namespace Solisty\Http;

class Response
{
    private ResponseContent $content;
    public function __construct($content = '', public $status = 200)
    {
        $this->content = ResponseContent::createFromUnknown($content);
        $this->status($status);
    }

    public function send(): void
    {
        switch ($this->content->getType()) {
            case 'view':
                $this->content->asView();
                break;
            case 'string':
                echo $this->content->asString();
                break;
            case 'array':
                echo $this->content->asJson();
        }
    }

    public function status($code)
    {
        http_response_code($code);

        return $this;
    }
}
