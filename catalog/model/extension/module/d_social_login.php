<?php
/*
 *  location: catalog/model/module/d_social_login.php
 */

class ModelExtensionModuleDSocialLogin extends Model
{

    public function checkAuthentication($customer_id, $provider)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_authentication  WHERE  customer_id = '" . (int)$customer_id . "' AND  provider = '" . $this->db->escape($provider) . "'");

        if ($result->num_rows) {
            return true;
        } else {
            return false;
        }
    }

    public function addAuthentication($data)
    {

        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_authentication SET " .
            "customer_id = '" . (int)$data['customer_id'] . "', " .
            "provider = '" . $this->db->escape($data['provider']) . "', " .
            "identifier = MD5('" . $this->db->escape($data['identifier']) . "'), " .
            "web_site_url = '" . $this->db->escape($data['web_site_url']) . "', " .
            "profile_url = '" . $this->db->escape($data['profile_url']) . "', " .
            "photo_url = '" . $this->db->escape($data['photo_url']) . "', " .
            "display_name = '" . $this->db->escape($data['display_name']) . "', " .
            "description = '" . $this->db->escape($data['description']) . "', " .
            "first_name = '" . $this->db->escape($data['first_name']) . "', " .
            "last_name = '" . $this->db->escape($data['last_name']) . "', " .
            "gender = '" . $this->db->escape($data['gender']) . "', " .
            "language = '" . $this->db->escape($data['language']) . "', " .
            "age = '" . $this->db->escape($data['age']) . "', " .
            "birth_day = '" . $this->db->escape($data['birth_day']) . "', " .
            "birth_month = '" . $this->db->escape($data['birth_month']) . "', " .
            "birth_year = '" . $this->db->escape($data['birth_year']) . "', " .
            "email = '" . $this->db->escape($data['email']) . "', " .
            "email_verified = '" . $this->db->escape($data['email_verified']) . "', " .
            "telephone = '" . $this->db->escape($data['telephone']) . "', " .
            "address = '" . $this->db->escape($data['address']) . "', " .
            "country = '" . $this->db->escape($data['country']) . "', " .
            "region = '" . $this->db->escape($data['region']) . "', " .
            "city = '" . $this->db->escape($data['city']) . "', " .
            "zip = '" . $this->db->escape($data['zip']) . "', " .
            "date_added = NOW()");
    }

    public function addCustomer($data)
    {
        //todo test 2
        $this->load->model('account/customer');
        if ($this->model_account_customer->getTotalCustomersByEmail($data['email'])) {
            return $this->model_account_customer->getCustomerByEmail($data['email'])['customer_id'];
        } else
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET
            store_id = '" . (int)$this->config->get('config_store_id') . "',
            firstname = '" . $this->db->escape($data['firstname']) . "',
            lastname = '" . $this->db->escape($data['lastname']) . "',
            email = '" . $this->db->escape($data['email']) . "',
            telephone = '" . $this->db->escape($data['telephone']) . "',
            fax = '" . $this->db->escape($data['fax']) . "',
            password = '" . $this->db->escape(md5($data['password'])) . "',
            newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "',
            customer_group_id = '" . (int)$data['customer_group_id'] . "',
            status = '1',
            date_added = NOW()");

        $customer_id = $this->db->getLastId();
        if ($this->existAddressFields($data)) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "address SET
            customer_id = '" . (int)$customer_id . "',
            firstname = '" . $this->db->escape($data['firstname']) . "',
            lastname = '" . $this->db->escape($data['lastname']) . "',
            company = '" . $this->db->escape($data['company']) . "',
            address_1 = '" . $this->db->escape($data['address_1']) . "',
            address_2 = '" . $this->db->escape($data['address_2']) . "',
            city = '" . $this->db->escape($data['city']) . "',
            postcode = '" . $this->db->escape($data['postcode']) . "',
            country_id = '" . (int)$data['country_id'] . "',
            zone_id = '" . (int)$data['zone_id'] . "'");

            $address_id = $this->db->getLastId();

            $this->db->query("UPDATE " . DB_PREFIX . "customer SET
            address_id = '" . (int)$address_id . "'
            WHERE customer_id = '" . (int)$customer_id . "'");
        }


        if (VERSION < '3.0.0.0') {
            $this->language->load('mail/customer');
            if (!$this->config->get('config_customer_approval')) {
                $this->db->query("UPDATE " . DB_PREFIX . "customer SET
                    approved = '1'
                    WHERE customer_id = '" . (int)$customer_id . "'");
            }
        }
        $this->language->load('mail/register');

        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

        $message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

        if (!$this->config->get('config_customer_approval')) {
            $message .= $this->language->get('text_login') . "\n";
        } else {
            $message .= $this->language->get('text_approval') . "\n";
        }

        $message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
        $message .= $this->language->get('text_services') . "\n\n";
        $message .= $this->language->get('text_thanks') . "\n";
        $message .= $this->config->get('config_name');

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');

        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject($subject);
        $mail->setText($message);

        if ($data['email']) {
            $mail->setTo($data['email']);
            $mail->send();
        }

        // Send to main admin email if new account email is enabled
        if ($this->config->get('config_account_mail')) {
            $mail->setTo($this->config->get('config_email'));
            $mail->send();

            // Send to additional alert emails if new account email is enabled
            $emails = explode(',', $this->config->get('config_alert_emails'));

            foreach ($emails as $email) {
                if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }

        return $customer_id;
    }

    public function getCustomer($customer_id)
    {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_authentication WHERE customer_id = '" . (int)$customer_id . "' LIMIT 1");

        return $result->row;
    }

    public function remoteLogin($setting)
    {
        require_once(DIR_SYSTEM . 'library/d_social_login/hybrid/auth.php');
        $hybridauth = new Hybrid_Auth($setting);
        Hybrid_Auth::$logger->info('d_social_login: Start authantication.');
        $adapter = $hybridauth->authenticate($setting['provider']);
        Hybrid_Auth::$logger->info('d_social_login: Start getUserProfile.');
        // get the user profile
        $profile = $adapter->getUserProfile();
        $setting['profile'] = (array)$profile;

        Hybrid_Auth::$logger->info('d_social_login: got UserProfile.' . serialize($setting['profile']));
        $authentication_data = array(
            'provider'       => $setting['provider'],
            'identifier'     => $setting['profile']['identifier'],
            'web_site_url'   => $setting['profile']['webSiteURL'],
            'profile_url'    => $setting['profile']['profileURL'],
            'photo_url'      => $setting['profile']['photoURL'],
            'display_name'   => $setting['profile']['displayName'],
            'description'    => $setting['profile']['description'],
            'first_name'     => $setting['profile']['firstName'],
            'last_name'      => $setting['profile']['lastName'],
            'gender'         => $setting['profile']['gender'],
            'language'       => $setting['profile']['language'],
            'age'            => $setting['profile']['age'],
            'birth_day'      => $setting['profile']['birthDay'],
            'birth_month'    => $setting['profile']['birthMonth'],
            'birth_year'     => $setting['profile']['birthYear'],
            'email'          => $setting['profile']['email'],
            'email_verified' => $setting['profile']['emailVerified'],
            'telephone'      => $setting['profile']['phone'],
            'address'        => $setting['profile']['address'],
            'country'        => $setting['profile']['country'],
            'region'         => $setting['profile']['region'],
            'city'           => $setting['profile']['city'],
            'zip'            => $setting['profile']['zip']
        );

        Hybrid_Auth::$logger->info('d_social_login: set authentication_data ' . serialize($authentication_data));

        // check by identifier
        $customer_id = $this->getCustomerByIdentifier($setting['provider'], $setting['profile']['identifier']);

        if ($customer_id) {
            Hybrid_Auth::$logger->info('d_social_login: getCustomerByIdentifier success.');
            $this->login($customer_id);
            // redirect
            return 'redirect';
        }
        $customer_id = $this->getCustomerByIdentifierOld($setting['provider'], $setting['profile']['identifier']);

        // check by email
        if ($setting['profile']['email']) {
            $customer_id = $this->getCustomerByEmail($setting['profile']['email']);
            if ($customer_id) {
                Hybrid_Auth::$logger->info('d_social_login: getCustomerByEmail success.');
            }
        }

        if (!$customer_id) {
            Hybrid_Auth::$logger->info('d_social_login: no customer_id. creating customer_data');
            // prepare customer data
            $address = array();

            if (!empty($setting['profile']['address'])) {
                $address[] = $setting['profile']['address'];
            }

            if (!empty($setting['profile']['region'])) {
                $address[] = $setting['profile']['region'];
            }

            if (!empty($setting['profile']['country'])) {
                $address[] = $setting['profile']['country'];
            }

            $customer_data = array(
                'email'             => $setting['profile']['email'],
                'firstname'         => $setting['profile']['firstName'],
                'lastname'          => $setting['profile']['lastName'],
                'telephone'         => $setting['profile']['phone'],
                'fax'               => false,
                'newsletter'        => $setting['newsletter'],
                'customer_group_id' => (isset($setting['customer_group'])) ? $setting['customer_group'] : '1',
                'company'           => false,
                'address_1'         => ($address ? implode(', ', $address) : false),
                'address_2'         => false,
                'city'              => $setting['profile']['city'],
                'postcode'          => $setting['profile']['zip'],
                'country_id'        => $this->getCountryIdByName($setting['profile']['country']),
                'zone_id'           => $this->getZoneIdByName($setting['profile']['region']),
                'password'          => ''
            );

            Hybrid_Auth::$logger->info('d_social_login: set customer_data ' . serialize($customer_data));

            //check if form required
            $form = false;
            foreach ($setting['fields'] as $field) {
                if ($field['enabled']) {
                    //checking if fields required for input
                    $form = true;
                    break;
                }
            }

            if (!$form) {
                Hybrid_Auth::$logger->info('d_social_login: adding customer with customer_data');
                $customer_data['password'] = $this->generateNewPassword();
                $customer_id = $this->addCustomer($customer_data);
            } else {
                Hybrid_Auth::$logger->info('d_social_login: need to use form');
                return array('customer_data' => $customer_data, 'authentication_data' => $authentication_data);
            }
        }
        if ($customer_id) {
            Hybrid_Auth::$logger->info('d_social_login: customer_id found');
            $authentication_data['customer_id'] = (int)$customer_id;

            $this->model_extension_module_d_social_login->addAuthentication($authentication_data);
            Hybrid_Auth::$logger->info('d_social_login: addAuthentication');
            // login
            $this->login($customer_id);

            // redirect
            return 'redirect';
        }

    }

    public function getCustomerByIdentifier($provider, $identifier)
    {
        $result = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer_authentication WHERE provider = '" . $this->db->escape($provider) . "' AND identifier = MD5('" . $this->db->escape($identifier) . "') LIMIT 1");

        if ($result->num_rows) {
            return (int)$result->row['customer_id'];
        } else {
            return false;
        }
    }

    public function login($customer_id)
    {

        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "' LIMIT 1");

        if (!$result->num_rows) {
            return false;
        }

        $this->session->data['customer_id'] = $result->row['customer_id'];

        if ($result->row['cart'] && is_string($result->row['cart'])) {
            $cart = unserialize($result->row['cart']);

            foreach ($cart as $key => $value) {
                if (!array_key_exists($key, $this->session->data['cart'])) {
                    $this->session->data['cart'][$key] = $value;
                } else {
                    $this->session->data['cart'][$key] += $value;
                }
            }
        }

        if ($result->row['wishlist'] && is_string($result->row['wishlist'])) {
            if (!isset($this->session->data['wishlist'])) {
                $this->session->data['wishlist'] = array();
            }

            $wishlist = unserialize($result->row['wishlist']);

            foreach ($wishlist as $product_id) {
                if (!in_array($product_id, $this->session->data['wishlist'])) {
                    $this->session->data['wishlist'][] = $product_id;
                }
            }
        }

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$result->row['customer_id'] . "'");

        return true;
    }

    public function getCustomerByIdentifierOld($provider, $identifier)
    {
        $query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer' ORDER BY ORDINAL_POSITION");
        $result = $query->rows;
        $columns = array();
        foreach ($result as $column) {
            $columns[] = $column['COLUMN_NAME'];
        }

        if (in_array(strtolower($provider) . '_id', $columns)) {
            $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE `" . strtolower($provider) . "_id` = '" . $this->db->escape($identifier) . "'");

            if ($result->num_rows) {
                return (int)$result->row['customer_id'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getCustomerByEmail($email)
    {
        $result = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' LIMIT 1");

        if ($result->num_rows) {
            return (int)$result->row['customer_id'];
        } else {
            return false;
        }
    }

    public function getCountryIdByName($country)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE LOWER(name) LIKE '" . $this->db->escape(utf8_strtolower($country)) . "' OR iso_code_2 LIKE '" . $this->db->escape($country) . "' OR iso_code_3 LIKE '" . $this->db->escape($country) . "' LIMIT 1");

        if ($query->num_rows) {
            return $query->row['country_id'];
        } else {
            return false;
        }
    }

    public function getZoneIdByName($zone)
    {
        $query = $this->db->query("SELECT zone_id FROM " . DB_PREFIX . "zone WHERE LOWER(name) LIKE '" . $this->db->escape(utf8_strtolower($zone)) . "' OR code LIKE '" . $this->db->escape($zone) . "' LIMIT 1");

        if ($query->num_rows) {
            return $query->row['zone_id'];
        } else {
            return false;
        }
    }

    public function generateNewPassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= substr($chars, $index, 1);
        }

        return $result;
    }


    public function existAddressFields($data)
    {
        // for some reasons auth give false for field
        //for aqc 6.6.3 if only one will not be input all address is wrong
        $condition = $this->db->escape($data['address_1']) != ''
            && $this->db->escape($data['address_1'])
            && ($this->db->escape($data['address_2']) != ''
                && $this->db->escape($data['address_2']))
            && ($this->db->escape($data['city']) != ''
                && $this->db->escape($data['city']))
            && ($data['country_id'] != '' && $data['country_id'])
            && ($data['zone_id'] != '' && $data['zone_id']);
        return ($condition) ? true : false;

    }

    public function getPreloader($pre_loader_code = 'clip-rotate')
    {
        return '<div class="la-ball-clip-rotate"><div></div></div>';
    }

    public function validateEmail($email)
    {
        if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
            return true;
        } else {
            return false;
        }
    }

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
                $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'];
            } else {
                $url .= $_SERVER['REQUEST_URI'];
            }
        } else {
            $url .= $_SERVER['PHP_SELF'];
        }

        // return current url
        return $url;
    }

    public function prepareDataRegistrationFields($data, $fields)
    {
        $keys = array_keys($data);
        foreach ($fields as $field) {
            if (!in_array($field['id'], $keys)) {
                $data[$field['id']] = '';
            }
        }
        return $data;
    }
}
