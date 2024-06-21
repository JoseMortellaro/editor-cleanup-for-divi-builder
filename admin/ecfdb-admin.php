<?php

defined( 'FDP_ECFDB_PLUGIN_DIR' ) || exit;

if( isset( $_GET['page'] ) && in_array( $_GET['page'],array( 'eos_dp_ecfdb_outer','eos_dp_ecfdb_loading' ) ) ){
  //Clean settings page and load scripts
  remove_all_actions( 'parse_request' );
  if( function_exists( 'eos_dp_remove_other_admin_notices' ) ){
    add_action( 'admin_init','eos_dp_remove_other_admin_notices' );
  }
  if( function_exists( 'eos_dp_scripts' ) ){
    add_action( 'admin_enqueue_scripts', 'eos_dp_scripts',999999 );
  }
  if( function_exists( 'eos_dp_dequeue_stylesheets' ) ){
    add_action( 'admin_enqueue_scripts', 'eos_dp_dequeue_stylesheets',999999 );
    add_action( 'admin_head', 'eos_dp_dequeue_stylesheets',999999 );
    add_action( 'admin_print_scripts', 'eos_dp_dequeue_stylesheets',999999 );
  }
  //Add notice to the footer
  add_action( 'fdp_after_save_button',function(){
    ?>
    <p style="margin-top:64px"><?php esc_html_e( 'Many thanks for using Editor Cleanup For Divi Builder with Freesoul Deactivate Plugins, the plugin to selectively disable all other plugins where you don\'t need them','editor-cleanup-for-divi-builder' ); ?></p>
    <?php
  } );
}

//Add warnings
function eos_dp_ecfdb_admin_notices(){
    if( version_compare( FDP_ECFDB_MU_VERSION,FDP_ECFDB_PLUGIN_VERSION,'<' ) ){
      ?>
      <div class="notiice notice-error is-dismissible" style="border-<?php echo is_rtl() ? 'right' : 'left'; ?>:4px solid #d63638;padding:10px">
        <p><?php esc_html_e( 'It looks the mu-plugin of Editor Cleanup For Divi Builder is not updated. Try deactivating Editor Cleanup For Divi Builder and then activate it again. Then refresh this page.','editor-cleanup-for-divi-builder' ); ?></p>
        <p><?php echo wp_kses( __( 'If you still see this message open a thread on the support foruum.','editor-cleanup-for-divi-builder' ),array( 'a' => array( 'href' ) ) ); ?></p>
      </div>
      <?php
    }
}

add_filter( 'fdp_pages',function( $pages ){
  //Add settings page to FDP pages
  $pages[] = 'eos_dp_ecfdb_outer';
  $pages[] = 'eos_dp_ecfdb_loading';
  return $pages;
} );

add_action( 'admin_menu',function(){
  $titles = eos_dp_ecfdb_titles();
  //Add sub menu settings page
  add_submenu_page( 'et_divi_options',esc_attr__( 'Frontend Editor Cleanup','editor-cleanup-for-divi-builder' ),esc_attr__( 'Frontend Editor Cleanup','fdp-ecfdb' ),apply_filters( 'eos_dp_settings_capability','activate_plugins' ),'eos_dp_ecfdb_outer','eos_dp_ecfdb_settings_callback',999999 );
  $n = 10;
  foreach( $titles as $k => $title ){
    add_submenu_page( null,esc_attr( $titles[$k] ),esc_attr( $titles[$k] ),apply_filters( 'eos_dp_settings_capability','activate_plugins' ),'eos_dp_ecfdb_'.$k,'eos_dp_ecfdb_'.$k.'_settings_callback',$n );
    ++$n;
  }
},9999);

//Callback function for the main settings page
function eos_dp_ecfdb_settings_callback(){
  if( isset( $_GET['page'] ) && 'eos_dp_ecfdb' === $_GET['page'] ){
    require_once FDP_ECFDB_PLUGIN_DIR.'/admin/ecfdb-index-settings.php';
  }
}
//Callback function for the outer settings page
function eos_dp_ecfdb_outer_settings_callback(){
  if( isset( $_GET['page'] ) && 'eos_dp_ecfdb_outer' === $_GET['page'] ){
    require_once FDP_ECFDB_PLUGIN_DIR.'/admin/ecfdb-outer-settings.php';
  }
}

//Callback function for the loading settings page
function eos_dp_ecfdb_loading_settings_callback(){
  if( isset( $_GET['page'] ) && 'eos_dp_ecfdb_loading' === $_GET['page'] ){
    require_once FDP_ECFDB_PLUGIN_DIR.'/admin/ecfdb-loading-settings.php';
  }
}

function eos_dp_ecfdb_inline_style( $column_count ){
  ?>
  <style id="fdp-divi-builder">
  #eos-dp-divi-builder-section #eos-dp-setts td:first-child:before{content:none !important}
  #eos-dp-setts{column-count:<?php echo esc_attr( $column_count ); ?>}
  #eos-dp-setts tr {
    -webkit-column-break-inside:avoid;
    column-break-inside:avoid
  }
  #eos-dp-setts .eos-dp-name-td {
      padding:0 10px;
      background: transparent;
      border: none;
  }
  #eos-dp-setts td .eos-dp-td-chk-wrp input {
      width: 24px;
      height: 24px;
  }
  @media screen and (max-width:1350px){
    #eos-dp-setts{column-count:<?php echo esc_attr( min( 2,$column_count ) ); ?>}
  }
  @media screen and (max-width:967px){
    #eos-dp-setts{column-count:1}
  }
  </style>
  <?php
}

//Settings page navigation
function eos_dp_ecfdb_navigation(){
  $titles = eos_dp_ecfdb_titles();
  ?>
  <nav id="fdp-divi-builder-navigation" style="margin-top:32px;margin-bottom:32px">
  <?php
  foreach( $titles as $k => $title ){ ?>
    <a id="fdp-divi-builder-<?php echo esc_attr( $k ); ?>-setts-url" class="button<?php echo isset( $_GET['page'] ) && $k === str_replace( 'eos_dp_ecfdb_','',sanitize_key( $_GET['page'] ) ) ? ' eos-active' : ''; ?>" href="<?php echo esc_attr( esc_url( admin_url( 'admin.php?page=eos_dp_ecfdb_'.esc_attr( $k ) ) ) ); ?>"><?php echo esc_html( $title ); ?></a>
  <?php } ?>
  </nav>
  <?php
}

//Array of titles for each settings page
function eos_dp_ecfdb_titles(){
  return array(
    'outer' => esc_attr__( 'Frontend Editor Cleanup','fdp-ecfdb' ),
    'loading' => esc_attr__( 'Editor Actions Cleanup','fdp-ecfdb' ),
  );
}

//It adds a settings link to the action links in the plugins page
add_filter( "plugin_action_links_editor-cleanup-for-divi-builder/editor-cleanup-for-divi-builder.php", 'eos_dp_ecfdb_plugin_add_settings_link' );
//It adds a settings link to the action links in the plugins page
function eos_dp_ecfdb_plugin_add_settings_link( $links ) {
    $settings_link = '<a class="fdp-divi-builder" href="'.admin_url( 'admin.php?page=eos_dp_ecfdb_outer' ).'">'.esc_html__( 'Settings','editor-cleanup-for-divi-builder' ).'</a>';
    array_push( $links, $settings_link );
  	return $links;
}
