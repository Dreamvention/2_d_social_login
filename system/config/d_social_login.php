<?php
$_["d_social_login"] = array(
    "base_url"            => "",
    "providers"           => array(),
    "debug_mode"          => false,
    "header"              => false,
    //system/logs/d_social_login.txt
    "debug_file"          => "d_social_login.txt",
    "title"               => "Sign in",
    "fields"              => array(
        "email"      => array("id" => "email", "enabled" => true, "required" => true, "sort_order" => 0, "type" => "email"),
        "firstname"  => array("id" => "firstname", "enabled" => true, "required" => true, "sort_order" => 1, "type" => "text"),
        "lastname"   => array("id" => "lastname", "enabled" => true, "required" => true, "sort_order" => 2, "type" => "text"),
        "telephone"  => array("id" => "telephone", "enabled" => true, "required" => true, "sort_order" => 3, "type" => "text", "mask" => "9(999) 9999-9999?9"),
        "address_1"  => array("id" => "address_1", "enabled" => true, "required" => false, "sort_order" => 4, "type" => "text"),
        "address_2"  => array("id" => "address_2", "enabled" => true, "required" => false, "sort_order" => 5, "type" => "text"),
        "city"       => array("id" => "city", "enabled" => true, "required" => false, "sort_order" => 6, "type" => "text"),
        "postcode"   => array("id" => "postcode", "enabled" => true, "required" => false, "sort_order" => 7, "type" => "text"),
        "country_id" => array("id" => "country_id", "enabled" => true, "required" => false, "sort_order" => 8, "type" => "select"),
        "zone_id"    => array("id" => "zone_id", "enabled" => true, "required" => false, "sort_order" => 9, "type" => "select"),
        "company"    => array("id" => "company", "enabled" => true, "required" => false, "sort_order" => 10, "type" => "text"),
        // "company_id" => array("id" => "company_id", "enabled" => true, "sort_order" => 11, "type" => "text"),
        // "tax_id" => array("id" => "tax_id", "enabled" => true, "sort_order" => 12, "type" => "text"),
        "password"   => array("id" => "password", "enabled" => true, "required" => false, "sort_order" => 13, "type" => "password"),
        "confirm"    => array("id" => "confirm", "enabled" => true, "required" => false, "sort_order" => 14, "type" => "password")
    ),
    "size"                => "icon",
    "sizes"               => array( "icons", "small", "medium", "large", "huge" ),
    "return_page_url"     => "",
    "customer_group"      => 1,
    "newsletter"          => 1,
    "iframe"              => 1,
    //image/catalog/d_social_login/bg.png
    "background_img"      => "catalog/d_social_login/bg.png",
    "background_img_size" => array("width" => 100, "height" => 100),
);
?>