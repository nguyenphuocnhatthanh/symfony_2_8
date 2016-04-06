<?php
namespace AppBundle\Service;

class TextMasterApi
{
    const API_KEY = "uZAE1fqIzyA";
    const API_SECRET = "m5MOLO-8o0s";
    const API_BASE_URL = "http://api.textmaster.com/"; //"http://api.staging.textmaster.com/";
    const API_VERSION = "beta";

    public function request($path = '', $method = 'GET', $content= '')
    {
        $body = $this->_baseRequest($path, $method, $content);

        print $body->data;
    }

    private function _baseRequest($path = "", $method = "", $content = "")
    {
        list($path, $method, $content) = $this->_switchToDefaultIfBlank($path, $method, $content);

        $curl = new HttpCurl(self::API_BASE_URL.$path, [
            'method' => strtoupper($method),
            'headers' => $this->_getAuthHeader(),
            'content' => $content
        ]);
        return $curl->request();

//        return file_get_contents(
//            self::API_BASE_URL . $path,
//            false,
//            $this->_createContext($method, $content));
    }

    private function _switchToDefaultIfBlank($path, $method, $content)
    {
        $base_request_default = array("path" => "test", "method" => "GET", "content" => array(), "api_section" => self::API_VERSION . "/clients/");
        $path = ($path == "") ? $base_request_default["path"] : $base_request_default["api_section"] . $path;
        $method = ($method == "") ? $base_request_default["method"] : $method;
        $content = ($content == "") ? $base_request_default["content"] : $content;

        return [$path, $method, $content];
    }

    private function _getAuthHeader()
    {
        $date = gmdate('Y-m-d H:i:s');
        $signature = sha1(self::API_SECRET . $date);
        return [
            "APIKEY: " . self::API_KEY . "\r\n" . "DATE: " . $date . "\r\n" .
            "SIGNATURE: " . $signature . "\r\n" . "Content-Type: application/json"
        ];
    }

    private function _createContext($method, $content)
    {
        $options = array(
            "http" => array(
                "method" => strtoupper($method),
                "header" => $this->_getAuthHeader(),
                "content" => $content
            )
        );
        return stream_context_create($options);
    }
}