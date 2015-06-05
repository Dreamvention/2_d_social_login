<?php
/*
 * 	d_social_login
 *	dreamvention.com || Live fix
 */

$_REQUEST['hauth_done'] = 'Live';

require_once( "system/library/Hybrid/Auth.php" );
require_once( "system/library/Hybrid/Endpoint.php" );
Hybrid_Endpoint::process();