<?php
namespace TMBOptions;

Class MembersStyle extends OptionsFactory implements \TFMembersOpsInterface {
  static $section;

  static function set_fields() {
    self::$section['id']       = 'members_style';
    self::$section['title']    = esc_html__('Members Style', 'tmb-string');
    self::$section['classes']  = 'flex';
  }

  public static function get_options() {
    $options = array(
      array(
          'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Name Color', 'tmb-string'),
          'id'   => 'nc',
          'type' => 'colorpicker',
          'selector' => ' .tf-member-name',
          'styles'   => array('color')
      ),
      array(
          'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Position Color', 'tmb-string'),
          'id'   => 'pc',
          'type' => 'colorpicker',
          'selector' => ' .tf-member .tf-member-position',
          'styles'   => array('color')
      ),
      array(
          'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('About Color', 'tmb-string'),
          'id'   => 'ac',
          'type' => 'colorpicker',
          'selector' => ' .tf-member-bio',
          'styles'   => array('color')
      ),
      array(
          'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Skill Title Color', 'tmb-string'),
          'id'   => 'sc',
          'type' => 'colorpicker',
          'selector' => ' .tf-member-skill .tf-member-skill-title',
          'styles'   => array('color')
      ),
      array(
          'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Skill Progress Background', 'tmb-string'),
          'id'   => 'spc',
          'type' => 'colorpicker',
          'selector' => ' .tf-member-skill .progress-bar',
          'styles'   => array('background-color')
      ),
      array(
          'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Skill Progress Active Background', 'tmb-string'),
          'id'   => 'spac',
          'type' => 'colorpicker',
          'selector' => ' .tf-member-skill .progress-wrap',
          'styles'   => array('background-color')
      ),
      array(
          'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Member Box Border Color', 'tmb-string'),
          'id'   => 'box-border',
          'type' => 'colorpicker',
          'selector' => ' .tf-member',
          'styles'   => array('border-color'),
          'description' =>  esc_html__('Set team member box wrapper border color', 'tmb-string'),
      ),
      array(
          'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Member Box Border Thickness', 'tmb-string'),
          'id'   => 'box-border-width',
          'type' => 'text_small',
          'before_field' => 'px',
          'selector' => ' .tf-member',
          'styles'   => array('border-width'),
          'description' =>  esc_html__('Set team member box wrapper border thickness', 'tmb-string'),
      ),
    );

    $options = apply_filters( 'tf_members_style_options_array', $options );

    return $options;
  }
}
