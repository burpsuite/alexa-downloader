<?php

namespace BurpSuite\Alexa;

use \Curl\Curl;

class CurlX extends Curl
{
    public function setDisableTransfer($curl_content)
    {
        switch ($curl_content) {
            case is_object($curl_content):
                return (string) json_encode($curl_content);
                break;
            case is_string($curl_content):
                return $curl_content;
                break;
        }

        return $this;
    }
}