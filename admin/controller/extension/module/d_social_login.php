<?php

class ControllerExtensionModuleDSocialLogin extends Controller
{
    private $codename = 'd_social_login';
    private $route = 'extension/module/d_social_login';
    private $error = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->d_admin_style = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_admin_style.json'));
        $this->d_shopunity = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_shopunity.json'));
        $this->d_opencart_patch = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_opencart_patch.json'));
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM . 'library/d_shopunity/extension/' . $this->codename . '.json'), true);
        $this->d_twig_manager = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_twig_manager.json'));
        $this->d_social_login_pro = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_social_login_pro.json'));
        $this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;
    }

    public function index()
    {
        if ($this->d_shopunity) {
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);
        }

        if ($this->d_twig_manager) {
            $this->load->model('extension/module/d_twig_manager');
            $this->model_extension_module_d_twig_manager->installCompatibility();
        }

        if ($this->d_admin_style){
            $this->load->model('extension/d_admin_style/style');
            $this->model_extension_d_admin_style_style->getStyles('light');
        }

        $this->load->language($this->route);
        $this->load->language($this->route . '_instruction');
        $this->load->model($this->route);
        $this->load->model('design/layout');
        $this->load->model('setting/setting');
        $this->load->model('extension/d_opencart_patch/module');
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/load');
        $this->load->model('extension/d_opencart_patch/user');
        $this->load->model('extension/d_opencart_patch/modification');

        $this->load->model('extension/d_opencart_patch/cache');
        $this->model_extension_d_opencart_patch_cache->clearTwig();

        // Saving
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // 3.x fix
            if (VERSION >= '3.0.0.0') {
                $sl_post_array = array();
                if ($this->request->post[$this->codename . '_status'] == 0) {
                    $sl_post_array['module_' . $this->codename . '_status'] = 0;
                } elseif ($this->request->post[$this->codename . '_status'] == 1) {
                    $sl_post_array['module_' . $this->codename . '_status'] = 1;
                }
                $this->model_setting_setting->editSetting('module_' . $this->codename, $sl_post_array, $this->store_id);
            }

            $this->model_setting_setting->editSetting($this->codename, $this->request->post, $this->store_id);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->model_extension_d_opencart_patch_url->getExtensionLink('module'));
        }

        // Scripts
        $this->document->addScript('view/javascript/d_social_login/bootstrap-sortable.js');
        $this->document->addScript('view/javascript/d_bootstrap_tour/js/bootstrap-tour.js');
        $this->document->addStyle('view/javascript/d_bootstrap_tour/css/bootstrap-tour.min.css');
        $this->document->addStyle('view/javascript/d_bootstrap_colorpicker/css/bootstrap-colorpicker.css');
        $this->document->addScript('view/javascript/d_bootstrap_colorpicker/js/bootstrap-colorpicker.js');
        $this->document->addScript('view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('view/javascript/jquery/magnific/magnific-popup.css');
        $this->document->addScript('view/javascript/d_tinysort/tinysort.js');
        $this->document->addScript('view/javascript/d_tinysort/jquery.tinysort.min.js');
        $this->document->addStyle('view/stylesheet/d_social_login.css');

        // Breadcrumbs
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_d_opencart_patch_url->link('common/dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->model_extension_d_opencart_patch_url->getExtensionLink('modules')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->model_extension_d_opencart_patch_url->link($this->route)
        );
        
        if(isset($this->session->data['success'])){
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }else{
            $data['success'] = '';
        }

        // Error
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        // Url
        $url = '';
        if (isset($this->request->get['store_id'])) {
            $url .= '&store_id=' . $this->store_id;
        }

        // Heading
        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_edit'] = $this->language->get('text_edit');

        // Variable
        $data['codename'] = $this->codename;
        $data['route'] = $this->route;
        $data['store_id'] = $this->store_id;
        $data['stores'] = $this->model_extension_module_d_social_login->getStores();
        $data['version'] = $this->extension['version'];
        $data['url_token'] = $this->model_extension_d_opencart_patch_user->getUrlToken();
        $data['token'] = $this->model_extension_d_opencart_patch_user->getToken();
        $data['pro'] = $this->d_social_login_pro;
        $data['text_pro'] = $this->language->get('text_pro');
        $data['d_shopunity'] = $this->d_shopunity;
        $data['text_setup'] = $this->language->get('text_setup');

        $data['text_module'] = $this->language->get('text_module');
        $data['text_setting'] = $this->language->get('text_setting');
        $data['text_instruction'] = $this->language->get('text_instruction');
        $data['text_social_login'] = $this->language->get('text_social_login');
        $data['text_registration_field'] = $this->language->get('text_registration_field');

        $data['entry_support'] = $this->language->get('entry_support');
        $data['text_support'] = $this->language->get('text_support');
        $data['text_expend_to_edit'] = $this->language->get('text_expend_to_edit');
        
        $data['text_api'] = $this->language->get('text_api');
        $data['text_no_api'] = $this->language->get('text_no_api');
        $data['text_icons'] = $this->language->get('text_icons');
        $data['text_small'] = $this->language->get('text_small');
        $data['text_medium'] = $this->language->get('text_medium');
        $data['text_large'] = $this->language->get('text_large');
        $data['text_huge'] = $this->language->get('text_huge');

        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $data['entry_status'] = $this->language->get('entry_status');

        // Settings
        $data['text_setting_basic'] = $this->language->get('text_setting_basic');
        $data['text_setting_field'] = $this->language->get('text_setting_field');
        $data['text_setting_button'] = $this->language->get('text_setting_button');
        $data['text_setting_provider'] = $this->language->get('text_setting_provider');
        $data['text_debug'] = $this->language->get('text_debug');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_fields_sort_order'] = $this->language->get('entry_fields_sort_order');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_firstname'] = $this->language->get('text_firstname');
        $data['text_lastname'] = $this->language->get('text_lastname');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_mask'] = $this->language->get('text_mask');
        $data['text_address_1'] = $this->language->get('text_address_1');
        $data['text_address_2'] = $this->language->get('text_address_2');
        $data['text_city'] = $this->language->get('text_city');
        $data['text_postcode'] = $this->language->get('text_postcode');
        $data['text_country_id'] = $this->language->get('text_country_id');
        $data['text_zone_id'] = $this->language->get('text_zone_id');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_company'] = $this->language->get('text_company');
        $data['text_company_id'] = $this->language->get('text_company_id');
        $data['text_tax_id'] = $this->language->get('text_tax_id');

        $data['entry_return_page_url'] = $this->language->get('entry_return_page_url');
        $data['help_return_page_url'] = $this->language->get('help_return_page_url');
        $data['entry_debug_mode'] = $this->language->get('entry_debug_mode');
        $data['entry_header'] = $this->language->get('entry_header');
        
        if(VERSION < '3.0.0.0'){
            $data['text_header_placeholder'] = $this->language->get('text_header_placeholder_2');
            $data['text_header_value'] = '<?php echo $d_social_login; ?>';
        }else{
            $data['text_header_placeholder'] = $this->language->get('text_header_placeholder_3');
            $data['text_header_value'] = '{{ d_social_login }}';
        }
        

        $data['entry_background_img'] = $this->language->get('entry_background_img');
        $data['text_background_color'] = $this->language->get('text_background_color');
        $data['text_background_color_active'] = $this->language->get('text_background_color_active');
        $data['text_background_color_hover'] = $this->language->get('text_background_color_hover');
        $data['text_icon'] = $this->language->get('text_icon');
        $data['entry_iframe'] = $this->language->get('entry_iframe');

        $data['text_app_id'] = $this->language->get('text_app_id');
        $data['text_app_secret'] = $this->language->get('text_app_secret');
        $data['text_app_key'] = $this->language->get('text_app_key');
        $data['text_app_scope'] = $this->language->get('text_app_scope');
        $data['text_sort_order'] = $this->language->get('text_sort_order');
        $data['text_app_settings'] = $this->language->get('text_app_settings');
        $data['text_image_manager'] = $this->language->get('text_image_manager');
        $data['text_browse'] = $this->language->get('text_browse');
        $data['text_clear'] = $this->language->get('text_clear');

        $data['text_instruction_social_login'] = $this->language->get('text_instruction_social_login');

        // Instructions
        $data['text_debug_file_into'] = $this->language->get('text_debug_file_into');
        $data['text_tour_title_1'] = $this->language->get('text_tour_title_1');
        $data['text_tour_content_1'] = $this->language->get('text_tour_content_1');
        $data['text_end'] = $this->language->get('text_end');
        $data['text_start'] = $this->language->get('text_start');
        $data['text_tour_title_2'] = $this->language->get('text_tour_title_2');
        $data['text_tour_content_2'] = $this->language->get('text_tour_content_2');
        $data['text_tour_title_3'] = $this->language->get('text_tour_title_3');
        $data['text_tour_content_3'] = $this->language->get('text_tour_content_3');
        $data['text_tour_title_4'] = $this->language->get('text_tour_title_4');
        $data['text_tour_content_4'] = $this->language->get('text_tour_content_4');
        $data['text_tour_title_5'] = $this->language->get('text_tour_title_5');
        $data['text_tour_content_5'] = $this->language->get('text_tour_content_5');
        $data['text_tour_title_6'] = $this->language->get('text_tour_title_6');
        $data['text_tour_content_6'] = $this->language->get('text_tour_content_6');
        $data['text_tour_title_7'] = $this->language->get('text_tour_title_7');

        $data['text_tour_content_7'] = $this->language->get('text_tour_content_7');
        $data['text_tour_title_8'] = $this->language->get('text_tour_title_8');
        $data['text_tour_content_8'] = $this->language->get('text_tour_content_8');
        $data['text_tour_title_9'] = $this->language->get('text_tour_title_9');
        $data['text_tour_content_9'] = $this->language->get('text_tour_content_9');

        // Buttons
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_module_add'] = $this->language->get('button_module_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_clear_debug_file'] = $this->language->get('button_clear_debug_file');

        // Action
        $data['module_link'] = $this->model_extension_d_opencart_patch_url->link($this->route);
        $data['action'] = $this->model_extension_d_opencart_patch_url->link($this->route, $url);
        $data['cancel'] = $this->model_extension_d_opencart_patch_url->getExtensionLink('module');
        $data['clear_debug_file'] = $this->model_extension_d_opencart_patch_url->link($this->route . '/clearDebugFile');
        $data['setup_link'] = $this->model_extension_d_opencart_patch_url->ajax($this->route . '/setup');
        $data['setup'] = $this->isSetup();
        
        // Status
        if (isset($this->request->post[$this->codename . '_status'])) {
            $data[$this->codename . '_status'] = $this->request->post[$this->codename . '_status'];
        } else {
            $data[$this->codename . '_status'] = $this->config->get($this->codename . '_status');
        }

        // Setting
        $data['setting'] = $this->getSetting();

        $data['fields'] = $data['setting']['fields'];

        // Background image size from config
        $this->load->model('tool/image');
        if (isset($this->request->post['setting']['background_img'])) {
            $data['background_img'] = $this->request->post['setting']['background_img'];
        } else {
            $data['background_img'] = $data['setting']['background_img'];
        }

        if ($data['setting']['background_img'] && file_exists(DIR_IMAGE . $data['setting']['background_img']) && is_file(DIR_IMAGE . $data['setting']['background_img'])) {
            $data['background_img_thumb'] = $this->model_tool_image->resize($data['setting']['background_img'], $data['setting']['background_img_size']['width'], $data['setting']['background_img_size']['height']);
        } else {
            $data['background_img_thumb'] = $this->model_tool_image->resize('no_image.png', $data['setting']['background_img_size']['width'], $data['setting']['background_img_size']['height']);
        }

        // Customer groups
        if (VERSION >= '2.1.0.1') {
            $this->load->model('customer/customer_group');
            $data['customer_groups'] =  $this->model_customer_customer_group->getCustomerGroups();
        } else {
            $this->load->model('sale/customer_group');
            $data['customer_groups'] =  $this->model_sale_customer_group->getCustomerGroups();
        }

        //languages
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();
        foreach ($data['languages'] as $key =>  $language){
            if(VERSION >= '2.2.0.0'){
                $data['languages'][$key]['flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
            }else{
                $data['languages'][$key]['flag'] = 'view/image/flags/'.$language['image'];
            }
        }

        // Debug
        $data['debug'] = $this->model_extension_module_d_social_login->getFileContents(DIR_LOGS . $data['setting']['debug_file']);
        $data['debug_file'] = $data['setting']['debug_file'];

        

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view($this->route, $data));
    }

    public  function setup(){
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/url');

        $this->load->model('extension/module/d_social_login');
        $this->model_extension_module_d_social_login->installConfig();

        $this->model_extension_module_d_social_login->addToLayoutFromSetup('d_social_login');

        $this->session->data['success'] = $this->language->get('text_success_setup');
        $this->response->redirect($this->model_extension_d_opencart_patch_url->ajax($this->route));
    }

    public function isSetup(){
        $this->load->model('setting/setting');
        return $this->model_setting_setting->getSetting($this->codename);
    }

    protected function validate()
    {
        $this->load->language($this->route);
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['config'])) {
            return false;
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function getSetting()
    {
        $this->load->model('setting/setting');
        $this->load->model('extension/module/d_social_login');

        $this->config->load($this->codename);
        $config = $this->config->get($this->codename);

        
        $config['providers'] = $this->model_extension_module_d_social_login->loadProviders($this->codename);

        if ($this->model_setting_setting->getSetting($this->codename, $this->store_id)) {
            $setting = $this->model_setting_setting->getSetting($this->codename, $this->store_id);
            $setting = ($setting) ? $setting[$this->codename . '_setting'] : array();
            foreach($setting['providers'] as $provider_id => $provider){
                if(!isset($config['providers'][$provider_id])){
                    unset($setting['providers'][$provider_id]);
                }
            }
        } else {
            $setting = array();
        }

        

        $setting = array_replace_recursive($config, $setting);

        $setting['sizes'] = $config['sizes'];
        return $setting;
    }

    public function install()
    {
        if ($this->d_shopunity) {
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->installDependencies($this->codename);
        }

        $this->load->model('extension/module/d_social_login');
        $this->model_extension_module_d_social_login->installDatabase();

        if ($this->d_opencart_patch) {
            $this->load->model('extension/d_opencart_patch/modification');
            $this->model_extension_d_opencart_patch_modification->setModification('d_social_login.xml', 1);
            $this->model_extension_d_opencart_patch_modification->refreshCache();
        }
    }

    public function uninstall()
    {
        $this->load->model('extension/module/d_social_login');
        $this->model_extension_module_d_social_login->uninstallDatabase();

        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('module_' . $this->codename);
        $this->model_setting_setting->deleteSetting($this->codename);

        if ($this->d_opencart_patch) {
            $this->load->model('extension/d_opencart_patch/modification');
            $this->model_extension_d_opencart_patch_modification->setModification('d_social_login.xml', 0);
        }
    }

    public function clearDebugFile()
    {
        $this->load->language($this->route);
        $json = array();

        if (!$this->user->hasPermission('modify', $this->route)) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $file = DIR_LOGS . $this->request->post['debug_file'];
            $handle = fopen($file, 'w+');
            fclose($handle);
            $json['success'] = $this->language->get('success_clear_debug_file');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}