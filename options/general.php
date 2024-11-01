<?php
namespace TMBOptions;

Class General extends OptionsFactory implements \TFMembersOpsInterface {
  static $section;

  static function set_fields() {
    self::$section['id']       = 'general';
    self::$section['title']    = esc_html__('General Options', 'tmb-string');
    self::$section['classes']  = 'flex';
  }

  public static function get_options() {
    $options = array(
      array(
          'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Background Color', 'tmb-string'),
          'id'   => 'bgc',
          'type' => 'colorpicker',
          'selector' => '.team-members',
          'styles'   => array('background-color')
      ),
      array(
          'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Use Transparent Background', 'tmb-string'),
          'id'   => 'bgct',
          'type' => 'checkbox',
          'selector' => '.team-members',
          'styles'   => array('background-color'),
          'output'   => 'transparent'
      ),
    );

    // custom fields filter
    $options = apply_filters( 'tf_members_general_options_array', $options );
    
    return $options;
  }
}
