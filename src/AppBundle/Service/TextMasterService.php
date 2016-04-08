<?php
namespace AppBundle\Service;

use AppBundle\Exception\JsonNotValidException;

class TextMasterService
{
    const API_KEY = "uZAE1fqIzyA";
    const API_SECRET = "m5MOLO-8o0s";
    const API_BASE_URL = "http://api.textmaster.com/"; //"http://api.staging.textmaster.com/";
    const API_VERSION = "v1";

    private function request($path = '', $method = 'GET', $params = [])
    {
        $body = $this->_baseRequest($path, $method, $params);

        if (empty($body)) {
            throw new \HttpException('Server Error.');
        }

        return $this->handlerDataResponse($body);
    }

    private function _baseRequest($path = "", $method = "", $params)
    {
        list($path, $method) = $this->_switchToDefaultIfBlank($path, $method);

        $curl = new HttpCurl(self::API_BASE_URL.$path, [
            'method' => strtoupper($method),
            'headers' => $this->_getAuthHeader(),
            'data' => $params
        ]);
        return $curl->request();

//        return file_get_contents(
//            self::API_BASE_URL . $path,
//            false,
//            $this->_createContext($method, $content));
    }

    private function _switchToDefaultIfBlank($path, $method)
    {
        $base_request_default = array("path" => "test", "method" => "GET", "content" => array(), "api_section" => self::API_VERSION . "/clients/");
        $path = ($path == "") ? $base_request_default["path"] : $base_request_default["api_section"] . $path;
        $method = ($method == "") ? $base_request_default["method"] : $method;

        return [$path, $method];
    }

    private function _getAuthHeader()
    {
        $date = gmdate('Y-m-d H:i:s');
        $signature = sha1(self::API_SECRET . $date);
        return [
            "APIKEY " => self::API_KEY,
            "DATE " => $date,
            "SIGNATURE" => $signature,
            "Content-Type" => "application/json"
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

    public function createProject($params)
    {
        return $this->request('projects', 'POST', $params);
    }

    public function updateProject()
    {
        
    }

    private function handlerDataResponse($data)
    {
        $result = json_decode($data->data, TRUE);

        if (json_last_error() == JSON_ERROR_NONE) {
            if (200 == $data->code) {
                return $result;
            }

            $result['code'] = $data->code;
            $result['message'] = $data->status_message;

            return $result;
        }

        throw new JsonNotValidException;
    }

}