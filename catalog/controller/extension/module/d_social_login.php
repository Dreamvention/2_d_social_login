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
    private $error = array();

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

        //load data from provider into form popup
        if (isset($this->session->data['provider'])) {
            $customer_data = (isset($this->request->post['customer_data'])) ? $this->request->post['customer_data'] : '';
            $authentication_data = (isset($this->request->post['authentication_data'])) ? $this->request->post['authentication_data'] : '';
            if (!empty($customer_data) && !empty($authentication_data)) {
                return $this->getForm($customer_data, $authentication_data);
            }
        }
        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_sign_in'] = $this->language->get('button_sign_in');
        $data['size'] = $setting['size'];
        $data['sizes'] = $setting['sizes'];
        $data['islogged'] = ($this->customer->isLogged()) ? $this->customer->isLogged() : false;
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/styles.css');
        $this->document->addScript('catalog/view/javascript/d_social_login/spin.min.js');
        $providers = $setting['providers'];
        //sorting providers in order wich admin set
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
            $this->session->data['sl_redirect'] = ($setting['return_page_url']) ? $setting['return_page_url'] : $this->model_extension_module_d_social_login->getCurrentUrl();
        }

        // facebook fix
        unset($this->session->data['HA::CONFIG']);
        unset($this->session->data['HA::STORE']);
        return $this->model_extension_d_opencart_patch_load->view($this->route, $data);
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
            $remoteLoginResponce = $this->model_extension_module_d_social_login->remoteLogin($this->setting, $this->sl_redirect);// result from hybrid
            if ($remoteLoginResponce == 'redirect') {
                $this->response->redirect($this->sl_redirect);
            }
            //load data into auth page redirecting
            $this->document->addScript('catalog/view/javascript/jquery/jquery-2.1.1.min.js');
            $remoteLoginResponce['scripts'] = $this->document->getScripts();
            $remoteLoginResponce['url'] = $this->model_extension_module_d_social_login->getCurrentUrl(false);
            $view = $this->model_extension_d_opencart_patch_load->view($this->id . '/auth', $remoteLoginResponce);
            $this->response->setOutput($view);
        } catch (Exception $e) {
            unset($this->session->data['provider']);
            switch ($e->getCode()) {
                case 0 :
                    $error = $this->language->get('unspecified_error');
                    break;
                case 1 :
                    $error = $this->language->get('hybriauth_error');
                    break;
                case 2 :
                    $error = $this->language->get('provider_not_configured_error');
                    break;
                case 3 :
                    $error = $this->language->get('disabled_provider_error');
                    break;
                case 4 :
                    $error = $this->language->get('missing_provider_error');
                    break;
                case 5 :
                    $error = $this->language->get('auth_fail_error');
                    break;
                case 6 :
                    $error = $this->language->get('profile_request_error');
                    if (isset($adapter)) {
                        $adapter->logout();
                    }
                    break;
                case 7 :
                    $error = $this->language->get('user_no_provider_error');
                    break;
                case 8 :
                    $error = $this->language->get('no_feature_provider_error');
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

    public function register()
    {
        $customer_data = array_merge(($this->session->data['customer_data'] != '') ? $this->session->data['customer_data'] : array(), $this->request->post);
        $authentication_data = $this->session->data['authentication_data'];
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateRegistration($customer_data)) {//all have to be fine after validation
            $customer_data = $this->model_extension_module_d_social_login->prepareDataRegistrationFields($customer_data, $this->setting['fields']);
            $customer_id = $this->model_extension_module_d_social_login->addCustomer($customer_data);
            $authentication_data['customer_id'] = (int)$customer_id;
            $this->model_extension_module_d_social_login->addAuthentication($authentication_data);//login
            $this->model_extension_module_d_social_login->login($customer_id);
            $json['success'] = $this->getConfirmMessageView();
            unset($this->session->data['provider']);//($this->url->link('account/success'));
        }
        if (count($this->error)) {
            $json = $this->error;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function reset()
    {
        unset($this->session->data['provider']);
        $this->response->addHeader('Content-Type: application/html');
        $this->response->setOutput($this->index());
    }

    private function getForm($customer_data, $authentication_data)
    {
        $data['islogged'] = ($this->customer->isLogged()) ? $this->customer->isLogged() : false;
        $data['pre_loader'] = html_entity_decode($this->model_extension_module_d_social_login->getPreloader('clip-rotate'), ENT_QUOTES, 'UTF-8');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/pre_loader/' . 'clip-rotate' . '.css');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/form.css' . '?' . rand());
        $this->document->addScript('catalog/view/javascript/d_social_login/jquery.validate.js');
        $this->document->addScript('catalog/view/javascript/d_social_login/jquery.maskedinput.min.js');
        $data['debug'] = $this->setting['debug_mode'] = (bool)$this->setting['debug_mode'];
        $this->session->data['customer_data'] = $customer_data;
        $this->session->data['authentication_data'] = $authentication_data;
        $data['customer_data'] = $customer_data;
        $data['authentication_data'] = $authentication_data;
        $data['button_sign_in_mail'] = $this->language->get('button_sign_in_mail');
        $data['button_sign_in'] = $this->language->get('button_sign_in');
        $labels_fields = array();

        foreach (array_keys($this->setting['fields']) as $key) {
            $labels_fields[$key] = $this->language->get('text_entry_' . $key);
        }
        $data['labels_field'] = $labels_fields;
        $data['background'] = $is_background = !$this->setting['iframe'];
        $background_image = $this->setting['background_img'];
        // no need $background_color = $this->setting['providers'][ucfirst($this->setting['provider'])]['background_color'];
        if ($is_background) {
            $this->load->model('tool/image');
            if (isset($background_image) && $background_image && file_exists(DIR_IMAGE . $background_image) && is_file(DIR_IMAGE . $background_image)) {
                $data['background_img_thumb'] = $this->model_tool_image->resize($background_image, 300, 300);
            } else {
                $data['background_img_thumb'] = $this->model_tool_image->resize('no_image.jpg', 300, 300);
            }
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
        //is problem still relevant?
        return $this->model_extension_d_opencart_patch_load->view($this->id . '/form', $data);
    }

    private function getConfirmMessageView()
    {
        $data['text_confirm'] = $this->language->get('text_confirm_finish');
        return $this->model_extension_d_opencart_patch_load->view($this->id . '/confirm', $data);
    }

    private function validateRegistration($customer_data)
    {
        foreach ($this->setting['fields'] as $field) {
            if ($field['enabled'] && isset($field['required']) && $field['required']) {
                if ($field['id'] == 'confirm') {
                    if (($customer_data['password'] != $customer_data['confirm'])) {
                        $this->error['error']['confirm'] = $this->language->get('error_password_and_confirm_different');
                    }
                }
                if ($this->request->post[$field['id']] == "" && isset($field['required']) && $field['required']) {
                    $this->error['error'][$field['id']] = $this->language->get('error_fill_all_fields');
                }
            }
        }
        if (isset($customer_data['email']) && isset($this->setting['fields']['email']['required']) && $this->setting['fields']['email']['required']) {
            if ($this->model_extension_module_d_social_login->validateEmail($customer_data['email'])) {
                $customer_id = $this->model_extension_module_d_social_login->getCustomerByEmail($customer_data['email']);
                if ($customer_id) {
                    if ($this->model_extension_module_d_social_login->checkAuthentication($customer_id, $this->request->post['provider'])) {
                        $this->error['error']['email'] = $this->language->get('error_email_taken');
                    }
                }
            } else {
                $this->error['error']['email'] = $this->language->get('error_email_incorrect');
            }

        }
        return !$this->error;

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


}
