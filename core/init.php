<?php
namespace TFTeamBlocks\Core;

class Init {

  function __construct() {
    $this->includes();
    $this->load_textdomain();
    $this->instantiate();
  }

  private function instantiate() {
    $instance = Setup::get_instance();
    if ( is_admin() ) {
      $pages = new \TFTeamBlocks\Pages\Init;
    }

  	$options = $instance->options()->get_options();
  	$prefix  = $instance->get_prefix();

   	new \TFMembersShortcode($prefix, $options);
  }

  private function load_textdomain() {
    load_plugin_textdomain( TF_TB_STRING, false, TF_TB_BASE . '/languages' );
  }

  private function includes() {
    require_once TF_TB_BASE . '/cmb2/init.php';
    require_once TF_TB_BASE . '/options/init.php';
    require_once 'setup.php';
    require_once 'update.php';
    require_once 'pages/init.php';
    require_once 'shortcode.php';
  }

  public static function get_instance() {
    if (null === self::$instance) {
        self::$instance = new self();
    }
    return self::$instance;
  }
}
