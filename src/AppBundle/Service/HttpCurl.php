<?php
namespace AppBundle\Service;

class HttpCurl
{
    /**
     * Url of the request.
     * @var string
     */
    public $url;

    /**
     * Request options.
     * @var array
     */
    public $options = [
        'headers' => ['User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36'],
        'method' => 'GET',
        'data' => NULL,
        'max_redirects' => 3,
        'timeout' => 30.0,
        'context' => NULL,
        'verify_ssl' => FALSE,
        'verbose' => FALSE,
        'cookiefile' => NULL,
        'http_proxy' => '',
        'https_proxy' => '',
        'curl_opts' => [],
    ];

    /**
     * @var mixed
     */
    protected $_uri;

    /**
     * cUrl resource.
     * @var mixed
     */
    protected $_ch;

    /**
     * Performs an HTTP request.
     *
     * This is a flexible and powerful HTTP client implementation. Correctly
     * handles GET, POST, PUT or any other HTTP requests. Handles redirects.
     *
     * @param $url
     *   A string containing a fully qualified URI.
     * @param array $options
     *   (optional) An array that can have one or more of the following elements:
     *   - headers: An array containing request headers to send as name/value pairs.
     *   - method: A string containing the request method. Defaults to 'GET'.
     *   - data: A string containing the request body, formatted as
     *     'param=value&param=value&...'. Defaults to NULL.
     *   - max_redirects: An integer representing how many times a redirect
     *     may be followed. Defaults to 3.
     *   - timeout: A float representing the maximum number of seconds the function
     *     call may take. The default is 30 seconds. If a timeout occurs, the error
     *     code is set to the HTTP_REQUEST_TIMEOUT constant.
     *   - context: A context resource created with stream_context_create().
     *   - verify_ssl: A boolean, to decide whether (TRUE) or not (FALSE) verify the SSL certificate and host.
     *   - verbose: A boolean, to switch on (TRUE) and off (FALSE) the cURL verbose mode.
     *   - cookiefile: A string containing a local path to the cookie file.
     *   - http_proxy: An array that will override the system-wide HTTP proxy settings. Array's elements:
     *   - https_proxy: An array that will override the system-wide HTTPS proxy settings.
     *   - curl_opts: An array of generic cURL options.
     */
    public function __construct($url, array $options = [])
    {
        // Merge the default options.
        $options += $this->options;


        // Set object attributes
        $this->setOptions($options);
        $this->setUrl($url);

        // Parse the URL and make sure we can handle the schema.
        $this->_uri = @parse_url($url);
    }

