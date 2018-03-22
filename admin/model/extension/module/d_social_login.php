<?php
/*
 *	location: admin/model
 */

class ModelExtensionModuleDSocialLogin extends Model
{
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

    /*
    *	Format the link to work with ajax requests
    */
    public function ajax($link)
    {
        return str_replace('&amp;', '&', $link);
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


    public function backup_files_by_mbooth($mbooth_xml, $action = 'install')
    {

        $zip = new ZipArchive();

        if (!file_exists(DIR_SYSTEM . 'mbooth/backup/')) {
            mkdir(DIR_SYSTEM . 'mbooth/backup/', 0777, true);
        }

        $mbooth = $this->get_files_by_mbooth(DIR_SYSTEM . 'mbooth/xml/' . $mbooth_xml);
        $files = $mbooth['files'];

        $zip->open(DIR_SYSTEM . 'mbooth/backup/' . date('Y-m-d.h-i-s') . '.' . $action . '.' . $mbooth_xml . '.v' . $mbooth['version'] . '.zip', ZipArchive::CREATE);


        foreach ($files as $file) {

            if (file_exists(DIR_ROOT . $file)) {

                if (is_file(DIR_ROOT . $file)) {
                    $zip->addFile(DIR_ROOT . $file, 'upload/' . $file);
                    $result['success'][] = $file;
                } else {
                    $result['error'][] = $file;
                }
            } else {
                $result['error'][] = $file;
            }
        }
        $zip->close();
        return $result;

    }

    public function move_dir($souce, $dest, &$result)
    {

        $files = scandir($souce);

        foreach ($files as $file) {

            if ($file == '.' || $file == '..' || $file == '.DS_Store') continue;

            if (is_dir($souce . $file)) {
                if (!file_exists($dest . $file . '/')) {
                    mkdir($dest . $file . '/', 0777, true);
                }
                $this->move_dir($souce . $file . '/', $dest . $file . '/', $result);
            } elseif (rename($souce . $file, $dest . $file)) {
                $result['success'][] = str_replace(DIR_ROOT, '', $dest . $file);
            } else {
                $result['error'][] = str_replace(DIR_ROOT, '', $dest . $file);
            }
        }

        $this->delete_dir($souce);
    }

    public function delete_dir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") $this->delete_dir($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = $this->array_merge_recursive_distinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
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
}
