<?php
namespace TFTeamBlocks\Pages;

class Init {

	function __construct() {

		add_action('admin_menu', array( $this,'register_menu') );
		add_action('activated_plugin', array( &$this,'tf_redirect') );
		$active = get_option('tf-members-activated');
		if( !isset( $active ) || '' === $active ) {
  			add_action( 'activated_plugin', array( $this,'tf_redirect' ) );
  			update_option('tf-members-activated', 'active');
		}

	} // end constructor


	function register_menu() {
		add_submenu_page( 'edit.php?post_type=tf_members', 'About', 'About', 'manage_options', 'tf-dashboard', array( $this,'tf_dashboard') );
    add_submenu_page( 'edit.php?post_type=tf_members', 'Addons', 'Addons', 'manage_options', 'tf-addons', array( $this,'tf_addons') );
		add_submenu_page( 'edit.php?post_type=tf_members', 'License', 'License', 'manage_options', 'tf-license', array( $this,'tf_license') );
	}

	 function tf_redirect($plugin) {
		 if( $plugin == TF_TB_BASE ) {
			 exit(wp_redirect(admin_url('edit.php?post_type=tf_members&page=tf-dashboard')));
		 }
	 }

	function tf_dashboard() {
		include_once( 'dashboard.php'  );
	}

	function tf_addons() {
		include_once( 'addons.php'  );
	}

  function tf_license() {
		include_once( 'license.php'  );
    License::hooks();
    License::license_page();
	}

}
