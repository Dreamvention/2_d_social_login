<?php 
//Shopunity API
//require('Httpful/Bootstrap.php');

namespace d_shopunity;

use \Httpful\Bootstrap;
use \Httpful\Request;


class Api {

    private $api = 'https://api.shopunity.net/v1/';
    private $api_local = 'http://localhost:8888/shopunity_api/';
    private $client_id = 'd_shopunity'; //client_id of your app. Provided by shopunity.net
    private $library = 'Httpful';
    private $access_token = '';
    private $store_id = '';
    private $account = false;
    private $config = '';
    private $load = '';

    public function __construct($registry, $access_token = '', $store_id = ''){
        Bootstrap::init();
        $this->config = $registry->get('config');
        $this->load = $registry->get('load');
        $d_shopunity_oauth = $this->config->get('d_shopunity_oauth');
        $d_shopunity_store_info = $this->config->get('d_shopunity_store_info');

        if($access_token){
            $this->access_token = $access_token;
        }else{
            
            if(isset($d_shopunity_oauth['access_token'])){
                $this->access_token = $d_shopunity_oauth['access_token'];
            }
        }

        if(isset($d_shopunity_store_info['store_id']))
        {
            $this->store_id = $d_shopunity_store_info['store_id'];
        }
        elseif($this->access_token)
        {
            $this->store_id = $this->getStoreId();
        }

        // if(strpos($_SERVER['HTTP_HOST'], 'localhost') !== false){
        //  $this->api = $this->api_local;
        // }
        
    }

    public function getClientId(){
        return $this->client_id;
    }

    public function getStoreId(){
        $store = $this->getCurrentStore();
        return $store['store_id'];
    }

    public function getCurrentStore(){

        if($this->config->get('d_shopunity_store_info')){
            return $this->config->get('d_shopunity_store_info');
        }else{

            if($this->access_token){
                
                $result = file_get_contents($this->api."stores?access_token=".$this->access_token.'&url='.urlencode((defined('HTTP_CATALOG')) ? HTTP_CATALOG : HTTP_SERVER));

                $json = json_decode($result,true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    if($json){
                        return $json[0];
                    }else{
                        return false;
                    }
                }
            }
        }
        return false;
    }

    public function getAuthorizeUrl($redirect_uri){
        return $this->api .'oauth/authorize?response_type=code&client_id='. $this->client_id .'&state=xyz&redirect_uri='. urlencode($redirect_uri);
    }

    public function set($key, $value){
        $this->$key = $value;
    }

    public function get($uri, $data = array()){
        return $this->_request('get', $uri, $data, $this->library);
    }

    public function post($uri, $data = array()){
        return $this->_request('post', $uri, $data, $this->library);
    }

    public function put($uri, $data = array()){
        return $this->_request('put', $uri, $data, $this->library);
    }

    public function delete($uri, $data = array()){
        return $this->_request('delete', $uri, $data, $this->library);
    }

    

    public function _request($method, $uri,  $data = array(), $library = 'Httpful'){
        $library = '_request'.$library;

        $uri = $this->api . $uri;

        if($this->access_token){
            if(strpos($uri, '?') !== false){
                $divider = '&';
            }else{
                $divider = '?';
            }
            $uri .= $divider . 'access_token=' . $this->access_token;
        }

        if($this->store_id){
            if(strpos($uri, '?') !== false){
                $divider = '&';
            }else{
                $divider = '?';
            }
            $uri .= $divider . 'store_id=' . $this->store_id;
        }

        

        return $this->$library($method, $uri,  $data);
    }

    public function _requestHttpful($method, $uri, $data = array()){
        if($method == 'get'){
            if(strpos($uri, '?') !== false){
                $divider = '&';
            }else{
                $divider = '?';
            }
            $uri .= $divider . http_build_query($data);
        }
        
        $response = Request::$method($uri)
            ->sendsJson()
            ->body(json_encode($data))
            ->expectsJson()
            ->send();
        $result = json_decode(json_encode($response->body), true);

        //refer to FirePHP library on shopunity.net for debugging
        // if (class_exists('FB')) {
        //  FB::log('REQUEST '.$uri);
        //  FB::log($result);
        // }

        return $result;
    }

    public function _requestCurl($method, $uri, $data = array()){

        $curl = curl_init();

        if($method === 'get'){
            if ($data){
                if(strpos($uri, '?') !== false){
                    $divider = '&';
                }else{
                    $divider = '?';
                }

                $uri .= $divider . http_build_query($data);
            }

            $result = file_get_contents($uri);
        }else{
            switch ($method)
            {
                case "post":
                    curl_setopt($curl, CURLOPT_POST, 1);
                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case "put":
                    curl_setopt($curl, CURLOPT_PUT, 1);
                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case "delete":
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                    if ($data){
                        if(strpos($uri, '?') !== false){
                            $divider = '&';
                        }else{
                            $divider = '?';
                        }

                        $uri .= $divider . http_build_query($data);
                     }
                    break;
                default:
                    if ($data){
                        if(strpos($uri, '?') !== false){
                            $divider = '&';
                        }else{
                            $divider = '?';
                        }

                        $uri .= $divider . http_build_query($data);
                     }
            }

            curl_setopt($curl, CURLOPT_URL, $uri);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($curl);

            curl_close($curl);
        }

        $json = json_decode($result,true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }else{
            return false;
        }
    }
}