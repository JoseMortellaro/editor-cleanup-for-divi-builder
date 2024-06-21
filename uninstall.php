<?php
if( !defined( 'WP_UNINSTALL_PLUGIN') ){
    die;
}
delete_site_option( 'fdp_divi_builder' );
