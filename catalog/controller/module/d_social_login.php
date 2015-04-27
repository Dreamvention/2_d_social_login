<?php
class ControllerModuleDSocialLogin extends Controller {
		
	public function index($setting) {
		//echo $setting;
		if(empty($setting) ){
			$setting = $this->config->get( 'd_social_login_module');
			$setting = $setting['setting'];
		}
		//echo "<pre>"; print_r($setting ); echo "</pre>"; 
		if( empty( $setting ) )
		{
			$setting['size'] = 'icon';
			$setting['status'] = '1';
		}

		$this->session->data['d_social_login'] = $setting;

	
		$server = (isset($_SERVER['HTTPS'])) ? HTTPS_SERVER : HTTP_SERVER;

		if(!isset($setting['base_url_index'])){ $setting['base_url_index'] = false; }

		if($setting['base_url_index']){
			$setting['base_url'] = $this->url->link('module/d_social_login/hybridauth', '', 'SSL');
		}else{
			$setting['base_url'] = $server.'catalog/model/d_social_login/hybridauth.php';
		}
		
		$setting['debug_file'] = DIR_SYSTEM . "logs/d_social_login.txt";
		$this->language->load('module/d_social_login');
		$this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/styles.css');
		$this->document->addScript('catalog/view/javascript/d_social_login/spin.min.js');
			
      	$data['heading_title'] = $this->language->get('heading_title');
      	$data['button_sign_in'] = $this->language->get('button_sign_in');
      	$data['size'] = $setting['size'];
      	$data['islogged'] = $this->customer->isLogged();
      	$providers = $setting['providers'];
      	
      	$sort_order = array(); 
		foreach ($providers as $key => $value) {
			if(isset($value['sort_order'])){
      			$sort_order[$key] = $value['sort_order'];
			}
    	}
		array_multisort($sort_order, SORT_ASC, $providers);

      	$data['providers'] = $providers; 
      	foreach($providers as $key => $val) {
      		$data['providers'][$key]['heading'] = $this->language->get('text_sign_in_with_'.$val['id']);
      	}

		

		unset($this->session->data['HA::CONFIG']);
		unset($this->session->data['HA::STORE']);

		$this->session->data['d_social_login']['return_url'] = $this->getCurrentUrl();

		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/d_social_login.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/d_social_login.tpl', $data);
		} else {
			return $this->load->view('default/template/module/d_social_login.tpl', $data);
		}
	}

	public function hybridauth(){

		$this->load->model('d_social_login/hybridauth_model');
		return $this->model_d_social_login_hybridauth_model->process();

	}

	public function provider_login(){
	
		$setting = $this->session->data['d_social_login'];

		$server = (isset($_SERVER['HTTPS'])) ? HTTPS_SERVER : HTTP_SERVER;

		if(!isset($setting['base_url_index'])){ $setting['base_url_index'] = false; }

		if($setting['base_url_index']){
			$setting['base_url'] = $this->url->link('module/d_social_login/hybridauth', '', 'SSL');
		}else{
			$setting['base_url'] = $server.'catalog/model/d_social_login/hybridauth.php';
		}
		$setting['debug_file'] = DIR_SYSTEM . "logs/d_social_login.txt";
		if(isset($this->request->get['provider'])){
			$this->session->data['provider'] = $this->request->get['provider'];
		}
			$profile = array();
			require_once( DIR_APPLICATION."model/d_social_login/Hybrid/Auth.php" );

			 try{

			  	 $hybridauth = new Hybrid_Auth( $setting );

			  	 $provider = $hybridauth->authenticate( $this->session->data['provider']);  
			 
			  	//get the user profile 
			  	 $profile = $provider->getUserProfile();
			  
			 	 $this->register($this->session->data['provider'], (array)$profile);

			  	 $provider->logout(); 
			  }
			  catch( Exception $e ){  

			  	switch( $e->getCode() ){ 
			  	  case 0 : $json['error'] =  "Unspecified error."; break;
			  	  case 1 : $json['error'] = "Hybriauth configuration error."; break;
			  	  case 2 : $json['error'] = "Provider not properly configured."; break;
			  	  case 3 : $json['error'] = "Unknown or disabled provider."; break;
			  	  case 4 : $json['error'] = "Missing provider application credentials."; break;
			  	  case 5 : $json['error'] = "Authentification failed. " 
			  	              . "The user has canceled the authentication or the provider refused the connection."; 
			  	           break;
			  	  case 6 : $json['error'] = "User profile request failed. Most likely the user is not connected "
			  	              . "to the provider and he should authenticate again."; 
			  	           $provider->logout(); 
			  	           
			  	  case 7 : $json['error'] = "User not connected to the provider."; 
			  	           $provider->logout();
			  	  case 8 : $json['error'] = "Provider does not support this feature."; break;
			  	}
			  
			  	//echo "Ooophs, we got an error: " . $e->getMessage();
			  	$this->session->data['success'] = $json['error']." Ooophs, we got an error: " . $e->getMessage();
			  	$this->response->redirect(urldecode($this->url->link('account/login', '')));
			  }
			
		
	}
