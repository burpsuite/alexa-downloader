<?php

namespace BurpSuite\Alexa;

use BurpSuite\Alexa\CurlX;

class Kernel {

    public static $start_count       = null;
    public static $end_count         = null;
    public static $accessKeyId       = null;
    public static $secretAccessKey   = null;
    public static $countryCode       = null;
    protected static $action_name    = 'Topsites';
    protected static $response_group = 'Country';
    protected static $endpoint_host  = 'ats.us-west-1.amazonaws.com';
    protected static $service_host   = 'ats.amazonaws.com';
    protected static $time_zone      = null;
    protected static $time_stamp     = null;
    protected static $sig_version    = '2';
    protected static $hash_algorithm = 'HmacSHA256';
    protected static $service_uri    = "/api";
    protected static $service_region = "us-west-1";
    protected static $service_name   = "AlexaTopSites";
    protected static $curl_response  = null;

    public function __toString() {
        return (string) self::$curl_response;
    }

    public function __construct($accessKeyId, $secretAccessKey, $countryCode) {
        isset($accessKeyId) ? self::$accessKeyId = $accessKeyId : null;
        isset($secretAccessKey) ? self::$secretAccessKey = $secretAccessKey : null;
        isset($countryCode) ? self::$countryCode = $countryCode : null;
        self::$time_zone = gmdate("Ymd\THis\Z", time());
        self::$time_stamp = gmdate("Ymd", time());
        $url = 'http://' . self::$service_host . self::$service_uri . '?' . $this->buildQueryParams();
        $this->setCurl($url, $this->makeAuthHeader());
    }

    public function makeAuthHeader() {
        $query_value = $this->buildQueryParams();
        $canonical_header = $this->buildHeaders(true);
        $signed_header = $this->buildHeaders(false);
        $payload_hash = hash('sha256', "");
        $canonicalRequest = "GET" . "\n" . self::$service_uri .
            "\n" . $query_value . "\n" . $canonical_header .
            "\n" . $signed_header . "\n" . $payload_hash;
        $algorithm = "AWS4-HMAC-SHA256";
        $credentialScope = self::$time_stamp . "/" .
            self::$service_region . "/" .
            self::$service_name . "/" . "aws4_request";
        $stringToSign = $algorithm . "\n" .  self::$time_zone .
            "\n" .  $credentialScope . "\n" .
            hash('sha256', $canonicalRequest);
        $signingKey = $this->getSignatureKey();
        $signature = hash_hmac('sha256', $stringToSign, $signingKey);
        $auth_header = $algorithm . ' ' . 'Credential=' .
            self::$accessKeyId . '/' . $credentialScope . ', ' .
            'SignedHeaders=' . $signed_header . ', ' . 'Signature=' . $signature;
        return $auth_header;
    }

    public function setCurl($url, $auth) {
        if (!isset($url) || !isset($auth)) {
            throw new \Exception('url or auth_header is null.');
        }
        $curl = new CurlX();
        $curl->setHeader('Accept','application/tmp');
        $curl->setHeader('Content-Type','application/tmp');
        $curl->setHeader('X-Amz-Date',self::$time_zone);
        $curl->setHeader('Authorization',$auth);
        $curl->get($url);
        self::$curl_response = $curl->setDisableTransfer($curl->rawResponse);
        return self::$curl_response;
    }

    protected function setSignature($key, $value) {
        return hash_hmac('sha256', $value, $key, true);
    }

    private function getSignatureKey() {
        $secret_key = 'AWS4' . self::$secretAccessKey;
        $time_stamp = $this->setSignature($secret_key, self::$time_stamp);
        $region = $this->setSignature($time_stamp, self::$service_region);
        $service = $this->setSignature($region, self::$service_name);
        $signature = $this->setSignature($service, 'aws4_request');
        return $signature;
    }

    protected function buildQueryParams() {
        $params = array(
            'Action'            => self::$action_name,
            'ResponseGroup'     => self::$response_group,
            'CountryCode'       => self::$countryCode,
            'Count'             => self::$end_count,
            'Start'             => self::$start_count
        );
        ksort($params);
        $value = array();
        foreach($params as $key => $val) {
            $value[] = $key . '=' . rawurlencode($val);
        }
        return implode('&',$value);
    }

    protected function buildHeaders($list) {
        $params = array(
            'host'            => self::$endpoint_host,
            'x-amz-date'      => self::$time_zone
        );
        ksort($params);
        $value = array();
        foreach($params as $key => $val) {
            if ($list) {
                $value[] = $key . ':' . $val;
            }
            else {
                $value[] = $key;
            }
        }
        return ($list) ? implode("\n",$value) . "\n" : implode(';',$value);
    }
}