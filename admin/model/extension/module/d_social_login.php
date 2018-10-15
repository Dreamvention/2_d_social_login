<?php
/*
 *	location: admin/model
 */

class ModelExtensionModuleDSocialLogin extends Model
{
    private $codename = 'd_social_login';

    public function installDatabase()
    {
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
		  `telephone` varchar(255) NOT NULL,
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

    public function uninstallDatabase()
    {
        $query = $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "customer_authentication`");
    }

    public function getStores()
    {
        $this->load->model('setting/store');
        $stores = $this->model_setting_store->getStores();
        $result = array();
        if ($stores) {
            $result[] = array(
                'store_id' => 0,
                'name'     => $this->config->get('config_name')
            );
            foreach ($stores as $store) {
                $result[] = array(
                    'store_id' => $store['store_id'],
                    'name'     => $store['name']
                );
            }
        }
        return $result;
    }

    public function checkInstallModule()
    {
        $this->load->model('extension/d_opencart_patch/extension');
        if (!$this->model_extension_d_opencart_patch_extension->isInstalled($this->codename)) {
            return false;
        }
        $this->load->model('setting/setting');
        $setting_module = $this->model_setting_setting->getSetting($this->codename);
        if (!$setting_module) {
            return false;
        }
        return true;
    }

    public function installConfig()
    {
        $this->load->model('extension/d_opencart_patch/extension');
        $this->load->model('extension/d_opencart_patch/setting');
        $this->load->model('extension/d_opencart_patch/user');
        if (!$this->model_extension_d_opencart_patch_extension->isInstalled($this->codename)) {
            $this->model_extension_d_opencart_patch_extension->install('module', $this->codename);
            $this->load->controller('extension/module/' . $this->codename . '/install');
        }
        $this->load->model('setting/setting');
        $setting_module = $this->model_extension_d_opencart_patch_setting->getSetting($this->codename);
        if (!empty($setting_module[$this->codename . '_setting'])) {
            $setting_module[$this->codename . '_status'] = 1;
            $setting_module = $this->config->get($this->codename);
            $setting_module['providers'] = $this->model_extension_module_d_social_login->loadProviders($this->codename);

            $this->model_extension_d_opencart_patch_setting->editSetting($this->codename, $setting_module);
        } else {
            $this->load->config($this->codename);
            $setting = $this->config->get($this->codename);
            $setting['providers'] = $this->model_extension_module_d_social_login->loadProviders($this->codename);

            $this->model_extension_d_opencart_patch_setting->editSetting($this->codename, array(
                $this->codename . '_setting' => $setting,
                $this->codename . '_status'  => 1
            ));

            $this->model_extension_d_opencart_patch_setting->editSetting('module_'.$this->codename, array(
                'module_'. $this->codename . '_setting' => $setting,
                'module_'. $this->codename . '_status'  => 1
            ));
        }
    }
    public function getFileContents($file)
    {
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
    public function loadProviders($id)

    {
        $providers = array();
        $dir_files = array();
        $this->scan_dir(DIR_CONFIG . $id, $dir_files);
        foreach ($dir_files as $file) {
            $provider_name = basename($file, ".php");
            $this->config->load($id . '/' . $provider_name);
            $provider = $this->config->get($id . "_" . $provider_name);
            if ($provider) {
                $providers = array_merge($providers, $provider);
            }

        }
        return $providers;
    }

    public function scan_dir($dir, &$arr_files)
    {

        if (is_dir($dir)) {
            $handle = opendir($dir);
            while ($file = readdir($handle)) {
                if ($file == '.' or $file == '..') continue;
                if (is_file($file)) $arr_files[] = "$dir/$file";
                else $this->scan_dir("$dir/$file", $arr_files);
            }
            closedir($handle);
        } else {
            $arr_files[] = $dir;
        }
    }

    public function addToLayoutFromSetup($module_id)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` (
                            `layout_id`, 
                            `code`, 
                            `position`, 
                            `sort_order`)
                          VALUES (
                              (SELECT `layout_id` FROM `" . DB_PREFIX . "layout_route` WHERE `route` LIKE '%account%' LIMIT 1), 
                              '" . $module_id. "', 
                              'content_top', 
                              0)
                        ");
        return true;
    }

}
