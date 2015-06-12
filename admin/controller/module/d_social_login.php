<?php
class ControllerModuleDSocialLogin extends Controller {
	private $id = 'd_social_login';
	private $route = 'module/d_social_login';
	private $lite = array('lite', 'light', 'free');
	private $mbooth = '';
	private $prefix = '';
	private $error = array(); 

	public function __construct($registry) {
		parent::__construct($registry);

		$this->mbooth = $this->getMboothFile();

	}

	public function index() {

		//dependencies
		$this->load->language($this->route);
		$this->load->model('setting/setting');
		$this->load->model('design/layout');
		$this->load->model('sale/customer_group');

		//scripts
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
		$this->document->addScript('view/javascript/shopunity/bootstrap-sortable.js');
		$this->document->addScript('view/javascript/shopunity/tinysort/jquery.tinysort.min.js');
		$this->document->addStyle('view/javascript/shopunity/colorpicker/css/bootstrap-colorpicker.min.css');
		$this->document->addScript('view/javascript/shopunity/colorpicker/js/bootstrap-colorpicker.min.js');
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
		$data['stores'] = $this->getStores();
		$data['mbooth'] = $this->mbooth;
		$data['config'] = $this->getConfigFile();
		$data['version'] = $this->getVersion($data['mbooth']);
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
		$data['stores'] = $this->getStores();
	
		//get config 
		$data['config_files'] = $this->getConfigFiles();

		//customer groups
		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		//debug
		$data['debug'] = $this->getFileContents(DIR_LOGS.$data['setting']['debug_file']);
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

		$this->db_authentication();
				
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

		// $query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND TABLE_NAME = '" . DB_PREFIX . "customer' ORDER BY ORDINAL_POSITION"); 
		// $result = $query->rows; 
		// $columns = array();
		// foreach($result as $column){
		// 	$columns[] = $column['COLUMN_NAME'];
		// }

		// if(!in_array('facebook_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD facebook_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('twitter_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD twitter_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('google_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD google_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('linkedin_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD linkedin_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('vkontakte_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD vkontakte_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('odnoklassniki_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD odnoklassniki_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('live_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD live_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('yandex_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD yandex_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('mailru_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD mailru_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('instagram_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD instagram_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('paypal_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD paypal_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('vimeo_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD vimeo_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('tumblr_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD tumblr_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('yahoo_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD yahoo_id VARCHAR( 255 )  NOT NULL");
		// }
		// if(!in_array('foursquare_id', $columns)){
		// 	 $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD foursquare_id VARCHAR( 255 )  NOT NULL");
		// }

		$this->db_authentication();

		$this->getUpdate(1);
	}

