<?php

namespace Amber\Http;

class Response
{
    private $headers = [];
    private $content;

    // Should probably put defaults here incase the response was not setup properly.
    public function __construct(){}

    /*
    I dont know why but http_response_code is not working for me.
    So I set the response code in the header and I find it more intuitive
    to set it up this way instead of setting it up in the constructor.
    */
    public function setHeader(string $header, string $value)
    {
        $this->headers[$header] = $value;
    }

    // Also did this for consistencies sake.
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function send(): void
    {
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->content;
    }

}