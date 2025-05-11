<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class XenditHelp
{
    public static function doCurl($endpoint, $POSTDATA, $ver2 = true, $header = array(), $method = "POST")
    {
        try {
            $ch  = curl_init();

            if ($ver2 == true) {
                $url = env('XENDIT_API_URL') . '/' . $endpoint;
            } else {
                $url = env('XENDIT_API_URL') . '/' . $endpoint;
            }
            $username = env('XENDIT_KEY');
            $password = "";

            $data = $POSTDATA;
            if (is_array($POSTDATA)) {
                $data = http_build_query($POSTDATA);
            }
            Log::info("\tCURL REQUEST \tHeader:: " . json_encode($header) . "\t Method:: " . $method . "\t\t" . $data . "\t\tURL::" . $url);

            if ($method == "GET") $url .= "?" . $data;

            curl_setopt($ch, CURLOPT_URL,               $url);
            curl_setopt($ch, CURLOPT_HEADER,            TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER,        $header);
            curl_setopt($ch, CURLINFO_HEADER_OUT,       true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,    TRUE);
            if ($method == "POST") {
                curl_setopt($ch, CURLOPT_POST,          TRUE);
            }
            if (!in_array($method, ["GET", "POST"])) {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }
            if ($method != "GET") {
                curl_setopt($ch, CURLOPT_POSTFIELDS,    $data);
            }
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,    2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,    false);
            curl_setopt($ch, CURLOPT_USERAGENT,         "Buy Ticket event");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,    30);
            curl_setopt($ch, CURLOPT_TIMEOUT,           30);
            curl_setopt($ch, CURLOPT_VERBOSE,           true);

            $rawResponse    = curl_exec($ch);
            $header_size    = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $httpcode       = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header         = substr($rawResponse, 0, $header_size);
            $heads          = explode("\n", $header);
            $head           = FALSE;
            foreach ($heads as $v) {
                $v = trim($v);
                if (strlen($v) < 1) continue;
                if (preg_match("/^(HTTP\/\d\.\d) (\d{3}) (.*)/i", $v, $m)) {
                    $head["http"]           = $m[1];
                    $head["status_code"]    = $m[2];
                    $head["status_string"]  = $m[2] . " - " . $m[3];
                } elseif (preg_match("/^([\w\-\.]+):\s*?(\S.*)$/", $v, $m)) {
                    $head[$m[1]] = $m[2];
                } else continue;
            }
            $response["cURLerror"] = curl_error($ch);
            curl_close($ch);
            $response["header"] = $head;

            $body   = substr($rawResponse, $header_size);
            $body   = trim($body);
            if (!!($jsonRes = json_decode($body, true)))
                $response["body"] = json_decode($body, true);
            else
                $response["body"] = $body;
        } catch (Exception $e) {
            $response["error"] = $e;
        }
        Log::info("\tCURL RESPONSE\t\t\t" . json_encode($response));
        return $response["body"];
    }

    public static function doCurlQris($url, $POSTDATA, $method = "POST")
    {
        try {

            $header = [
                'api-version: ' . '2022-07-31',
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode(env('XENDIT_KEY') . ':')
            ];

            $ch  = curl_init();

            $data = $POSTDATA;
            if (is_array($POSTDATA)) {
                $data = http_build_query($POSTDATA);
            }
            Log::info("SESSIONID" . "\tCURL REQUEST \tHeader:: " . json_encode($header) . "\t Method:: " . $method . "\t\t" . $data . "\t\tURL::" . $url);

            if ($method == "GET") $url .= "?" . $data;

            curl_setopt($ch, CURLOPT_URL,               $url);
            curl_setopt($ch, CURLOPT_HEADER,            TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER,        $header);
            curl_setopt($ch, CURLINFO_HEADER_OUT,       true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,    TRUE);
            if ($method == "POST") {
                curl_setopt($ch, CURLOPT_POST,          TRUE);
            }
            if (!in_array($method, ["GET", "POST"])) {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }
            if ($method != "GET") {
                curl_setopt($ch, CURLOPT_POSTFIELDS,    $data);
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,    2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,    false);
            curl_setopt($ch, CURLOPT_USERAGENT,         "Pockets/2.0");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,    30);
            curl_setopt($ch, CURLOPT_TIMEOUT,           30);
            curl_setopt($ch, CURLOPT_VERBOSE,           true);

            $rawResponse    = curl_exec($ch);
            $header_size    = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $httpcode       = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header         = substr($rawResponse, 0, $header_size);
            $heads          = explode("\n", $header);
            $head           = FALSE;
            foreach ($heads as $v) {
                $v = trim($v);
                if (strlen($v) < 1) continue;
                if (preg_match("/^(HTTP\/\d\.\d) (\d{3}) (.*)/i", $v, $m)) {
                    $head["http"]           = $m[1];
                    $head["status_code"]    = $m[2];
                    $head["status_string"]  = $m[2] . " - " . $m[3];
                } elseif (preg_match("/^([\w\-\.]+):\s*?(\S.*)$/", $v, $m)) {
                    $head[$m[1]] = $m[2];
                } else continue;
            }
            $response["cURLerror"] = curl_error($ch);
            curl_close($ch);
            $response["header"] = $head;

            $body   = substr($rawResponse, $header_size);
            $body   = trim($body);
            if (!!($jsonRes = json_decode($body, true)))
                $response["body"] = json_decode($body, true);
            else
                $response["body"] = $body;
        } catch (Exception $e) {
            $response["error"] = $e;
        }
        Log::info('SESSIONID' . "\tCURL RESPONSE\t\t\t" . json_encode($response));

        if (isset($response['body']) && is_array($response['body'])) {
            if (isset($response['body']['status']) && $response['body']['status'] === 'ACTIVE') {
                return $response['body'];
            } else {
                return [
                    'status' => false,
                    'message' => 'Something Went Wrong',
                    'details' => isset($response['body']['errors']) ? $response['body']['errors'] : null,
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => 'Something Went Wrong',
                'details' => isset($response['error']) ? $response['error'] : null,
            ];
        }
    }
}
