<?php
namespace TMBOptions;

class OptionsFactory implements \TFMembersOptionFactory {

  private function __construct() {}

  static function init($prefix) {
     static::set_fields();
     $section = self::get_section($prefix);

     if ( isset(static::$group) ) {
       $group   = self::get_group($section, $prefix);

     } else {
       $group = null;
     }

     $options = static::get_options();

     self::render_fields($prefix, $section, $options, $group);

     // Hook for appending new fields to the elements
     $hook = strtolower(static::class);
     $hook_prefix = 'tf-members';

     do_action("{$hook_prefix}_{$hook}_options", $section, $group);
  }

  static function render_fields($prefix, $section, $options, $group) {
    if ( !isset(static::$group) ) {
      foreach ( $options as $values => $option ) {
        $option['id'] = $prefix . $option['id'];
        $section->add_field( $option );
      }

    } else {
      if ( ! empty($options) ) {
        foreach ( $options as $values => $option ) {
          $option['id'] = $prefix . $option['id'];
          $section->add_group_field( $group, $option );
        }
      }
    }
  }

  static function get_section($prefix) {
      $hook = strtolower(static::class);
      $hook_prefix = 'tf-members';

      $section_args = apply_filters( "{$hook_prefix}_{$hook}_section_args", array(
          'id'    => $prefix . static::$section['id'],
          'title' => static::$section['title'],
          'context'  => isset(static::$section['context']) ? static::$section['context'] : 'normal',
          'priority' => isset(static::$section['priority']) ? static::$section['priority'] : 'core',
          'classes' => isset(static::$section['classes']) ? static::$section['classes'] : '',
          'object_types' => array( 'tf_members' ),
      ) );

      if ( empty(static::get_options()) ) {
        return null;
      }

      $section = new_cmb2_box( $section_args );

      return $section;
  }

  static function get_group($section, $prefix) {
    $hook = strtolower(static::class);
    $hook_prefix = 'tf-members';

    $group_args = apply_filters( "{$hook_prefix}_{$hook}_group_args", array(
      'id'          => $prefix . static::$group['id'],
      'type'        => 'group',
      'description' => static::$group['desc'],
      'options'     => static::$group['options']
    ) );

    $group = $section->add_field( $group_args );

    return $group;
  }
}