	public function db_authentication(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customer_authentication` (
		  `customer_authentication_id` int(11) NOT NULL AUTO_INCREMENT,
		  `customer_id` int(11) NOT NULL,
		  `provider` varchar(55) NOT NULL,
		  `identifier` varchar(200) NOT NULL,
		  `web_site_url` varchar(255) NOT NULL,
		  `profile_url` varchar(255) NOT NULL,
		  `photo_url` varchar(255) NOT NULL,
		  `display_name` varchar(255) NOT NULL,
		  `description` varchar(255) NOT NULL,
		  `first_name` varchar(255) NOT NULL,
		  `last_name` varchar(255) NOT NULL,
		  `gender` varchar(255) NOT NULL,
		  `language` varchar(255) NOT NULL,
		  `age` varchar(255) NOT NULL,
		  `birth_day` varchar(255) NOT NULL,
		  `birth_month` varchar(255) NOT NULL,
		  `birth_year` varchar(255) NOT NULL,
		  `email` varchar(255) NOT NULL,
		  `email_verified` varchar(255) NOT NULL,
		  `phone` varchar(255) NOT NULL,
		  `address` varchar(255) NOT NULL,
		  `country` varchar(255) NOT NULL,
		  `region` varchar(255) NOT NULL,
		  `city` varchar(255) NOT NULL,
		  `zip` varchar(255) NOT NULL,
		  `date_added` datetime NOT NULL,
		  PRIMARY KEY (`customer_authentication_id`),
		  UNIQUE KEY `identifier` (`identifier`, `provider`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;");
	}
		 
	public function uninstall() {

		  $this->getUpdate(0);
	}

	/*
	*	custom helper functions
	*/
	public function array_merge_recursive_distinct( array &$array1, array &$array2 )
	{
	  $merged = $array1;	
	  foreach ( $array2 as $key => &$value )
		  {
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
			{
			  $merged [$key] = $this->array_merge_recursive_distinct ( $merged [$key], $value );
			}
			else
			{
			  $merged [$key] = $value;
			}
		  }
		
	  return $merged;
	}

	private function getFileContents($file){

		if (file_exists($file)) {
			$size = filesize($file);

			if ($size >= 5242880) {
				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				);

				$i = 0;

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				return sprintf($this->language->get('error_get_file_contents'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
			} else {
				return file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
			}
		}
	}

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
	*	default helper functions
	*/
	private function getStores(){
		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();
		$result = array();
		if($stores){
			$result[] = array(
				'store_id' => 0, 
				'name' => $this->config->get('config_name')
			);
			foreach ($stores as $store) {
				$result[] = array(
					'store_id' => $store['store_id'],
					'name' => $store['name']	
				);
			}	
		}
		return $result;
	}

	private function getVersion($mbooth){
		if(file_exists(DIR_SYSTEM . 'mbooth/xml/'. $mbooth)){
			$xml = new SimpleXMLElement(file_get_contents(DIR_SYSTEM . 'mbooth/xml/'. $mbooth));
			return $xml->version;
		}else{
			return false;
		}
	}
	
	private function getConfigFile(){
		
		if(isset($this->request->post['config'])){
			return $this->request->post['config'];
		}

		$setting = $this->config->get($this->id.'_setting');

		if(isset($setting['config'])){
			return $setting['config'];
		}

		$full = DIR_SYSTEM . 'config/'. $this->id . '.php';
		if (file_exists($full)) {
			return $this->id;
		} 

		foreach ($this->lite as $file){
			if (file_exists(DIR_SYSTEM . 'config/'. $this->id . '_' . $file . '.php')) {
				return $this->id . '_' . $file;
			}
		}
		
		return false;
	}

	private function getConfigFiles(){
		$files = array();
		$results = glob(DIR_SYSTEM . 'config/'. $this->id .'*');
		foreach($results as $result){
			$files[] = str_replace('.php', '', str_replace(DIR_SYSTEM . 'config/', '', $result));
		}
		return $files;
	}

	private function getMboothFile(){
		$full = DIR_SYSTEM . 'mbooth/xml/mbooth_'. $this->id .'.xml';
		if (file_exists($full)) {
			return 'mbooth_'. $this->id . '.xml';
		} else{
			foreach ($this->lite as $file){
				if (file_exists(DIR_SYSTEM . 'mbooth/xml/mbooth_'. $this->id . '_' . $file . '.xml')) {
					$this->prefix = '_' . $file;
					return 'mbooth_' . $this->id . '_' . $file.'.xml';
				}
			}
		}

		return false;
	}

	private function isInstalled($code) {
		$extension_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `code` = '" . $this->db->escape($code) . "'");
		if($query->row) {
			return true;
		}else{
			return false;
		}	
	}
		
	public function getUpdate($status = '1'){
		if($status !== 0){	$status = 1; }

		$json = array();
		$this->load->language($this->route);
		$this->mboot_script_dir = DIR_SYSTEM . 'mbooth/xml/';

		$xml = new SimpleXMLElement(file_get_contents($this->mboot_script_dir . $this->mbooth));
		$current_version = $xml->version ;

		if (isset($this->request->get['mbooth'])) { 
			$mbooth = $this->request->get['mbooth']; 
		} else { 
			$mbooth = $this->mbooth; 
		}

		$customer_url = HTTP_SERVER;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE language_id = " . (int)$this->config->get('config_language_id') ); 
		$language_code = $query->row['code'];
		$ip = $this->request->server['REMOTE_ADDR'];

		$check_version_url = 'http://opencart.dreamvention.com/api/1/index.php?route=extension/check&mbooth=' . $mbooth . '&store_url=' . $customer_url . '&module_version=' . $current_version . '&language_code=' . $language_code . '&opencart_version=' . VERSION . '&ip='.$ip . '&status=' .$status;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $check_version_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$return_data = curl_exec($curl);
		$return_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if ($return_code == 200) {
			$data = simplexml_load_string($return_data);

			if ((string) $data->version == (string) $current_version || (string) $data->version <= (string) $current_version) {
				$json['success'] = $this->language->get('text_no_update');
			} elseif ((string) $data->version > (string) $current_version) {
				$json['attention']   = $this->language->get('text_new_update');

				foreach($data->updates->update as $update){
					if((string) $update->attributes()->version > (string)$current_version){
						$version = (string)$update->attributes()->version;
						$json['update'][$version] = (string) $update[0];
					}
				}
			} else {
				$json['error']   = $this->language->get('text_error_update');
			}
		} else { 
			$json['error']   =  $this->language->get('text_error_failed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }  
}
?>