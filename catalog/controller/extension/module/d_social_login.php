<?php
/*
 *  location: catalog/controller/extension/module/d_social_login.php
 */

class ControllerExtensionModuleDSocialLogin extends Controller
{

    private $route = 'extension/module/d_social_login';
    private $codename = 'd_social_login';
    private $setting = array();
    private $sl_redirect = '';
    private $error = array();
    private $theme = 'default';

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->setting = $this->config->get($this->codename . '_setting');
        $this->language->load($this->route);
        $this->load->model($this->route);
        $this->load->model('extension/module/d_social_login');
        $this->load->model('extension/d_opencart_patch/load');
        if (VERSION >= '2.2.0.0') {
            $this->theme = $this->config->get('config_theme');
        } else {
            $this->theme = $this->config->get('config_template');
        }
    }

    public function index()
    {
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/pre_loader/clip-rotate.css');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/styles.css');
        $this->document->addScript('catalog/view/javascript/d_social_login/spin.min.js');
        $setting = $this->config->get($this->codename . '_setting');
        $method = $_SERVER['REQUEST_METHOD'];
        
        if ('PUT' === $method) {
            parse_str(file_get_contents('php://input'), $_PUT);
            //fix for burn engine
            if ($this->theme == 'BurnEngine') {
                $this->session->data['provider'] = $_PUT['provider'];
                $this->session->data['customer_data'] = $_PUT['customer_data'];
                $this->session->data['authentication_data'] = $_PUT['authentication_data'];
            }
        } else {
            if (!(isset($this->session->data['reset']) && $this->session->data['reset'])) {
                if(isset($this->request->get['route']) && $this->request->get['route'] == 'account/logout'){
                    $redirect_url = $this->url->link('account/account');
                }else{
                    $redirect_url = $this->model_extension_module_d_social_login->getCurrentUrl();
                }
                $this->session->data['redirect_url'] = $redirect_url;
            }
        }
        //load data from provider into form popup
        if (isset($this->session->data['provider']) && $_SERVER['REQUEST_METHOD'] === "PUT" && !empty($_PUT)) {
            $customer_data = $_PUT['customer_data'];
            $authentication_data = $_PUT['authentication_data'];
            if (!empty($customer_data) && !empty($authentication_data)) {
                return $this->getForm($customer_data, $authentication_data);
            }
        }
        $data['title'] = $setting['title'][$this->config->get('config_language_id')];

        $data['size'] = $setting['size'];

        $data['islogged'] = ($this->customer->isLogged()) ? $this->customer->isLogged() : false;

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
        $this->load->model('tool/image');
        foreach ($providers as $key => $val) {
            $data['providers'][$key]['icon'] = $this->model_tool_image->resize('catalog/d_social_login/'.$val['icon'], 10,10);
            
        }

        $data['error'] = false;
        if (isset($this->session->data['d_social_login_error'])) {
            $data['error'] = $this->session->data['d_social_login_error'];
            unset($this->session->data['d_social_login_error']);
        }

        if (!isset($this->session->data['sl_redirect'])) {
            $this->session->data['sl_redirect'] = ($setting['return_page_url']) ? $setting['return_page_url'] : $this->model_extension_module_d_social_login->getCurrentUrl();
        }

        $data['url'] = $this->model_extension_module_d_social_login->getCurrentUrl(1, 1);

        // facebook fix
        unset($this->session->data['HA::CONFIG']);
        unset($this->session->data['HA::STORE']);
        return $this->model_extension_d_opencart_patch_load->view($this->route, $data);
    }

    public function header(){
        if(!empty($this->setting['header'])){
            return $this->index();
        }
    }

    public function login()
    {
        // multistore fix
        $this->initializeSlRedirect();
    
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
        $this->setting['debug_file'] = DIR_LOGS . $this->setting['debug_file'];

        $this->setting['base_url'] = $this->config->get('config_secure') ? $httpsServer . 'index.php?route=extension/d_social_login/callback' : $httpServer . 'index.php?route=extension/d_social_login/callback';
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

        if ($this->setting['provider'] == 'Live') {
            $this->setting['base_url'] = $this->config->get('config_secure') ? $httpsServer . 'd_social_login_live.php' : $httpServer . 'index.php?route=extension/d_social_login/callback_live';
        }
        try {
            $remoteLoginResponce = $this->model_extension_module_d_social_login->remoteLogin($this->setting, $this->sl_redirect);// result from hybrid
            if ($remoteLoginResponce == 'redirect') {
                $this->response->redirect($this->sl_redirect);
            }
            $this->document->addScript('catalog/view/javascript/jquery/jquery-2.1.1.min.js');
            $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/pre_loader/clip-rotate.css');
            $remoteLoginResponce['scripts'] = $this->document->getScripts();
            $remoteLoginResponce['styles'] = $this->document->getStyles();
            $remoteLoginResponce['pre_loader'] = $this->model_extension_module_d_social_login->getPreloader();
            $remoteLoginResponce['url'] = $this->sl_redirect;//fix
            if ($this->theme == 'BurnEngine') {
                $remoteLoginResponce['url'] = $this->url->link($this->route . '/burn_engine');
                $remoteLoginResponce['url_burn_engine'] = $this->sl_redirect;
            }
            $view = $this->model_extension_d_opencart_patch_load->view('extension/'.$this->codename . '/auth', $remoteLoginResponce);
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
        if (($this->request->server['REQUEST_METHOD'] == 'POST')
            && $this->validateRegistration($customer_data)) { //all have to be fine after validation

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

    /*
         * Called when user close pop up window
         * this method reset provider so pop up will not be showen with
         * same  auth data but with same user
         * */
    public function reset()
    {
        unset($this->session->data['provider']);
        $this->session->data['reset'] = true;
        $this->response->addHeader('Content-Type: application/html');
        $this->response->setOutput($this->index());
    }

    private function getForm($customer_data, $authentication_data)
    {
        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/pre_loader/clip-rotate.css');
        $this->document->addScript('catalog/view/javascript/d_social_login/jquery.validate.js');
        $this->document->addScript('catalog/view/javascript/d_social_login/jquery.maskedinput.min.js');

        $data['debug'] = $this->setting['debug_mode'] = (bool)$this->setting['debug_mode'];
        $data['islogged'] = ($this->customer->isLogged()) ? $this->customer->isLogged() : false;
        $data['pre_loader'] = html_entity_decode($this->model_extension_module_d_social_login->getPreloader('clip-rotate'), ENT_QUOTES, 'UTF-8');
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $data['mobile'] = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));

        $this->session->data['customer_data'] = $customer_data;
        $this->session->data['authentication_data'] = $authentication_data;

        $data['customer_data'] = $customer_data;
        $data['authentication_data'] = $authentication_data;
        $data['text_email_intro'] = $this->language->get('text_email_intro');
        $data['text_help_required'] = $this->language->get('text_help_required');
        $data['button_sign_in_mail'] = $this->language->get('button_sign_in_mail');
        $data['button_sign_in'] = $this->language->get('button_sign_in');

        $labels_fields = array();

        foreach (array_keys($this->setting['fields']) as $key) {
            $labels_fields[$key] = $this->language->get('text_entry_' . $key);
            $data["text_" . $key] = $this->language->get('text_' . $key);//load txt
        }
        $data['labels_field'] = $labels_fields;
        $data['background'] = $is_background = !$this->setting['iframe'];
        $background_image = $this->setting['background_img'];
        if ($is_background) {
            $this->load->model('tool/image');
            if (isset($background_image) && $background_image
                && file_exists(DIR_IMAGE . $background_image) && is_file(DIR_IMAGE . $background_image)) {
                $data['background_img_thumb'] = $this->model_tool_image->resize($background_image,
                    $this->setting['background_img']['width'], $background_image, $this->setting['background_img']['height']);
            } else {
                $data['background_img_thumb'] = $this->model_tool_image->resize('no_image.jpg',
                    $this->setting['background_img']['width'], $background_image, $this->setting['background_img']['height']);
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
        $provider = $this->setting['providers'][$this->session->data['provider']];
        $data['log_style'] = ' background-image: url(\'image/catalog/d_social_login/' . $provider['id'] . '.svg\');background-color:' . $provider['background_color'];
        $this->load->model('localisation/country');
        $data['countries'] = $this->model_localisation_country->getCountries();
        return $this->model_extension_d_opencart_patch_load->view('extension/'.$this->codename . '/form', $data);
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

    private function initializeSlRedirect()
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
        
        $this->sl_redirect = isset($this->session->data['redirect_url'])?
            $this->session->data['redirect_url']:
            $this->session->data['redirect_url'] = $this->url->link('account/account');
    }

    private function getConfirmMessageView()
    {
        $data['text_confirm'] = $this->language->get('text_confirm_finish');
        return $this->model_extension_d_opencart_patch_load->view('extension/'.$this->codename . '/confirm', $data);
    }

    public function burn_engine()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ('PUT' === $method) {
            parse_str(file_get_contents('php://input'), $_PUT);
        }
        $this->initializeSlRedirect();
        //load data from provider into form popup
        if (isset($this->session->data['provider']) && $_SERVER['REQUEST_METHOD'] === "PUT" && !empty($_PUT)) {
            $data = array("customer_data" => $_PUT['customer_data'], 'authentication_data' => $_PUT['authentication_data'], 'provider' => $this->session->data['provider']);
            $this->session->data['customer_data'] = $_PUT['customer_data'];
            $this->session->data['authentication_data'] = $_PUT['authentication_data'];
            $url = $this->sl_redirect;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            $response = curl_exec($ch);
            if (!$response) {
                return false;
            }
            $this->response->setOutput($response);
        }

    }
}
