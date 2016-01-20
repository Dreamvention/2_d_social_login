<?php
class ControllerModuleDSocialLogin extends Controller {
	private $id = 'd_social_login';
	private $route = 'module/d_social_login';
	private $sub_versions = array('lite', 'light', 'free');
	private $mbooth = '';
	private $prefix = '';
	private $error = array(); 

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('module/d_social_login');
		$this->mbooth = $this->model_module_d_social_login->getMboothFile($this->id, $this->sub_versions);
	}

	public function index() {

		//dependencies
		$this->load->language($this->route);
		$this->load->language($this->route.'_instruction');
		$this->load->model('setting/setting');
		$this->load->model('design/layout');
		if(VERSION >= '2.1.0.1'){
			$this->load->model('customer/customer_group');
		}else{
			$this->load->model('sale/customer_group');
		}

		//scripts
		$this->document->addStyle('view/stylesheet/bootstrap.css');
		$this->document->addScript('view/javascript/shopunity/bootstrap-sortable.js');
		$this->document->addScript('view/javascript/shopunity/tinysort/jquery.tinysort.min.js');
		$this->document->addStyle('view/javascript/shopunity/colorpicker/css/bootstrap-colorpicker.min.css');
		$this->document->addScript('view/javascript/shopunity/colorpicker/js/bootstrap-colorpicker.min.js');
		$this->document->addScript('view/javascript/shopunity/bootstrap-switch/bootstrap-switch.min.js');
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap-switch/bootstrap-switch.css');
		$this->document->addStyle('view/stylesheet/d_social_login/styles.css');

		//multistore
		if (isset($this->request->get['store_id'])) { 
			$store_id = $this->request->get['store_id']; 
		}else{  
			$store_id = 0;
		}

		//saving
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			
			$this->model_setting_setting->editSetting($this->id, $this->request->post, $store_id);
			 // echo "<pre>"; print_r($this->request->post); echo "</pre>";

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));	
		}

		//status
		if (isset($this->request->post[$this->id.'_status'])) {
			$data[$this->id.'_status'] = $this->request->post[$this->id.'_status'];
		} else {
			$data[$this->id.'_status'] = $this->config->get($this->id.'_status');
		}

		//error
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		//url
		$url = '';
		if(isset($this->request->get['store_id'])){
			$url .=  '&store_id='.$store_id;
		}

		// Heading
		$this->document->setTitle($this->language->get('heading_title_main'));
		$data['heading_title'] = $this->language->get('heading_title_main');
		$data['text_edit'] = $this->language->get('text_edit');
		// Variable
		$data['id'] = $this->id;
		$data['route'] = $this->route;
		$data['store_id'] = $store_id;
		$data['stores'] = $this->model_module_d_social_login->getStores();
		$data['mbooth'] = $this->mbooth;
		$data['config'] = $this->model_module_d_social_login->getConfigFile($this->id, $this->sub_versions);
		$data['version'] = $this->model_module_d_social_login->getVersion($data['mbooth']);
		$data['token'] =  $this->session->data['token'];

		// Tab
		$data['text_module'] = $this->language->get('text_module');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_instruction'] = $this->language->get('text_instruction');

		// Modules
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

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_config_files'] = $this->language->get('entry_config_files');
	
		$data['entry_get_update'] = sprintf($this->language->get('entry_get_update'), $data['version']);
		$data['text_no_update'] = $this->language->get('text_no_update');
		$data['text_new_update'] = $this->language->get('text_new_update');
		$data['text_error_update'] = $this->language->get('text_error_update');
		$data['text_error_failed'] = $this->language->get('text_error_failed');
		$data['button_get_update'] = $this->language->get('button_get_update');

		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['text_facebook'] = $this->language->get('text_facebook');
		$data['text_google'] = $this->language->get('text_google');
		$data['text_twitter'] = $this->language->get('text_twitter');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_linkedin'] = $this->language->get('text_linkedin');
		$data['text_vkontakte'] = $this->language->get('text_vkontakte');
		$data['text_odnoklassniki'] = $this->language->get('text_odnoklassniki');
		$data['text_mailru'] = $this->language->get('text_mailru');
		$data['text_yandex'] = $this->language->get('text_yandex');
		$data['text_instagram'] = $this->language->get('text_instagram');
		$data['text_paypal'] = $this->language->get('text_paypal');
		$data['text_vimeo'] = $this->language->get('text_vimeo');
		$data['text_tumblr'] = $this->language->get('text_tumblr');
		$data['text_yahoo'] = $this->language->get('text_yahoo');
		$data['text_foursquare'] = $this->language->get('text_foursquare');


		$data['entry_fields_sort_order'] = $this->language->get('entry_fields_sort_order');
		$data['text_firstname'] = $this->language->get('text_firstname');
		$data['text_lastname'] = $this->language->get('text_lastname');
		$data['text_phone'] = $this->language->get('text_phone');
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
		$data['entry_debug_mode'] = $this->language->get('entry_debug_mode');

		$data['entry_background_img'] = $this->language->get('entry_background_img');
		$data['text_background_color'] = $this->language->get('text_background_color');
		$data['text_background_color_active'] = $this->language->get('text_background_color_active');
		$data['text_icon'] = $this->language->get('text_icon');
		$data['entry_iframe'] = $this->language->get('entry_iframe');
		
		$data['warning_app_settings'] = $this->language->get('warning_app_settings');
		$data['warning_app_settings_full'] = $this->language->get('warning_app_settings_full');

		$data['text_app_id'] = $this->language->get('text_app_id');
		$data['text_app_secret'] = $this->language->get('text_app_secret');
		$data['text_app_key'] = $this->language->get('text_app_key');
		$data['text_app_scope'] = $this->language->get('text_app_scope');
		$data['text_sort_order'] = $this->language->get('text_sort_order');

		$data['text_app_settings'] = $this->language->get('text_app_settings');


		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');

		// Instructions
		$data['text_instructions_full'] = $this->language->get('text_instructions_full');
		$data['text_debug_file_into'] = $this->language->get('text_debug_file_into');
		$data['entry_debug_file'] = $this->language->get('entry_debug_file');

		// Buttons
		$data['button_save'] = $this->language->get('button_save');
		$data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_module_add'] = $this->language->get('button_module_add');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_clear_debug_file'] = $this->language->get('button_clear_debug_file');
		
		// Action
		$data['module_link'] = $this->url->link($this->route, 'token=' . $this->session->data['token'], 'SSL');
		$data['action'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] .$url, 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['get_update'] = str_replace('&amp;', '&', $this->url->link($this->route.'/getUpdate', 'token=' . $this->session->data['token'], 'SSL'));
		$data['clear_debug_file'] = str_replace('&amp;', '&', $this->url->link($this->route.'/clearDebugFile', 'token=' . $this->session->data['token'], 'SSL'));
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->id, $store_id);
		$setting = (isset($setting[$this->id.'_setting'])) ? $setting[$this->id.'_setting'] : '';

		$this->config->load($data['config']);
		$data['setting'] = ($this->config->get($this->id)) ? $this->config->get($this->id) : array();

		if(!isset($this->request->post['config']) && !empty($setting)){
			$data['setting'] = array_replace_recursive($data['setting'], $setting);
		}

		//background image
		$this->load->model('tool/image');
		if (isset($this->request->post['setting']['background_img'])) {
			$data['background_img'] = $this->request->post['setting']['background_img'];
		} else {
			$data['background_img'] = $data['setting']['background_img'];			
		}
		if ($data['setting']['background_img']&& file_exists(DIR_IMAGE . $data['setting']['background_img']) && is_file(DIR_IMAGE . $data['setting']['background_img'])) {
			$data['background_img_thumb'] = $this->model_tool_image->resize($data['setting']['background_img'], 100, 100);		
		} else {
			$data['background_img_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		//providers
		$data['providers'] = $data['setting']['providers'];
		$data['fields'] = $data['setting']['fields'];

		//Get stores
		$data['stores'] = $this->model_module_d_social_login->getStores();
	
		//get config 
		$data['config_files'] = $this->model_module_d_social_login->getConfigFiles($this->id);

		//customer groups
		if(VERSION >= '2.1.0.1'){
			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		}else{
			$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}
		//debug
		$data['debug'] = $this->model_module_d_social_login->getFileContents(DIR_LOGS.$data['setting']['debug_file']);
		$data['debug_file'] = $data['setting']['debug_file'];

		$data['breadcrumbs'] = array();
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_main'),
			'href'      => $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->model_module_d_social_login->installDatabase();
				
		$this->response->setOutput($this->load->view($this->route . '.tpl', $data));
	}

	/*
	*	validate
	*/
	protected function validate() {
		if (!$this->user->hasPermission('modify', $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['config'])) {
			return false;
		}

		//$this->install();
						
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	/*
	*	validate
	*/
	public function install() {
		$this->model_module_d_social_login->installDatabase();

		$this->model_module_d_social_login->getUpdateInfo($this->mbooth,1);

		$this->model_module_d_social_login->installDependencies($this->mbooth);
	}

	public function uninstall() {

		$this->model_module_d_social_login->getUpdateInfo($this->mbooth,0);
	}

	/*
	*	Ajax: clear debug file.
	*/

	public function clearDebugFile() {
		$this->load->language($this->route);
		$json = array();

		if (!$this->user->hasPermission('modify', $this->route)) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$file = DIR_LOGS.$this->request->post['debug_file'];

			$handle = fopen($file, 'w+');

			fclose($handle);

			$json['success'] = $this->language->get('success_clear_debug_file');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	/*
	*	Ajax: Get the update information on this module. 
	*/
	public function getUpdate($status = 1){
		if($status !== 0){	$status = 1; }

		$json = array();

		$this->load->language($this->route);
		$this->load->model($this->route);

		$current_version = $this->model_module_d_social_login->getVersion($this->mbooth);
		$info = $this->model_module_d_social_login->getUpdateInfo($this->mbooth, $status);

		if ($info['code'] == 200) {
			$data = simplexml_load_string($info['data']);

			if ((string) $data->version == (string) $current_version 
				|| (string) $data->version <= (string) $current_version) 
			{
				$json['success']   = $this->language->get('success_no_update') ;
			} 
			elseif ((string) $data->version > (string) $current_version) 
			{
				$json['warning']   = $this->language->get('warning_new_update');

				foreach($data->updates->update as $update)
				{
					if((string) $update->attributes()->version > (string)$current_version)
					{
						$version = (string)$update->attributes()->version;
						$json['update'][$version] = (string) $update[0];
					}
				}
			} 
			else 
			{
				$json['error']   = $this->language->get('error_update');
			}
		} 
		else 
		{ 
			$json['error']   =  $this->language->get('error_failed');
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>