    /**
     * Set url for the request.
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set instance options.
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        // Merge options.
        if (!empty($options)) {
            foreach($options as $k => $v) {
                $this->options[$k] = (isset($this->options[$k]) && is_array($v)) ? array_merge((array)$this->options[$k], $v) : $v;
            }
        }
    }

    /**
     * Get instance options.
     * @return array
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * @return object
     *   An object that can have one or more of the following components:
     *   - request: A string containing the request body that was sent.
     *   - code: An integer containing the response status code, or the error code
     *     if an error occurred.
     *   - protocol: The response protocol (e.g. HTTP/1.1 or HTTP/1.0).
     *   - status_message: The status message from the response, if a response was
     *     received.
     *   - redirect_code: If redirected, an integer containing the initial response
     *     status code.
     *   - redirect_url: If redirected, a string containing the URL of the redirect
     *     target.
     *   - error: If an error occurred, the error message. Otherwise not set.
     *   - errno: If an error occurred, a cURL error number greater than 0. Otherwise set to 0.
     *   - headers: An array containing the response headers as name/value pairs.
     *     HTTP header names are case-insensitive (RFC 2616, section 4.2), so for
     *     easy access the array keys are returned in lower case.
     *   - data: A string containing the response body that was received.
     *   - curl_opts: An array of curl options used
     * @return stdClass
     */
    public function request()
    {
        // Initialize cURL object.
        $this->_ch = curl_init($this->getUrl());

        // Store the result.
        $result = new \stdClass();

        if ($this->_uri == FALSE) {
            $result->error = 'unable to parse URL';
            $result->code = -1001;
            return $result;
        }

        if (!isset($this->_uri['scheme'])) {
            $result->error = 'missing schema';
            $result->code = -1002;
            return $result;
        }

        timer_start('curl_' . __FUNCTION__);

        // Set the proxy settings.
        $this->_setProxySettings();

        if (empty($this->options['headers']['User-Agent'])) {
            $this->options['headers']['User-Agent'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36';
        }

        if (empty($this->options['header'])) {
            $this->options['header'] = [];
        }

        // Set default configuration.
        $this->_setDefaults();

        // Set cookie settings.
        $this->_setCookieSettings();

        // Set the port.
        $success = $this->_setPort();

        if (FALSE === $success) {
            $result->error = 'invalid schema ' . $this->_uri['scheme'];
            $result->code = -1003;
            return $result;
        }

        // Set request options.
        $success = $this->_requestTypeOption();

        if (FALSE === $success) {
            $result->error = 'invalid method ' . $this->options['method'];
            $result->code = -1004;
            return $result;
        }

        // If the server URL has a user then attempt to use basic authentication.
        if (isset($this->_uri['user'])) {
            $this->options['headers']['Authorization'] = 'Basic ' . base64_encode($this->_uri['user'] . (isset($this->_uri['pass']) ?
                        ':' . $this->_uri['pass'] : ''));
        }

        // Set headers.
        $this->_setHeaders();

        // Set any last minute options.
        $this->_setOptions();

        // Make request.
        $result->data = trim(curl_exec($this->_ch));

        // Check for errors.
        $result->errno = curl_errno($this->_ch);

        // Get Response Info.
        $info = curl_getinfo($this->_ch);

        // If there's been an error, do not continue.
        if ($result->errno != 0) {
            // Request timed out.
            if (CURLE_OPERATION_TIMEOUTED == $result->errno) {
                $result->code = HTTP_REQUEST_TIMEOUT;
                $result->error = 'request timed out';
                return $result;
            }
            $result->error = curl_error($this->_ch);
            $result->code = $result->errno;
            return $result;
        }

        // The last effective URL should correspond to the Redirect URL.
        $result->redirect_url = curl_getinfo($this->_ch, CURLINFO_EFFECTIVE_URL);

        // Save the request sent into the result object.
        $result->request = curl_getinfo($this->_ch, CURLINFO_HEADER_OUT);

        // Close the connection.
        curl_close($this->_ch);

        // For NTLM requests:
        // Since NTLM responses contain multiple header sections, we must parse them
        // differently than standard response data.
        if (isset($this->options['curl_opts'][CURLOPT_HTTPAUTH]) && $this->options['curl_opts'][CURLOPT_HTTPAUTH] & CURLAUTH_NTLM) {
            //  @todo don't need right now, do later
            //  $result->ntlm_response;
        }

        // Parse response headers from the response body.
        // Be tolerant of malformed HTTP responses that separate header and body with
        // \n\n or \r\r instead of \r\n\r\n.
        $matches = preg_split("/\r\n\r\n|\n\n|\r\r/", $result->data, 2);
        $response = isset($matches[0]) ? $matches[0] : NULL;
        // Sometimes there isn't payload e.g. when a HEAD request is sent.
        $result->data = isset($matches[1]) ? $matches[1] : NULL;

        // Sometimes when making an HTTP request via proxy using cURL, you end up with a multiple set of headers:
        // from the web server being the actual target, from the proxy itself, etc.
        // The following 'if' statement is to check for such a situation and make sure we get a proper split between
        // actual response body and actual response headers both coming from the web server.
        while ('HTTP/' == substr($result->data, 0, 5)) {
            $matches = preg_split("/\r\n\r\n|\n\n|\r\r/", $result->data, 2);
            $response = isset($matches[0]) ? $matches[0] : NULL;
            // Sometimes there isn't payload e.g. when a HEAD request is sent.
            $result->data = isset($matches[1]) ? $matches[1] : NULL;
        }

        $response = preg_split("/\r\n|\n|\r/", $response);

        // Parse the response status line.
        list($protocol, $code, $status_message) = explode(' ', trim(array_shift($response)), 3);
        $result->protocol = $protocol;
        $result->status_message = $status_message;

        $result->headers = [];

        // Parse the response headers.
        while ($line = trim(array_shift($response))) {
            list($name, $value) = explode(':', $line, 2);
            $name = strtolower($name);
            if (isset($result->headers[$name]) && $name == 'set-cookie') {
                // RFC 2109: the Set-Cookie response header comprises the token Set-
                // Cookie:, followed by a comma-separated list of one or more cookies.
                $result->headers[$name] .= ',' . trim($value);
            } else {
                $result->headers[$name] = trim($value);
            }
        }

        $responses = $this->requestResponseCodes();

        // RFC 2616 states that all unknown HTTP codes must be treated the same as the
        // base code in their class.
        if (!isset($responses[$code])) {
            $code = floor($code / 100) * 100;
        }
        $result->code = $code;

        switch ($code) {
            case 200: // OK
            case 304: // Not modified
                break;
            case 301: // Moved permanently
            case 302: // Moved temporarily
            case 307: // Moved temporarily
                $location = $result->headers['location'];
                $this->options['timeout'] -= timer_read('curl_' . __FUNCTION__) / 1000;
                if ($this->options['timeout'] <= 0) {
                    $result->code = HTTP_REQUEST_TIMEOUT;
                    $result->error = 'request timed out';
                } elseif ($this->options['max_redirects']) {
                    // Redirect to the new location.
                    $this->options['max_redirects']--;
                    $result = $this->request($location, $this->options);
                    $result->redirect_code = $code;
                }
                if (!isset($result->redirect_url)) {
                    $result->redirect_url = $location;
                }
                break;
            default:
                $result->error = $status_message;
        }

        // Lastly, include any cURL specific information.
        $result->curl_info = $info;

        return $result;
    }

    /**
     * Set curl proxy settings.
     */
    private function _setProxySettings()
    {
        // Select the right proxy for the right protocol.
        $proxy = ('https' == $this->_uri['scheme']) ? $this->options['https_proxy'] : $this->options['http_proxy'];

        // Nullify the proxy if the host to send the request to is part of the proxy's exceptions.
        if ((!empty($proxy['exceptions'])) && (in_array($this->_uri['host'], $proxy['exceptions']))) {
            $proxy = NULL;
        }

        if (!empty($proxy)) {
            curl_setopt($this->_ch, CURLOPT_PROXY, $proxy['server']);
            curl_setopt($this->_ch, CURLOPT_PROXYPORT, $proxy['port']);
            // For the time being let's just support HTTP proxies with basic authentication.
            if (isset($proxy['username']) && isset($proxy['password'])) {
                curl_setopt($this->_ch, CURLOPT_PROXYUSERPWD, implode(':', [$proxy['username'], $proxy['password']]));
                curl_setopt($this->_ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                curl_setopt($this->_ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            }
        }
    }

    /**
     * Set default cURL settings.
     */
    private function _setDefaults()
    {
        curl_setopt($this->_ch, CURLOPT_HEADER, TRUE);
        curl_setopt($this->_ch, CURLOPT_USERAGENT, $this->options['headers']['User-Agent']);
        curl_setopt($this->_ch, CURLINFO_HEADER_OUT, TRUE);
        curl_setopt($this->_ch, CURLOPT_URL, $this->url);
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, $this->options['timeout']);
        curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, $this->options['verify_ssl']);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYHOST, $this->options['verify_ssl']);
        curl_setopt($this->_ch, CURLOPT_MAXREDIRS, $this->options['max_redirects']);

        // Remove the user agent from the headers as it is already set
        unset($this->options['headers']['User-Agent']);
    }

