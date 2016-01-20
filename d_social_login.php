<?php
/*
 * 	d_social_login
 *	dreamvention.com
 */

require_once( "system/library/Hybrid/Auth.php" );
require_once( "system/library/Hybrid/Endpoint.php" );
if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done']))
{
    Hybrid_Endpoint::process();
}