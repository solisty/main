<?php

namespace Solisty\Http;

class ResponseContent
{
    private $content;
    private string $type;

    public static function createFromUnknown($value)
    {
        $content = new ResponseContent;
        $content->content = $value;

        if ($content->content instanceof \Solisty\View\View) {
            $content->type = 'view';
            return $content;
        }

        if (is_array($content->content)) {
            $content->type = 'array';
            return $content;
        }

        if (is_string($content->content)) {
            $content->type = 'string';
            return $content;
        }

        return null;
    }

    public function getType()
    {
        return $this->type;
    }

    public function asJson()
    {
        header('Content-Type: application/json');
        return json_encode($this->content);
    }

    public function asString()
    {
        return $this->content;
    }

    public function asView()
    {
        return $this->content->show();
    }
}
