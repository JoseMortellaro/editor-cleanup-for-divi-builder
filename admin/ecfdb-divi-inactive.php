<?php
defined( 'FDP_ECFDB_PLUGIN_DIR' ) || exit; //Exit if not called by FDP PRO


add_action( 'admin_notices','eos_dp_ecfdb_divi_builder_not_active' );
add_action( 'fdp_admin_notices','eos_dp_ecfdb_divi_builder_not_active' );
//Warn the user FDP is not active
function eos_dp_ecfdb_divi_builder_not_active(){
  static $called = false;
  if( $called ) return;
  $called = true;
  ?>
  <div class="notice notice-error" style="display:block !important;padding:20px">
    <?php esc_html_e( 'Editor Cleanup For Divi Builder needs that Divi Builder or Divi is installed and active!','editor-cleanup-for-divi-builder' ); ?>
    <p>
    <?php
    if( file_exists( FDP_ECFDB_PLUGINS_DIR.'/divi-builder/divi-builder.php' ) ){
      $url = wp_nonce_url(
        add_query_arg(
          array(
            'action' => 'activate',
            'plugin' => 'divi-builder/divi-builder.php',
            'plugin_status' => 'all',
            'paged' => '1'
          ),
          admin_url( 'plugins.php' )
        ),
        'activate-plugin_divi-builder/divi-builder.php'
      );
      ?>
      <a class="button" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Activate Divi Builder','editor-cleanup-for-divi-builder' ); ?></a>
      <?php
    }
    ?>
    </p>
  </div>
  <?php
}
