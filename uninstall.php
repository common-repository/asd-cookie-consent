<?php
if( !defined('WP_UNINSTALL_PLUGIN') ) {
	die();
}

delete_option( 'asd_cookie_consent' );
