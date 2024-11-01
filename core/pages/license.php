<?php
namespace TFTeamBlocks\Pages;

class License {

  static function hooks() {

      if ( isset($_GET['page']) && $_GET['page'] === 'tf-license' ) {
        add_action('admin_init', array(__CLASS__, 'tf_license_check'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
        add_action( 'admin_footer', array(__CLASS__, 'license_ajax' ));
        add_action('wp_ajax_tf_members_save_licenses', array(__CLASS__, 'save_licenses'));
        add_action('wp_ajax_tf_members_deactivate_licenses', array(__CLASS__, 'deactivate_license'));
      }
  }

  static function license_page() {
    ?>
    <div class="wrap">
      <h2><?php _e('Addons Licenses'); ?></h2>
      <form method="post" action="options.php">

        <?php settings_fields('tf_members_license'); ?>

        <table class="form-table">
          <tbody>
            <?php do_action( 'tf_members_license_row' ); ?>
          </tbody>
        </table>
        <?php //submit_button(); ?>
        <a id="lic-subm" style="display: inline-block;margin-top: 30px;padding: 5px 20px;background: rgb(43, 162, 13);border-radius: 3px;color: #fff;cursor: pointer">Save</a>
      </form>
    <?php
  }

  static function register_settings() {
    register_setting('tf_members_license', 'tf_members_license_key', array( __CLASS__, 'sant_license' ) );
  }

  static function sant_license( $new ) {
    $old = get_option( 'tf_members_license_key' );
    if( $old && $old != $new ) {
      delete_option( 'tf_members_license_status' );
    }
    return $new;
  }

  static function activate_license($name, $license, $addon_key) {

      $api_params = array(
        'edd_action'=> 'activate_license',
        'license'   => $license,
        'item_name' => urlencode( $name ),
        'url'       => home_url()
      );

      $response = wp_remote_post( TF_STORE_URL, array(
        'timeout'   => 15,
        'sslverify' => false,
        'body'      => $api_params
      ) );

      if ( is_wp_error( $response ) )
        return false;

      $license_data = json_decode( wp_remote_retrieve_body( $response ) );

      update_option( $addon_key, $license_data->license );

  }

  static function check_license($addon_key, $name) {
      $store_url = 'http://themeflection.com';
      $item_name = $name;
      $license = get_option( $addon_key );
      $api_params = array(
          'edd_action' => 'check_license',
          'license' => $license,
          'item_name' => urlencode( $item_name )
      );
      $response = wp_remote_get( add_query_arg( $api_params, $store_url ), array( 'timeout' => 15, 'sslverify' => false ) );
      if ( is_wp_error( $response ) )
          return false;
      $license_data = json_decode( wp_remote_retrieve_body( $response ) );
      $addon_key = str_replace( '_key', '_status', $addon_key );
      if( $license_data->license == 'expired' ) {
          update_option( $addon_key, 'expired' );
      } elseif( $license_data->license == 'invalid' ) {
          update_option( $addon_key, 'invalid' );
      } elseif( $license_data->license == 'inactive' ) {
          update_option( $addon_key, 'inactive' );
      }

      self::activate_license($name, $license, $addon_key);
  }

  static function license_check() {
    $check = false;
    $addons = array();
    $auto = get_option( 'tfmb_customizer_license_key' );
    $data = array(
        'customizer' => array(
           'name' => 'Team Members Customizer Addon',
           'key'  => 'tfmb_customizer_license_key'
        )
    );

    if( get_option( 'tfmb_customizer_license_key' ) ) {
       $check = true;
       if( $iconizer ) {
          array_push( $addons, 'customizer' );
       }
    }

    if( $check ) {
      foreach( $addons as $addon ) {
        $addon_key = $data[$addon]['key'];
        $name = $data[$addon]['name'];
        self::check_license($addon_key, $name);
      }
    }

  }

  static function save_licenses() {
      $data = isset($_POST['data']) ? $_POST['data'] : '';

      if( $data ) {
        foreach( $data as $addon ) {
          update_option( sanitize_text($addon['key']), sanitize_text($addon['val']) );
        }
      }

      wp_die();
  }

  static function license_ajax() {
    ?>
      <script type="text/javascript">
         jQuery(document).ready(function($){
            $data = {};
            $('#lic-subm').on('click', function(){
                $('.regular-text').each(function(){
                   var $this = $(this);
                   var $val = $this.val();
                   var $key = $this.attr('name');
                   $data[$key] = {
                    val: $val,
                    key: $key
                   }
                });
                $('#lic-subm').text('Saving...');
                var data = {
                  'action': 'tf_members_save_licenses',
                  'data': $data
                }
                jQuery.post(ajaxurl, data, function(response) {
                  location.reload(true);
                })
            });
         });
      </script>
    <?php
  }
}