/*

identifier
photoURL
firstName
lastName
email
phone
country
city
region
zip
birthDay
birthMonth
birthYear

*/
	public function register($provider,$profile){
		$setting = $this->session->data['d_social_login'];
		$fields = $setting['fields'];
		
		$popup = false;
      	

		if($profile){

			//creating data				
			if($profile['country']){
				$country_id= $this->getCountryIdByName($profile['country']);
			}elseif($profile['region']){
				$country_id= $this->getCountryIdByName($profile['region']);
			}else{
				$country_id= $this->config->get('config_country_id');
			}
			

			$data = array(
							    'firstname' => $profile['firstName'],
							    'lastname' => $profile['lastName'],
							    'email' => $profile['email'],
							    'telephone' => $profile['phone'],
							    'fax' => '',
							    'company' => '',
							    'customer_group_id' => (isset($setting['customer_group']))? $setting['customer_group'] : '1',
							    'company_id' => '',
							    'tax_id' => '',
							    'address_1' => $profile['address'],
							    'address_2' => '',
							    'city' => $profile['city'],
							    'postcode' => $profile['zip'],
							    'country_id' => $country_id,
							    'zone_id' => '',
							    'password' => '',
							    'confirm' => '',
							    'newsletter' => '1',
							    'agree' => '1',
							    'provider' => $provider,
							    'id' => $profile['identifier']
						);
			//checking if customer already created with this provider
			if($profile['identifier'] != ''){
				$customer = $this->get_customer_by_provider_id($provider,$profile['identifier']);
			}else{
				$customer = false;
			}
			
			
			if($customer){
				$this->customer->login($customer['email'], '', true);
				$this->response->redirect(urldecode($this->session->data['d_social_login']['return_url']));
			}else{
			
			//checking if email sent
				if(!$profile['email']){
					$popup = true;
				}else{
					$customer = $this->getCustomerByEmail($profile['email']);
					if(!$customer){
						foreach ($fields as $field){
				      		if($field['enabled']) {
				      			//checking if fields required for input
				      			$popup = true;
				      			break;
				      		}
				      	}
				    }
				}

				if(!$popup){

					if(!$customer){
						$this->load->model('account/customer');
						$password = $this->generate_password();
						$data['password'] = $password;
						$data['confirm'] = $password;
						$this->model_account_customer->addCustomer($data);
						$this->customer->login($profile['email'], $password);
						$this->provider_edit_customer($this->customer->getId(), $data);
						$this->response->redirect($this->return_to_page($setting['return_page'], $this->session->data['d_social_login']['return_url']));
					}else{
						$this->customer->login($profile['email'], '', true);
						$data = array(
							    'provider' => $provider,
							    'id' => $profile['identifier']
						);
						$this->provider_edit_customer($this->customer->getId(), $data);
					
						$this->response->redirect($this->session->data['d_social_login']['return_url']);
					}
				}else{
						$this->language->load('module/d_social_login');
						$sort_order = array(); 
						foreach ($fields as $key => $value) {
							if(isset($value['sort_order'])){
				      			$sort_order[$key] = $value['sort_order'];
							}
				    	}
						array_multisort($sort_order, SORT_ASC, $fields);
						$data['fields'] = $fields;
						$data['background_img'] = $setting['background_img'];
						$data['background_color'] = $setting['providers'][ucfirst($provider)]['background_color'];

						$this->load->model('localisation/country');
						$data['countries'] = $this->model_localisation_country->getCountries();

						$this->session->data['profile'] = $profile;
						$data['profile'] = $profile;
						$data['provider'] = $provider;
						$data['profile_value'] = $data;
						$data['button_sign_in_mail'] = $this->language->get('button_sign_in_mail');
						$data['button_sign_in'] = $this->language->get('button_sign_in');
						$data['text_none'] = $this->language->get('text_none');
						$data['text_select'] = $this->language->get('text_select');
						$data['text_email'] = $this->language->get('text_email');
						$data['text_firstname'] = $this->language->get('text_firstname');
						$data['text_lastname'] = $this->language->get('text_lastname');
						$data['text_phone'] = $this->language->get('text_phone');
						$data['text_address_1'] = $this->language->get('text_address_1');
						$data['text_address_2'] = $this->language->get('text_address_1');
						$data['text_city'] = $this->language->get('text_city');
						$data['text_postcode'] = $this->language->get('text_postcode');
						$data['text_country_id'] = $this->language->get('text_country_id');
						$data['text_zone_id'] = $this->language->get('text_zone_id');
						$data['text_company'] = $this->language->get('text_company');
						$data['text_company_id'] = $this->language->get('text_company_id');
						$data['text_tax_id'] = $this->language->get('text_tax_id');
						$data['text_password'] = $this->language->get('text_password');
						$data['text_confirm'] = $this->language->get('text_confirm');
						$data['text_email_intro'] = $this->language->get('text_email_intro');

						if(
							isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 )
						|| 	isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
						){
							$data['http'] = HTTPS_SERVER;
						}
						else {
							$data['http'] = HTTP_SERVER;
						}

						
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_social_login/email.tpl')) {
							 $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/d_social_login/email.tpl', $data));
						} else {
							 $this->response->setOutput($this->load->view('default/template/d_social_login/email.tpl', $data));
						}
					
				}
			}
		}
	}

	public function create_with_email(){
		$this->language->load('module/d_social_login');
		$json = array();
		$profile = array_merge($this->session->data['profile'], $this->request->post);
		$error = false;

		if($this->validate_email($profile['email'])){
			$customer = $this->getCustomerByEmail($profile['email']);
			if($customer){
				$error = $this->language->get('error_email_taken');
			}
		}else{
			$error = $this->language->get('error_email_incorrect');
			$json['empty'][] = 'email';
		}

		$setting = $this->session->data['d_social_login'];
		$fields = $setting['fields'];

		foreach($fields as $field) {
			if($field['enabled']){
			  if($field['id'] == 'confirm'){
			  	if(($profile['password'] != $profile['confirm'])){
			  		$error = $this->language->get('error_password_and_confirm_different');
			  		$json['empty'][] = 'confirm';
			  	}
			  }
			   if ($this->request->post[$field['id']] == "") {
			   	$error = $this->language->get('error_fill_all_fields');
			   	$json['empty'][] = $field['id'];  
			  }
			}
		}


		if(!$error){

				$json['msg'] = '';
				$this->load->model('account/customer');

				if($fields['firstname']['enabled']){
					$firstname = $profile['firstname'];
				}else{
					$firstname = $profile['firstName'];
				}

				if($fields['lastname']['enabled']){
					$lastname = $profile['lastname'];
				}else{
					$lastname = $profile['lastName'];
				}

				if($fields['country_id']['enabled']){
					$country_id = $profile['country_id'];
				}else{
					if($profile['country']){
						$country_id= $this->getCountryIdByName($profile['country']);
					}elseif($profile['region']){
						$country_id= $this->getCountryIdByName($profile['region']);
					}else{
						$country_id= $this->config->get('config_country_id');
					}
				}

				if($fields['postcode']['enabled']){
					$postcode = $profile['postcode'];
				}else{
					$postcode = $profile['zip'];
				}

				if($fields['zone_id']['enabled']){
					$zone_id = $profile['zone_id'];
				}else{
					$zone_id = '';
				}

				if($fields['address_1']['enabled']){
					$address_1 = $profile['address_1'];
				}else{
					$address_1 = $profile['address'];
				}

				if($fields['address_2']['enabled']){
					$address_2 = $profile['address_2'];
				}else{
					$address_2 = '';
				}

				if($fields['company']['enabled']){
					$json['msg'] .= $profile['company'];
					$company = $profile['company'];
				}else{
					$company = '';
				}
				if($fields['company_id']['enabled']){
					$json['msg'] .= $profile['company_id'];
					$company_id = $profile['company_id'];
				}else{
					$company_id = '';
				}
				if($fields['tax_id']['enabled']){
					$json['msg'] .= $profile['tax_id'];
					$tax_id = $profile['tax_id'];
				}else{
					$tax_id = '';
				}

				if($fields['password']['enabled']){
					$password = $profile['password'];
				}else{
					$password = $this->generate_password();
				}
					
				$data = array(
					    'firstname' => $firstname,
					    'lastname' => $lastname,
					    'email' => $profile['email'],
					    'telephone' => $profile['phone'],
					    'fax' => '',
					    'company' => $company,
					    'customer_group_id' => (isset($setting['customer_group']))? $setting['customer_group'] : '1',
					    'company_id' => $company_id,
					    'tax_id' => $tax_id,
					    'address_1' => $address_1,
					    'address_2' => $address_2,
					    'city' => $profile['city'],
					    'postcode' => $postcode,
					    'country_id' => $country_id,
					    'zone_id' => $zone_id,
					    'password' => $password,
					    'confirm' => $password,
					    'newsletter' => '1',
					    'agree' => '1',
					    'provider' => $this->request->get['provider'],
					    'id' => $profile['identifier']
				);
				$json['data'] = $data;
				$this->model_account_customer->addCustomer($data);
				$this->customer->login($profile['email'], $password, true);
				$this->provider_edit_customer($this->customer->getId(), $data);
				$json['url'] = $this->return_to_page($setting['return_page'], $this->session->data['d_social_login']['return_url']);
		}else{
			$json['error'] = $error; 
		}
		$this->response->setOutput(json_encode($json));	
	}

	private function provider_edit_customer($customer_id, $data){

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET `".$data['provider']."_id` = '" . $this->db->escape($data['id']) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		
	}

	private function get_customer_by_provider_id($provider, $id){
		if($provider || $id){
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE `".$provider."_id` = '" . $this->db->escape($id) . "'");
			return $query->row;
		}else{
			return false;
		}
	}

	public function validate_email($email){
		if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) { 
			return true; 
		}else{
			return false;
		}
	}

	private function getCountryIdByName($country_name){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE LOWER(name) LIKE '" . $this->db->escape(utf8_strtolower($country_name)). "' AND status = '1'");
		
		$country = $query->row;
		if(isset($country['country_id'])){
			return $country['country_id'];
		}else{
			return $this->config->get('config_country_id');
		}
		
	}

	private function generate_password($length = 8) {
	    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    $count = mb_strlen($chars);

	    for ($i = 0, $result = ''; $i < $length; $i++) {
	        $index = rand(0, $count - 1);
	        $result .= mb_substr($chars, $index, 1);
	    }

	    return $result;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		
		return $query->row;
	}

	public function country() {
		$json = array();
		
		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setOutput(json_encode($json));
	}

	public function return_to_page($return_url, $viewed_url){
		if($return_url == 'viewed'){
			$result = $viewed_url;
		}

		if($return_url == 'address'){
			$address_id = $this->customer->getAddressId();
			if($address_id){
				$result = $this->url->link('account/address/update', 'address_id='.$address_id, 'SSL');
			}else{
				$result = $viewed_url;
			}
		}

		if($return_url == 'home'){
			$result = $this->url->link('common/home');
		}

		if($return_url == 'account'){
			$result = $this->url->link('account/account', '', 'SSL');
		}

		return htmlspecialchars_decode($result);

	}


	public static function getCurrentUrl( $request_uri = true ) 
	{
		if(
			isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 )
		|| 	isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
		){
			$protocol = 'https://';
		}
		else {
			$protocol = 'http://';
		}

		$url = $protocol . $_SERVER['HTTP_HOST'];

		if( isset( $_SERVER['SERVER_PORT'] ) && strpos( $url, ':'.$_SERVER['SERVER_PORT'] ) === FALSE ) {
			$url .= ($protocol === 'http://' && $_SERVER['SERVER_PORT'] != 80 && !isset( $_SERVER['HTTP_X_FORWARDED_PROTO']))
				|| ($protocol === 'https://' && $_SERVER['SERVER_PORT'] != 443 && !isset( $_SERVER['HTTP_X_FORWARDED_PROTO']))
				? ':' . $_SERVER['SERVER_PORT'] 
				: '';
		}

		if( $request_uri ){
			$url .= $_SERVER['REQUEST_URI'];
		}
		else{
			$url .= $_SERVER['PHP_SELF'];
		}

		// return current url
		return $url;
	}
}
?>