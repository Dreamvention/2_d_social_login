<?php
/*
 *  location: catalog/controller/extension/module/d_social_login.php
 */

class ControllerExtensionModuleDSocialLogin extends Controller
{

    private $route = 'extension/module/d_social_login';
    private $id = 'd_social_login';
    private $setting = array();
    private $sl_redirect = '';
    private $last_route = '';

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->setting = $this->config->get($this->id . '_setting');
        $this->language->load($this->route);
        $this->load->model($this->route);
        $this->load->model('extension/module/d_social_login');
        $this->load->model('extension/d_opencart_patch/load');

    }

    public function index()
    {
        $this->setup();
        $setting = $this->config->get($this->id . '_setting');
        if (isset($this->session->data['provider'])) {
            $customer_data = (isset($this->request->post['customer_data'])) ? $this->request->post['customer_data'] : '';
            $authentication_data = (isset($this->request->post['customer_data'])) ? $this->request->post['customer_data'] : '';
            return $this->form($customer_data, $authentication_data);
        }
        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_sign_in'] = $this->language->get('button_sign_in');
        $data['size'] = $setting['size'];
        $data['sizes'] = $setting['sizes'];
        $data['islogged'] = ($this->customer->isLogged()) ? $this->customer->isLogged() : false;
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/styles.css');
        $this->document->addScript('catalog/view/javascript/d_social_login/spin.min.js');


        $providers = $setting['providers'];
        $sort_order = array();
        foreach ($providers as $key => $value) {
            if (isset($value['sort_order'])) {
                $sort_order[$key] = $value['sort_order'];
            }
        }
        array_multisort($sort_order, SORT_ASC, $providers);
        $data['providers'] = $providers;
        foreach ($providers as $key => $val) {
            $data['providers'][$key]['heading'] = $this->language->get('text_sign_in_with_' . $val['id']);
        }
        $data['error'] = false;
        if (isset($this->session->data['d_social_login_error'])) {
            $data['error'] = $this->session->data['d_social_login_error'];
            unset($this->session->data['d_social_login_error']);
        }

        if (!isset($this->session->data['sl_redirect'])) {
            $this->session->data['sl_redirect'] = ($setting['return_page_url']) ? $setting['return_page_url'] : $this->getCurrentUrl();
        }

        // facebook fix
        unset($this->session->data['HA::CONFIG']);
        unset($this->session->data['HA::STORE']);
        if (VERSION >= '2.2.0.0') {
            return $this->model_extension_d_opencart_patch_load->view($this->route, $data);
        } else {
            if ($this->config->get('config_template')) {
                return $this->model_extension_d_opencart_patch_load->view($this->config->get('config_template') . '/template/' . $this->route, $data);
            } else {
                return $this->model_extension_d_opencart_patch_load->view('default/template/' . $this->route, $data);
            }
        }
    }

    public function login()
    {
        // multistore fix
        $this->setup();
        $this->load->model('setting/store');
        $stores = $this->model_setting_store->getStores();
        $store_id = $this->config->get('config_store_id');
        foreach ($stores as $store) {
            if ($store_id == $store['store_id']) {
                $httpServer = $store['url'];
                $httpsServer = $store['ssl'];
                break;
            }
        }
        if (empty($httpServer) || empty($httpsServer)) {
            $httpsServer = HTTPS_SERVER;
            $httpServer = HTTP_SERVER;
        }

        $this->setting['base_url'] = $this->config->get('config_secure') ? $httpServer . 'index.php?route=extension/d_social_login/callback' : $httpServer . 'index.php?route=extension/d_social_login/callback';
        $this->setting['debug_file'] = DIR_LOGS . $this->setting['debug_file'];

        if (isset($this->request->get['provider'])) {
            $this->session->data['provider'] = $this->setting['provider'] = $this->request->get['provider'];
        } else {
            // Save error to the System Log
            $this->log->write('Missing application provider.');
            // Set Message
            $this->session->data['error'] = sprintf("An error occurred, please <a href=\"%s\">notify</a> the administrator.", $this->url->link('information/contact'));
            // Redirect to the Login Page
            $this->response->redirect($this->sl_redirect);
        }
        // facebook fix
        if ($this->setting['provider'] == 'Facebook') {
            $this->setting['base_url'] = $httpServer . 'index.php?route=extension/d_social_login/callback';
        }
        if ($this->setting['provider'] == 'Live') {
            $this->setting['base_url'] = $this->config->get('config_secure') ? $httpsServer . 'd_social_login_live.php' : $httpServer . 'index.php?route=extension/d_social_login/callback_live';
        }

        try {
            $res = $this->model_extension_module_d_social_login->remoteLogin($this->setting, $this->sl_redirect);// result from hybrid
            if ($res == 'redirect') {
                $this->response->redirect($this->sl_redirect);
            }
            $this->document->addScript('catalog/view/javascript/jquery/jquery-2.1.1.min.js');
            $res['scripts'] = $this->document->getScripts();
            $res['url'] = $this->getCurrentUrl(false);
            $view = $this->model_extension_d_opencart_patch_load->view($this->id . '/auth', $res);
            if (VERSION >= '2.2.0.0') {
                $this->response->setOutput($view);
            } else {
                if ($this->config->get('config_template')) {
                    $this->response->setOutput($this->model_extension_d_opencart_patch_load->view($this->config->get('config_template') . '/template/' . $this->route, $res));
                } else {
                    $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('default/template/' . $this->route, $res));
                }
            }
        } catch (Exception $e) {
            unset($this->session->data['provider']);
            switch ($e->getCode()) {
                case 0 :
                    $error = $this->language->get('unspecified_error');//"Unspecified error";
                    break;
                case 1 :
                    $error = $this->language->get('hybriauth_error');//"Hybriauth configuration error.";
                    break;
                case 2 :
                    $error = $this->language->get('provider_not_configured_error');//"Provider not properly configured.";
                    break;
                case 3 :
                    $error = $this->language->get('disabled_provider_error');//"Unknown or disabled provider.";
                    break;
                case 4 :
                    $error = $this->language->get('missing_provider_error');// "Missing provider application credentials.";
                    break;
                case 5 :
                    $error = $this->language->get('auth_fail_error');//"Authentication failed. The user has canceled the authentication or the provider refused the connection.";
                    break;
                case 6 :
                    $error = $this->language->get('profile_request_error');// "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.";
                    if (isset($adapter)) {
                        $adapter->logout();
                    }
                    break;
                case 7 :
                    $error = $this->language->get('user_no_provider_error');// "User not connected to the provider.";
                    break;
                case 8 :
                    $error = $this->language->get('no_feature_provider_error');//"Provider does not support this feature.";
                    break;
            }

            if (isset($adapter)) {
                $adapter->logout();
            }
            $this->session->data['d_social_login_error'] = $error;
            $error .= "\n\nHybridAuth Error: " . $e->getMessage();
            $error .= "\n\nTrace:\n " . $e->getTraceAsString();
            $this->log->write($error);
            $this->response->redirect($this->sl_redirect);
        }
    }

    private function form($customer_data, $authentication_data)
    {
        $data['pre_loader'] = html_entity_decode($this->model_extension_module_d_social_login->getPreloader('clip-rotate'), ENT_QUOTES, 'UTF-8');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/pre_loader/' . 'clip-rotate' . '.css');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/form.css');
        $this->document->addScript('catalog/view/javascript/d_social_login/jquery.validate.js');
        $this->document->addScript('catalog/view/javascript/d_social_login/jquery.maskedinput.min.js');
        $data['debug'] = $this->setting['debug_mode'] = (bool)$this->setting['debug_mode'];
        $this->session->data['customer_data'] = $customer_data;
        $this->session->data['authentication_data'] = $authentication_data;
        $data['customer_data'] = $customer_data;
        $data['authentication_data'] = $authentication_data;
        $data['button_sign_in_mail'] = $this->language->get('button_sign_in_mail');
        $data['button_sign_in'] = $this->language->get('button_sign_in');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_firstname'] = $this->language->get('text_firstname');
        $data['text_lastname'] = $this->language->get('text_lastname');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_address_1'] = $this->language->get('text_address_1');
        $data['text_address_2'] = $this->language->get('text_address_1');
        $data['text_city'] = $this->language->get('text_city');
        $data['text_postcode'] = $this->language->get('text_postcode');
        $data['text_country_id'] = $this->language->get('text_country_id');
        $data['text_zone_id'] = $this->language->get('text_zone_id');
        $data['text_company'] = $this->language->get('text_company');
        $data['text_wait_provider'] = $this->language->get('text_wait_provider');
        // $data['text_company_id'] = $this->language->get('text_company_id');
        // $data['text_tax_id'] = $this->language->get('text_tax_id');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_email_intro'] = $this->language->get('text_email_intro');

