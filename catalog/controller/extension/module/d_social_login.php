<?php
/*
 *  location: catalog/controller/extension/module/d_social_login.php
 */

class ControllerExtensionModuleDSocialLogin extends Controller {

    private $route = 'extension/module/d_social_login';
    private $id = 'd_social_login';
    private $setting = array();
    private $sl_redirect = '';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->language->load($this->route);
        $this->load->model($this->route);
        $this->load->model('extension/module/d_social_login');
        $this->load->model('extension/d_opencart_patch/load');
    }

    public function index()
    {
        $this->setup();

        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/styles.css');
        $this->document->addScript('catalog/view/javascript/d_social_login/spin.min.js');

        $setting = $this->config->get('d_social_login_setting');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_sign_in'] = $this->language->get('button_sign_in');
        $data['size'] = $setting['size'];
        $data['islogged'] = $this->customer->isLogged();

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

        $this->session->data['sl_redirect'] = ($setting['return_page_url']) ? $setting['return_page_url'] : $this->getCurrentUrl();

        // facebook fix
        unset($this->session->data['HA::CONFIG']);
        unset($this->session->data['HA::STORE']);

        if(VERSION >= '2.2.0.0'){
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
        $this->setup();
        require_once(DIR_SYSTEM . 'library/hybrid/auth.php');

        $this->setting = $this->config->get('d_social_login_setting');

        // multistore fix
        $this->load->model('setting/store');
        $stores = $this->model_setting_store->getStores();
        $store_id = $this->config->get('config_store_id');
        foreach ($stores as $store ) {
            if($store_id == $store['store_id']){
                $httpServer =  $store['url'];
                $httpsServer = $store['ssl'];
                break;
            }
        }
        if(empty($httpServer) || empty($httpsServer)){
            $httpsServer = HTTPS_SERVER;
            $httpServer = HTTP_SERVER;
        }

        $this->setting['base_url'] = $this->config->get('config_secure') ? $httpServer . 'index.php?route=extension/d_social_login/callback' : $httpServer . 'index.php?route=extension/d_social_login/callback';
        $this->setting['debug_file'] = DIR_LOGS . $this->setting['debug_file'];
        $this->setting['debug_mode'] = (bool) $this->setting['debug_mode'];

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
            $hybridauth = new Hybrid_Auth($this->setting);
            Hybrid_Auth::$logger->info('d_social_login: Start authantication.');
            $adapter = $hybridauth->authenticate($this->setting['provider']);
            Hybrid_Auth::$logger->info('d_social_login: Start getUserProfile.');
            // get the user profile
            $profile = $adapter->getUserProfile();
            $this->setting['profile'] = (array) $profile;

            Hybrid_Auth::$logger->info('d_social_login: got UserProfile.' . serialize($this->setting['profile']));
            $authentication_data = array(
                'provider' => $this->setting['provider'],
                'identifier' => $this->setting['profile']['identifier'],
                'web_site_url' => $this->setting['profile']['webSiteURL'],
                'profile_url' => $this->setting['profile']['profileURL'],
                'photo_url' => $this->setting['profile']['photoURL'],
                'display_name' => $this->setting['profile']['displayName'],
                'description' => $this->setting['profile']['description'],
                'first_name' => $this->setting['profile']['firstName'],
                'last_name' => $this->setting['profile']['lastName'],
                'gender' => $this->setting['profile']['gender'],
                'language' => $this->setting['profile']['language'],
                'age' => $this->setting['profile']['age'],
                'birth_day' => $this->setting['profile']['birthDay'],
                'birth_month' => $this->setting['profile']['birthMonth'],
                'birth_year' => $this->setting['profile']['birthYear'],
                'email' => $this->setting['profile']['email'],
                'email_verified' => $this->setting['profile']['emailVerified'],
                'telephone' => $this->setting['profile']['phone'],
                'address' => $this->setting['profile']['address'],
                'country' => $this->setting['profile']['country'],
                'region' => $this->setting['profile']['region'],
                'city' => $this->setting['profile']['city'],
                'zip' => $this->setting['profile']['zip']
            );

            Hybrid_Auth::$logger->info('d_social_login: set authentication_data ' . serialize($authentication_data));

            // check by identifier
            $customer_id = $this->model_extension_module_d_social_login->getCustomerByIdentifier($this->setting['provider'], $this->setting['profile']['identifier']);

            if ($customer_id) {
                Hybrid_Auth::$logger->info('d_social_login: getCustomerByIdentifier success.');
                $this->load->model('extension/module/d_social_login');
                $this->model_extension_module_d_social_login->login($customer_id);

                // redirect
                $this->response->redirect($this->sl_redirect);
            }
            $this->load->model('extension/module/d_social_login');
            $customer_id = $this->model_extension_module_d_social_login->getCustomerByIdentifierOld($this->setting['provider'], $this->setting['profile']['identifier']);

            // check by email
            if ($this->setting['profile']['email']) {
                $this->load->model('extension/module/d_social_login');
                $customer_id = $this->model_extension_module_d_social_login->getCustomerByEmail($this->setting['profile']['email']);
                if ($customer_id) {
                    Hybrid_Auth::$logger->info('d_social_login: getCustomerByEmail success.');
                }
            }

            if (!$customer_id) {
                Hybrid_Auth::$logger->info('d_social_login: no customer_id. creating customer_data');
                // prepare customer data
                $address = array();

                if (!empty($this->setting['profile']['address'])) {
                    $address[] = $this->setting['profile']['address'];
                }

                if (!empty($this->setting['profile']['region'])) {
                    $address[] = $this->setting['profile']['region'];
                }

                if (!empty($this->setting['profile']['country'])) {
                    $address[] = $this->setting['profile']['country'];
                }

                $customer_data = array(
                    'email' => $this->setting['profile']['email'],
                    'firstname' => $this->setting['profile']['firstName'],
                    'lastname' => $this->setting['profile']['lastName'],
                    'telephone' => $this->setting['profile']['phone'],
                    'fax' => false,
                    'newsletter' => $this->setting['newsletter'],
                    'customer_group_id' => (isset($this->setting['customer_group'])) ? $this->setting['customer_group'] : '1',
                    'company' => false,
                    'address_1' => ($address ? implode(', ', $address) : false),
                    'address_2' => false,
                    'city' => $this->setting['profile']['city'],
                    'postcode' => $this->setting['profile']['zip'],
                    'country_id' => $this->model_extension_module_d_social_login->getCountryIdByName($this->setting['profile']['country']),
                    'zone_id' => $this->model_extension_module_d_social_login->getZoneIdByName($this->setting['profile']['region']),
                    'password' => ''
                );

                Hybrid_Auth::$logger->info('d_social_login: set customer_data ' . serialize($customer_data));

                //check if form required
                $form = false;
                foreach ($this->setting['fields'] as $field) {
                    if ($field['enabled']) {
                        //checking if fields required for input
                        $form = true;
                        break;
                    }
                }

                if (!$form) {
                    Hybrid_Auth::$logger->info('d_social_login: adding customer with customer_data');
                    $customer_data['password'] = $this->password();
                    $customer_id = $this->model_extension_module_d_social_login->addCustomer($customer_data);
                } else {
                    Hybrid_Auth::$logger->info('d_social_login: need to use form');
                    $this->form($customer_data, $authentication_data);
                }
            }

            if ($customer_id) {
                Hybrid_Auth::$logger->info('d_social_login: customer_id found');
                $authentication_data['customer_id'] = (int) $customer_id;

                $this->model_extension_module_d_social_login->addAuthentication($authentication_data);
                Hybrid_Auth::$logger->info('d_social_login: addAuthentication');
                // login
                $this->model_extension_module_d_social_login->login($customer_id);

                // redirect
                $this->response->redirect($this->sl_redirect);
            }
        } catch (Exception $e) {

            switch ($e->getCode()) {
                case 0 : $error = "Unspecified error.";
                    break;
                case 1 : $error = "Hybriauth configuration error.";
                    break;
                case 2 : $error = "Provider not properly configured.";
                    break;
                case 3 : $error = "Unknown or disabled provider.";
                    break;
                case 4 : $error = "Missing provider application credentials.";
                    break;
                case 5 : $error = "Authentication failed. The user has canceled the authentication or the provider refused the connection.";
                    break;
                case 6 : $error = "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.";
                    if (isset($adapter)) {
                        $adapter->logout();
                    }
                    break;
                case 7 : $error = "User not connected to the provider.";
                    break;
                case 8 : $error = "Provider does not support this feature.";
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
        $this->session->data['customer_data'] = $customer_data;
        $this->session->data['authentication_data'] = $authentication_data;
        $data['provider'] = $this->setting['provider'];
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
        // $data['text_company_id'] = $this->language->get('text_company_id');
        // $data['text_tax_id'] = $this->language->get('text_tax_id');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_email_intro'] = $this->language->get('text_email_intro');

        $data['background_img'] = $this->setting['background_img'];
        $data['background_color'] = $this->setting['providers'][ucfirst($this->setting['provider'])]['background_color'];
        if ($this->setting['iframe']) {
            $data['iframe'] = $this->sl_redirect;
        } else {
            $data['iframe'] = false;
        }

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

        if(VERSION >= '2.2.0.0'){
            $this->response->setOutput($this->load->view('d_social_login/form', $data));
        }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_social_login/form.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/d_social_login/form.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/d_social_login/form.tpl', $data));
        }
    }

    public function register()
    {
        $this->setup();

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            return false;
        }

        $json = array();
        $customer_data = array_merge($this->session->data['customer_data'], $this->request->post);
        $authentication_data = $this->session->data['authentication_data'];
        $this->setting = $this->config->get('d_social_login_setting');

        // check email
        if ($this->validate_email($customer_data['email'])) {
            $customer_id = $this->model_extension_module_d_social_login->getCustomerByEmail($customer_data['email']);
            if ($customer_id) {
                if (!$this->model_extension_module_d_social_login->checkAuthentication($customer_id, $this->session->data['provider'])) {
                    $authentication_data['customer_id'] = (int) $customer_id;
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
                if ($this->request->post[$field['id']] == "") {
                    $json['error'][$field['id']] = $this->language->get('error_fill_all_fields');
                }
            }
        }


        if (empty($json['error'])) {

            if (!$this->setting['fields']['password']['enabled']) {
                $customer_data['password'] = $this->password();
            }

            $customer_id = $this->model_extension_module_d_social_login->addCustomer($customer_data);

            $authentication_data['customer_id'] = (int) $customer_id;
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
        if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
            return true;
        } else {
            return false;
        }
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

    public function getCurrentUrl($request_uri = true)
    {
        if (
            isset($_SERVER['HTTPS']) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
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
            $url .= $_SERVER['REQUEST_URI'];
        } else {
            $url .= $_SERVER['PHP_SELF'];
        }

        // return current url
        return $url;
    }

}
