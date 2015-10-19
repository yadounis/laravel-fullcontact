<?php

namespace Yadounis\FullContact;

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
use Mockery\CountValidator\Exception;


/**
 * This class handles the actually HTTP request to the FullContact endpoint.
 *
 * @package  FullContact
 * @author   Younes Adounis <y.adounis@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache
 */
class FullContactService
{
    const USER_AGENT = 'yadounis/fullcontact-php-0.9.0';

    protected $_baseUri = 'https://api.fullcontact.com/';
    protected $_version = 'v2';

    protected $_apiKey = null;

    public $response_obj  = null;
    public $response_code = null;
    public $response_json = null;

    protected $_supportedMethods = array('email', 'phone', 'twitter', 'facebookUsername');
    protected $_resourceUri = '/person.json';

    /**
     * The base constructor needs the API key available from here:
     * http://fullcontact.com/getkey
     *
     * @param type $api_key
     */
    public function __construct($api_key)
    {
        $this->_apiKey = $api_key;
    }
    
    protected function _execute($params = array())
    {
        $params['apiKey'] = urlencode($this->_apiKey);

        $fullUrl = $this->_baseUri . $this->_version . $this->_resourceUri . '?' . http_build_query($params);

        //open connection
        $connection = curl_init($fullUrl);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($connection, CURLOPT_USERAGENT, self::USER_AGENT);

        //execute request
        $this->response_json = curl_exec($connection);
        $this->response_code = curl_getinfo($connection, CURLINFO_HTTP_CODE);
        $this->response_obj  = json_decode($this->response_json);

        if ('403' == $this->response_code) {
            throw new Exception($this->response_obj->message);
        }

        return $this->response_obj;
    }

    public function lookupByEmail($search)
    {
        $this->_execute(array('email' => $search, 'method' => 'email'));

        return $this->response_obj;
    }

    public function lookupByEmailMD5($search)
    {
        $this->_execute(array('emailMD5' => $search, 'method' => 'email'));

        return $this->response_obj;
    }

    public function lookupByPhone($search)
    {
        $this->_execute(array('phone' => $search, 'method' => 'phone'));

        return $this->response_obj;
    }

    public function lookupByTwitter($search)
    {
        $this->_execute(array('twitter' => $search, 'method' => 'twitter'));

        return $this->response_obj;
    }

    public function lookupByFacebook($search)
    {
        $this->_execute(array('facebookUsername' => $search, 'method' => 'facebookUsername'));

        return $this->response_obj;
    }
}