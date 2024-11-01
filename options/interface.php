<?php
interface TFMembersOpsInterface {
    // initialization call
    static function set_fields();

    // options array
    static function get_options();
}

interface TFMembersOptionFactory {

  static function init($prefix);

  static function get_section($prefix);

  static function get_group($section, $prefix);

  static function render_fields($prefix, $section, $options, $group);
}
