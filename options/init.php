<?php

Class TMBOptions {

  private $prefix;
  private static $instance;

  function __construct($prefix) {
    $this->prefix = $prefix;
    self::$instance = $this;

    $this->includes();
  }

  function init() {
    $prefix = $this->prefix;

    \TMBOptions\Members::init($prefix);
    \TMBOptions\General::init($prefix);
    \TMBOptions\MembersStyle::init($prefix);

    do_action('tf_members_options_init', $prefix);
  }

  function get_options() {
    $members       = \TMBOptions\Members::get_options();
    $member_styles = \TMBOptions\MembersStyle::get_options();
    $general       = \TMBOptions\General::get_options();

    $options = array(
      'members'       => $members,
      'member_styles' => $member_styles,
      'general'       => $general,
    );
    $options = apply_filters('tf_members_get_options', $options );

    $result = array();
    foreach ( $options as $group => $option ) {
      $goup_ops = array_map(array($this, 'map_optons'), $option);

      $result = array_merge($result, $goup_ops);
    }

    $result = array_column($result, null, 'id');
    return apply_filters( 'tf_members_options_result', $result );
  }

  private function map_optons($options) {
    $result = array(
      'id'           => $options['id'],
      'selector'     => isset( $options['selector'] ) ? $options['selector'] : false,
      'styles'       => isset( $options['styles'] ) ? $options['styles'] : false,
      'output'       => isset( $options['output'] ) ? $options['output'] : false,
      'before_field' => isset( $options['before_field'] ) ? $options['before_field'] : false,
    );

    return $result;
  }

  private function includes() {
    require_once 'interface.php';
    require_once 'options-factory.php';
    require_once 'general.php';
    require_once 'members.php';
    require_once 'members-style.php';
  }

  function getInstance() {
      if (null === self::$instance) {
          self::$instance = new self();
      }
      return self::$instance;
  }
}
