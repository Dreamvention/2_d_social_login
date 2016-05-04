<?php
/*
 *  location: catalog/model/module/d_social_login.php
 */

class ModelModuleDSocialLogin extends Model {

    public function getCustomerByIdentifier($provider, $identifier) {
        $result = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer_authentication WHERE provider = '" . $this->db->escape($provider) . "' AND identifier = MD5('" . $this->db->escape($identifier) . "') LIMIT 1");

        if ($result->num_rows) {
            return (int) $result->row['customer_id'];
        } else {
            return false;
        }
    }

    public function getCustomerByIdentifierOld($provider, $identifier) {
        $query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND TABLE_NAME = '" . DB_PREFIX . "customer' ORDER BY ORDINAL_POSITION"); 
        $result = $query->rows; 
        $columns = array();
        foreach($result as $column){
         $columns[] = $column['COLUMN_NAME'];
        }

        if(in_array(strtolower($provider).'_id', $columns)){
            $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE `".strtolower($provider)."_id` = '" . $this->db->escape($identifier) . "'");
            
            if ($result->num_rows) {
                return (int) $result->row['customer_id'];
            } else {
                return false;
            }
        }else{
            return false;
        } 
    }

    public function getCustomerByEmail($email) {
        $result = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' LIMIT 1");

        if ($result->num_rows) {
            return (int) $result->row['customer_id'];
        } else {
            return false;
        }
    }

     public function checkAuthentication($customer_id, $provider ) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_authentication  WHERE  customer_id = '" . (int) $customer_id . "' AND  provider = '" . $this->db->escape($provider) . "'");

        if ($result->num_rows) {
            return  true;
        } else {
            return false;
        }
    }
    public function login($customer_id) {

        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "' LIMIT 1");

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

    public function addAuthentication($data) {

       $this->db->query("INSERT INTO " . DB_PREFIX . "customer_authentication SET ".
            "customer_id = '" . (int) $data['customer_id'] . "', ".
            "provider = '" . $this->db->escape($data['provider']) . "', ".
            "identifier = MD5('" . $this->db->escape($data['identifier']). "'), ".
            "web_site_url = '" . $this->db->escape($data['web_site_url']) . "', ".
            "profile_url = '" . $this->db->escape($data['profile_url']) . "', ".
            "photo_url = '" . $this->db->escape($data['photo_url']) . "', ".
            "display_name = '" . $this->db->escape($data['display_name']) . "', ".
            "description = '" . $this->db->escape($data['description']) . "', ".
            "first_name = '" . $this->db->escape($data['first_name']) . "', ".
            "last_name = '" . $this->db->escape($data['last_name']) . "', ".
            "gender = '" . $this->db->escape($data['gender']) . "', ".
            "language = '" . $this->db->escape($data['language']) . "', ".
            "age = '" . $this->db->escape($data['age']) . "', ".
            "birth_day = '" . $this->db->escape($data['birth_day']) . "', ".
            "birth_month = '" . $this->db->escape($data['birth_month']) . "', ".
            "birth_year = '" . $this->db->escape($data['birth_year']) . "', ".
            "email = '" . $this->db->escape($data['email']) . "', ".
            "email_verified = '" . $this->db->escape($data['email_verified']) . "', ".
            "telephone = '" . $this->db->escape($data['telephone']) . "', ".
            "address = '" . $this->db->escape($data['address']) . "', ".
            "country = '" . $this->db->escape($data['country']) . "', ".
            "region = '" . $this->db->escape($data['region']) . "', ".
            "city = '" . $this->db->escape($data['city']) . "', ".
            "zip = '" . $this->db->escape($data['zip']) . "', ".
            "date_added = NOW()");
    }

    public function addCustomer($data) {

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

        if (!$this->config->get('config_customer_approval')) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET 
                approved = '1' 
                WHERE customer_id = '" . (int)$customer_id . "'");
        }

        $this->language->load('mail/customer');

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

    public function getCountryIdByName($country){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE LOWER(name) LIKE '" . $this->db->escape(utf8_strtolower($country)). "' OR iso_code_2 LIKE '" . $this->db->escape($country) . "' OR iso_code_3 LIKE '" . $this->db->escape($country) . "' LIMIT 1");
    
        if ($query->num_rows) {
            return $query->row['country_id'];
        } else {
            return false;
        }
    }

    public function getZoneIdByName($zone){
        $query = $this->db->query("SELECT zone_id FROM " . DB_PREFIX . "zone WHERE LOWER(name) LIKE '" . $this->db->escape(utf8_strtolower($zone)). "' OR code LIKE '" . $this->db->escape($zone) . "' LIMIT 1");

        if ($query->num_rows) {
            return $query->row['zone_id'];
        } else {
            return false;
        }
    }

    public function getCustomer($customer_id){
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_authentication WHERE customer_id = '" . (int) $customer_id . "' LIMIT 1");

        return $result->row;
    }

}