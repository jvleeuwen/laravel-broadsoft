<?php

namespace Jvleeuwen\Broadsoft\Services;

class XmlService
{
    public function parse($req)
    {
        // dd($req);
        return simplexml_load_string($req, null, 0, 'xsi', true);
    }

    public function type($xml)
    {
        return str_replace('xsi:', '', (string)$xml->eventData[0]->attributes('xsi1', true)->type);
    }
}
