<?php
/**
  * Plugin Name: Team Members Blocks
  * Plugin URI: http://themeflection.com/plug/team-members-blocks/
  * Version: 1.0.0
  * Author: Themeflection
  * Author URI: http://themeflection.com
  * Description: Team Members Blocks is a clean and beautiful team members building plugin that can be esily customized and integrated into any theme
  * Text Domain: tmb-string
  * Domain Path: /languages
  * License: GPL
  */
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

  require_once 'constants.php';
  require_once 'core/init.php';

  //initialize
	new TFTeamBlocks\Core\Init;
