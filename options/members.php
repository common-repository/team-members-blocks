<?php
namespace TMBOptions;

Class Members extends OptionsFactory implements \TFMembersOpsInterface {
  static $group;
  static $section;

  static function set_fields() {
    self::$group['id'] = 'member';
    self::$group['desc'] = esc_html__( 'Add/Remove New Team Member', 'tmb-string' );
    self::$group['options'] = array(
        'group_title'   => esc_html__( 'Team Member {#}', 'tmb-string' ),
        'add_button'    => esc_html__( 'Add Another Member', 'tmb-string' ),
        'remove_button' => esc_html__( 'Remove Member', 'tmb-string' ),
        'sortable'      => true,
    );

    self::$section['id'] = 'members_box';
    self::$section['title'] = esc_html__('Team Members', 'tmb-string');
  }

  public static function get_options() {
    $options = array(
      array(
        'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Member\'s Photo', 'tmb-string'),
        'id'   => 'photo',
        'type' => 'file',
        'description' => esc_html__('Recommended size - 360x240px', 'tmb-string'),
        'classes' => 'long-th'
      ),
      array(
        'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Name', 'tmb-string'),
        'id'   => 'name',
        'type' => 'text',
        'classes' => 'half-view'
      ),
      array(
        'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Last Name', 'tmb-string'),
        'id'   => 'lastname',
        'type' => 'text',
        'classes' => 'half-view'
      ),
      array(
        'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Position', 'tmb-string'),
        'id'   => 'position',
        'type' => 'text',
      ),
      array(
        'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('About Info', 'tmb-string'),
        'id'   => 'description',
        'type' => 'textarea',
      ),
      array(
        'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Skills', 'tmb-string'),
        'id'   => 'skills',
        'type' => 'text',
        'repeatable' => true,
        'text' =>   array(
      		'add_row_text' => 'Add Skill',
      	),
        'classes' => array('half-view', 'width-40'),
      ),
      array(
        'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Skills Percentage', 'tmb-string'),
        'id'   => 'skills_percentage',
        'type' => 'text',
        'repeatable' => true,
        'text' =>   array(
          'add_row_text' => 'Add Percentage',
        ),
        'classes' => array('half-view', 'width-50'),
      ),
      array(
        'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__('Social Networks', 'tmb-string'),
        'id'   => 'social',
        'type' => 'text',
        'repeatable' => true,
        'text' =>   array(
      		'add_row_text' => 'Add Social Network',
      	),
        'classes' => array('half-view', 'width-40'),
        'description' => esc_html__('Just paste link, icons will be automatically generetated for following social media: facebook, twitter and instagram. With customizer addon include linkedin, dribbble, behance, google plus, pinterest', 'tmb-string')
      ),
    );

    return apply_filters( 'tf_members_members_options', $options );
  }
}
