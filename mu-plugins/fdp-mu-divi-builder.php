<?php
/*
  Plugin Name: Editor Cleanup For Divi Builder [ecfdb]
  Description: mu-plugin automatically installed by Editor CLeanup For Divi Builder
  Version: 0.0.2
  Plugin URI: https://freesoul-deactivate-plugins.com/
  Author: Jose Mortellaro
  Author URI: https://josemortellaro.com/
  License: GPLv2
*/

defined( 'ABSPATH' ) || exit; // Exit if accessed directly
define( 'FDP_ECFDB_MU_VERSION','0.0.2' );

if( isset( $_GET['et_fb'] ) && 1 === absint( $_GET['et_fb'] ) ){
  add_filter( 'fdp_frontend_plugins',function( $plugins ){
    return eos_dp_ecfdb_plugins( $plugins,'outer' );
  } );
}

add_filter( 'fdp_ajax_plugins',function( $plugins ){
  $divi_builder_actions = array(
    'et_fb_update_builder_assets',
    'et_pb_preview--et_pb_preview_nonce--iframe_id--is_fb_preview--shortcode'
  );
  if( isset( $_REQUEST['action'] ) && in_array( sanitize_text_field( $_REQUEST['action'] ),$divi_builder_actions ) ){
    return eos_dp_ecfdb_plugins( $plugins,'loading' );
  }
  return $plugins;
} );

add_filter( 'fdp_ajax_plugins',function( $plugins ){
  if( isset( $_REQUEST['action'] ) && in_array( sanitize_text_field( $_REQUEST['action'] ),array( 'eos_dp_save_divi_builder_outer_settings','eos_dp_save_divi_builder_loading_settings' ) ) ){
    return in_array( 'divi-builder/divi-builder.php',$plugins ) ? array_merge( array( 'divi-builder/divi-builder.php' ),fdp_ecfdb_plugins( $plugins ) ) : fdp_ecfdb_plugins( $plugins );
  }
  return $plugins;
} );

function eos_dp_ecfdb_plugins( $plugins,$page ){
  $opts = eos_dp_get_option( 'fdp_divi_builder' );
  $divi_builder_plugins = isset( $opts[$page] ) ? $opts[$page] : array();
  $fdp_plugins = fdp_ecfdb_plugins( $plugins );
  $divi_builder_plugins = $divi_builder_plugins && is_array( $divi_builder_plugins ) ? array_merge( $divi_builder_plugins,$fdp_plugins ) : $fdp_plugins;
  foreach( $divi_builder_plugins as $plugin ){
    if( in_array( $plugin,$plugins ) || in_array( $plugin,$fdp_plugins ) ){
      unset( $plugins[array_search( $plugin,$plugins )] );
    }
  }
  return array_values( $plugins );
}

function fdp_ecfdb_plugins( $plugins ){
  $arr = array(
    'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php',
    'editor-cleanup-for-divi-builder/editor-cleanup-for-divi-builder.php'
  );
  if( in_array( 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php',$plugins ) ){
    $arr[] = 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php';
  }
  return $arr;
}
