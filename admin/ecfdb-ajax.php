<?php
defined( 'ABSPATH' ) || exit;

foreach( array( 'outer','loading' ) as $page ){
	add_action( 'wp_ajax_eos_dp_save_divi_builder_'.$page.'_settings','eos_dp_save_divi_builder_'.$page.'_settings' );
}
//Saves activation/deactivation settings for Divi Builder outer editor
function eos_dp_save_divi_builder_outer_settings(){
	eos_dp_save_divi_builder_settings( 'outer' );
}

//Saves activation/deactivation settings for Divi Builder loading editor
function eos_dp_save_divi_builder_loading_settings(){
	eos_dp_save_divi_builder_settings( 'loading' );
}
//Callback for saving Divi Builder editor settings
function eos_dp_save_divi_builder_settings( $page ){
	eos_dp_check_intentions_and_rights( 'eos_dp_divi_builder_'.$page.'_setts' );
	if( isset( $_POST['eos_dp_divi_builder_data'] ) && !empty( $_POST['eos_dp_divi_builder_data'] ) && isset( $_POST['page'] ) && !empty( $_POST['page'] ) ){
		$opts = eos_dp_get_option( 'fdp_divi_builder' );
		$opts[sanitize_key( $_POST['page'] )] = array_filter( explode( ',',sanitize_text_field( $_POST['eos_dp_divi_builder_data'] ) ) );
		eos_dp_update_option( 'fdp_divi_builder',$opts,false );
		echo 1;
		die();
	}
	echo 0;
	die();
}
