<?php

//define version
if( !defined( 'TF_TB_VERSION' ) ) {
  define( 'TF_TB_VERSION', '1.0.0' );
}

//define translation string
if( !defined( 'TF_TB_STRING' ) ) {
  define( 'TF_TB_STRING', 'team-blocks' );
}

//define plugin directory path
if( !defined( 'TF_TB_DIR' ) ) {
  define( 'TF_TB_DIR', plugin_dir_url( __FILE__ ) );
}

// define store URL
if( !defined('TF_STORE_URL') ) {
  define( 'TF_STORE_URL', 'http://themeflection.com' );
}

if( !defined('TF_TB_BASE') ) {
    define('TF_TB_BASE', dirname( __FILE__ ) );
}

if( !defined('TF_TB_BASENAME') ) {
    define('TF_TB_BASENAME', plugin_basename( __FILE__ ) );
}