//        $data['background_img'] = $this->setting['background_img'];
//        $data['background_color'] = $this->setting['providers'][ucfirst($this->setting['provider'])]['background_color'];

        $sort_order = array();
        foreach ($this->setting['fields'] as $key => $value) {
            if (isset($value['sort_order'])) {
                $sort_order[$key] = $value['sort_order'];
            }
        }
        array_multisort($sort_order, SORT_ASC, $this->setting['fields']);
        $data['fields'] = $this->setting['fields'];

        $this->load->model('localisation/country');
        $data['countries'] = $this->model_localisation_country->getCountries();

        if (VERSION >= '2.2.0.0') {
            return $this->load->view('d_social_login/form', $data);
        } elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_social_login/form.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/d_social_login/form.tpl', $data);
        } else {
            return $this->load->view('default/template/d_social_login/form.tpl', $data);
        }
    }

    public function register()
    {

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            return false;
        }

        $json = array();
        $customer_data = array_merge($this->session->data['customer_data'], $this->request->post);
        $authentication_data = $this->session->data['authentication_data'];

        // check email
        if ($this->validate_email($customer_data['email'])) {
            $customer_id = $this->model_extension_module_d_social_login->getCustomerByEmail($customer_data['email']);
            if ($customer_id) {
                if (!$this->model_extension_module_d_social_login->checkAuthentication($customer_id, $this->request->post['provider'])) {
                    $authentication_data['customer_id'] = (int)$customer_id;
                    $this->model_extension_module_d_social_login->addAuthentication($authentication_data);
                } else {
                    $json['error']['email'] = $this->language->get('error_email_taken');
                }
            }
        } else {
            $json['error']['email'] = $this->language->get('error_email_incorrect');
        }

        // fields
        foreach ($this->setting['fields'] as $field) {
            if ($field['enabled']) {
                if ($field['id'] == 'confirm') {
                    if (($customer_data['password'] != $customer_data['confirm'])) {
                        $json['error']['confirm'] = $this->language->get('error_password_and_confirm_different');
                    }
                }
                if ($this->request->post[$field['id']] == "" && isset($field['required']) && $field['required']) {
                    $json['error'][$field['id']] = $this->language->get('error_fill_all_fields');
                }
            }
        }


        if (empty($json['error'])) {

            if (!$this->setting['fields']['password']['enabled']) {
                $customer_data['password'] = $this->password();
            }

            $customer_id = $this->model_extension_module_d_social_login->addCustomer($customer_data);

            $authentication_data['customer_id'] = (int)$customer_id;
            $this->model_extension_module_d_social_login->addAuthentication($authentication_data);

            //login
            $this->model_extension_module_d_social_login->login($customer_id);

            //redirect
            $json['redirect'] = $this->sl_redirect;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function validate_email($email)
    {
        //if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
            return true;
       // } else {
        //    return false;
       // }
    }

    private function password($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= substr($chars, $index, 1);
        }

        return $result;
    }

    private function setup()
    {
        // correct &amp; in url
        if (isset($this->request->get)) {

            foreach ($this->request->get as $key => $value) {
                $this->request->get[str_replace('amp;', '', $key)] = $value;
            }
        }

        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                $_GET[str_replace('amp;', '', $key)] = $value;
            }
        }

        // set redirect address
        if (isset($this->session->data['sl_redirect']) && !stripos($this->session->data['sl_redirect'], 'logout')) {
            $this->sl_redirect = $this->session->data['sl_redirect'];
        } else {
            $this->sl_redirect = $this->url->link('account/account', '', 'SSL');
        }


    }

    private function getCountryId($profile)
    {
        if ($profile['country']) {
            return $this->model_extension_module_d_social_login->getCountryIdByName($profile['country']);
        }

        if ($profile['region']) {
            return $this->model_extension_module_d_social_login->getCountryIdByName($profile['region']);
        }

        return $this->config->get('config_country_id');
    }

    //todo move to model
    public function getCurrentUrl($request_uri = true, $reset_uri = false)
    {
        if (
            isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
        ) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        $url = $protocol . $_SERVER['HTTP_HOST'];

        if (isset($_SERVER['SERVER_PORT']) && strpos($url, ':' . $_SERVER['SERVER_PORT']) === FALSE) {
            $url .= ($protocol === 'http://' && $_SERVER['SERVER_PORT'] != 80 && !isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) || ($protocol === 'https://' && $_SERVER['SERVER_PORT'] != 443 && !isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) ? ':' . $_SERVER['SERVER_PORT'] : '';
        }

        if ($request_uri) {
            if ($reset_uri) {
                $url .= $_SERVER['HTTP_REFERER'];
            } else {
                $url .= $_SERVER['REQUEST_URI'];
            }
        } else {
            $url .= $_SERVER['PHP_SELF'];
        }

        // return current url
        return $url;
    }

    public function reset()
    {
        unset($this->session->data['provider']);
        $this->response->addHeader('Content-Type: application/html');
        $this->response->setOutput($this->index());
    }
}
