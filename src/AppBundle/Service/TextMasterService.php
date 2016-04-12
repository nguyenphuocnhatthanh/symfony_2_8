<?php
namespace AppBundle\Service;

use AppBundle\Exception\JsonNotValidException;

/**
 * Class TextMasterService
 * @package AppBundle\Service
 */
class TextMasterService
{
    const API_KEY = "uZAE1fqIzyA";

    const API_SECRET = "m5MOLO-8o0s";

    const API_BASE_URL = "http://api.textmaster.com/"; //"http://api.staging.textmaster.com/";

    const API_VERSION = "v1";

    /**
     * @param $params
     * @return array
     * @throws \HttpException
     */
    public function createProject($params)
    {
        return $this->request('projects', 'POST', $params);
    }

    /**
     * @param $id
     * @param $params
     * @return array
     */
    public function updateProject($id, $params)
    {
        return $this->request(
            sprintf('projects/%s', $id),
            'PUT',
            $params
        );
    }

    /**
     * @param $id
     * @return array
     */
    public function launchProject($id)
    {
        return $this->request(
            sprintf('projects/%s/launch', $id),
            'PUT'
        );
    }

    /**
     * Get all project
     */
    public function getProjects()
    {
        return $this->request('projects', 'GET');
    }

    /**
     * @param $params
     * @return array
     */
    public function filterProject($params)
    {
        return $this->request('projects/filter'.$params, 'GET');
    }

    /**
     * @param $id
     * @return array
     */
    public function consultProject($id)
    {
        return $this->request(
            sprintf('projects/%s', $id),
            'GET'
        );
    }

    /**
     * @param $id
     * @return array
     */
    public function duplicateProject($id)
    {
        return $this->request(
            sprintf('projects/%s/duplicate', $id),
            'POST'
        );
    }

    /**
     * @param $projectId
     * @param $params
     * @return array
     * @throws \HttpException
     */
    public function createDocument($projectId, $params)
    {
        return $this->request(
            sprintf('projects/%s/documents', $projectId),
            'POST',
            $params
        );
    }

    /**
     * @param $projectId
     * @return array
     * @throws \HttpException
     */
    public function getDocuments($projectId)
    {
        return $this->request(
            sprintf('projects/%s/documents', $projectId),
            'GET'
        );
    }

    /**
     * @param $projectId
     * @param $id
     * @return array
     * @throws \HttpException
     */
    public function getDocument($projectId, $id)
    {
        return $this->request(
            sprintf('projects/%s/documents/%s', [$projectId, $id]),
            'GET'
        );
    }

    /**
     * @param $projectId
     * @param $id
     * @return array
     */
    public function updateDocument($projectId, $id)
    {
        return $this->request(
            sprintf('projects/%s/document/%s', [$projectId, $id]),
            'PUT'
        );
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $params
     * @return array
     * @throws JsonNotValidException
     * @throws \HttpException
     */
    private function request($path = '', $method = 'GET', $params = [])
    {
        $body = $this->_baseRequest($path, $method, $params);

        if (empty($body)) {
            throw new \HttpException('Server Error.');
        }

        return $this->handlerDataResponse($body);
    }

    /**
     * @param string $path
     * @param string $method
     * @param $params
     * @return object
     */
    private function _baseRequest($path = "", $method = "", $params)
    {
        list($path, $method) = $this->_switchToDefaultIfBlank($path, $method);

        $curl = new HttpCurl(self::API_BASE_URL.$path, [
            'method' => strtoupper($method),
            'headers' => 'GET' !== $method ?  array_merge(['Content-Type' => 'application/json'], $this->_getAuthHeader()) : $this->_getAuthHeader(),
            'data' => $params
        ]);

        return $curl->request();
//        return file_get_contents(
//            self::API_BASE_URL . $path,
//            false,
//            $this->_createContext($method, $content));
    }

    /**
     * @param $path
     * @param $method
     * @return array
     */
    private function _switchToDefaultIfBlank($path, $method)
    {
        $base_request_default = array("path" => "test", "method" => "GET", "content" => array(), "api_section" => self::API_VERSION . "/clients/");
        $path = ($path == "") ? $base_request_default["path"] : $base_request_default["api_section"] . $path;
        $method = ($method == "") ? $base_request_default["method"] : $method;

        return [$path, $method];
    }

    /**
     * @return array
     */
    private function _getAuthHeader()
    {
        $date = gmdate('Y-m-d H:i:s');
        $signature = sha1(self::API_SECRET . $date);

        return [
            "APIKEY " => self::API_KEY,
            "DATE " => $date,
            "SIGNATURE" => $signature,
//            "Content-Type" => "application/json"
        ];
    }

    /**
     * @param $method
     * @param $content
     * @return resource
     */
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

    /**
     * @param $data
     * @return array|mixed
     * @throws JsonNotValidException
     */
    private function handlerDataResponse($data)
    {
//        die(dump($data));
        $result = json_decode($data->data, TRUE);

        if (json_last_error() == JSON_ERROR_NONE) {
            if (200 == $data->code) {
                return $result;
            }

            $response = [
                'code' => (int) $data->code,
                'message' => $data->status_message.'.',
                'fields' => null
            ];


            if (isset($result['status'])) {
                unset($result['status']);
            }

            if (isset($result['error'])) {
                unset($result['error']);
            }

            if (isset($result['errors'])) {
                foreach ($result['errors'] as $field => $error) {
                    $response['message'] .= !is_array($error[0]) ?  $field .': '.$error[0]. '. ' : $field .': '.$error[0][0]. '. ';
                }
            }

            return $response;
        }

        throw new JsonNotValidException;
    }
}