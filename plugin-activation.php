<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if( file_exists( WPMU_PLUGIN_DIR.'/fdp-mu-divi-builder.php' ) ){
  unlink( WPMU_PLUGIN_DIR.'/fdp-mu-divi-builder.php' );
}
eos_dp_ecfdb_write_file( FDP_ECFDB_PLUGIN_DIR.'/mu-plugins/fdp-mu-divi-builder.php',WPMU_PLUGIN_DIR,WPMU_PLUGIN_DIR.'/fdp-mu-divi-builder.php',true );