    /**
     * Set default cookie settings.
     */
    private function _setCookieSettings()
    {
        // Set cookie settings
        if ($this->options['cookiefile']) {
            curl_setopt($this->_ch, CURLOPT_COOKIEJAR, $this->options['cookiefile']);
            curl_setopt($this->_ch, CURLOPT_COOKIEFILE, $this->options['cookiefile']);
        }
    }

    /**
     * Set port options.
     *
     * @return boolean
     *  Returns TRUE if they were set properly, FALSE otherwise.
     */
    private function _setPort()
    {
        $default_ports = [
            'http' => 80,
            'feed' => 80,
            'https' => 443,
        ];

        if (array_key_exists($this->_uri['scheme'], $default_ports)) {
            if (!isset($this->_uri['port'])) {
                $this->_uri['port'] = $default_ports[$this->_uri['scheme']];
            }
            // RFC 2616: "non-standard ports MUST, default ports MAY be included".
            // We don't add the standard port to prevent from breaking rewrite rules
            // checking the host that do not take into account the port number.
            $this->options['headers']['Host'] = $this->_uri['host'] . ($this->_uri['port'] != 80 ? ':' . $this->_uri['port'] : '');

            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Convert the string name of the request type to a cURL opt value.
     *
     * @return boolean
     *  Returns FALSE on error.
     */
    private function _requestTypeOption()
    {
        $valid_method = FALSE;
        switch (strtoupper($this->options['method'])) {
            case 'DELETE':
                curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                $valid_method = TRUE;
                break;
            case 'OPTIONS':
                curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
                $valid_method = TRUE;
                break;
            case 'TRACE':
                curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'TRACE');
                $valid_method = TRUE;
                break;
            case 'CONNECT':
                // @todo
                break;
            case 'PATCH':
                // @todo
                break;
            case 'POST':
                // Assign the data to the proper cURL option
                curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $this->options['data']);

                if (isset($this->options['multipart']) && TRUE === $this->options['multipart']) {
                    // Do nothing for now
                } else {
                    curl_setopt($this->_ch, CURLOPT_POST, TRUE);
                }
                $valid_method = TRUE;
                break;
            case 'PUT':
                // Assign the data to the proper cURL option
                curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $this->options['data']);
                curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($this->_ch, CURLOPT_HTTPHEADER, ['X-HTTP-Method-Override: PUT']);
                $valid_method = TRUE;
                break;
            case 'GET':
                curl_setopt($this->_ch, CURLOPT_HTTPGET, TRUE);
                $valid_method = TRUE;
                break;
            case 'HEAD':
                curl_setopt($this->_ch, CURLOPT_NOBODY, TRUE);
                $valid_method = TRUE;
                break;
            default:
                return $valid_method;
        }
        return $valid_method;
    }

    /**
     * Set default headers in curl object.
     */
    private function _setHeaders()
    {
        if (is_array($this->options['headers']) and !empty($this->options['headers'])) {
            $headers = [];
            foreach ($this->options['headers'] as $key => $value) {
                $headers[] = trim($key) . ": " . trim($value);
            }
            curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $headers);
        }
    }

    /**
     * Set cURL options.
     */
    private function _setOptions()
    {
        // Set any extra cURL options
        foreach ($this->options['curl_opts'] as $opt => $value) {
            curl_setopt($this->_ch, $opt, $value);
        }
    }

    /**
     * List of response codes.
     *
     * @return array
     *  Returns an array of response codes.
     */
    protected function requestResponseCodes()
    {
        return [
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            416 => 'Requested range not satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
            505 => 'HTTP Version not supported',
        ];
    }
}