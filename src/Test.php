<?php

namespace Amber;

use Amber\Http\Request;
use Amber\Http\Response;

class Test extends Response {
    public function test(Request $request)
    {
        $content = "CONTROLLER TEST WORKS " . $request->params('name');
        $this->setHeader('Content-Type', 'text/html');
        $this->setHeader('HTTP/1.1', '301 Pending');
        $this->setContent($content);
        $this->send();
    }
}