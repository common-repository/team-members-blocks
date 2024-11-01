<?php

if( !class_exists( 'TFMembersShortcode' ) )
{
  class TFMembersShortcode
  {
    // global style that will be
    // colleted in this variable
    private $css = array();
    // global index that will be
    //used in styles array
    private $n;
    private $prefix;
    private $member_id;
    private $options_data;
    private $options;

    function __construct($prefix, $options) {
      add_shortcode( 'tf_members', array( $this, 'members_shortcode' ) );
      add_action('wp_footer', array($this,'tf_print_style'));
      $this->prefix = $prefix;
      $this->options_data = $options;
    }

    function members_shortcode( $atts ) {
      $prefix = $this->prefix;
      /**
        * Call post by name extracting the $name
        * from the shortcode previously created
        * in custom post column
        */
        extract( shortcode_atts( array(
             'name' => ''
          ), $atts )
        );

        $args = array('post_type' => 'tf_members', 'name' => $name);
        $members = get_posts( $args );
        $html = '';

        if( $members )
        {
          foreach( $members as $member )
          {
            setup_postdata($member);
            $ID = 'tf-members-' . $member->ID;
            $this->member_id = $member->ID;
            $this->assing_options();
            $css = $this->generate_styles();
            $this->css[$ID] = $css;

            $vals = get_post_meta( $member->ID, "{$prefix}member", true );

            $section_class = apply_filters( 'tf_members_section_class', array('team-members', 'tf-members-' . count($vals)), $member->ID, $this );
            $wrapper_class = apply_filters( 'tf_members_wrapper_class', array('tf-members-wrapper'), $member->ID, $this );
            $heading_class = array('tf-members-heading');
            $atts = array(
              'id'    => esc_attr($ID),
              'class' => $this->render_classes($section_class)
            );
            $atts = apply_filters('tf_members_section_atts', $atts, $member->ID);

            // Output starts here
            $html .= apply_filters( 'tf_members_section_open',
                sprintf( '<section %s>', $this->render_atts($atts) ),
                $atts,
                $member->ID
            );

            if( isset( $member->post_title ) && $member->post_title ) {
              $html .= sprintf('<h3 class="%s">%s</h3>',
                  $this->render_classes($heading_class),
                  apply_filters('the_title', $member->post_title)
              );
            }

            $html .= apply_filters( 'tf_members_wrapper_open',
                sprintf( '<div class="%s">', $this->render_classes($wrapper_class) ),
                $wrapper_class,
                $member->ID
            );

            foreach( $vals as $key => $value ) {
              $html .= $this->render_card($value);
            }

            $html .= apply_filters( 'tf_members_wrapper_close', '</div>' );

            $html .= apply_filters( 'tf_members_section_close', '</section>' );
          }
        }

        return $html;
    }

    function render_card($value) {
      $prefix = $this->prefix;
      $blocks = array();

      $name = $this->get_value($value, 'name');
      $lname = $this->get_value($value, 'lastname');
      $photo = $this->get_value($value, 'photo');
      $position = $this->get_value($value, 'position');
      $desc = $this->get_value($value, 'description');
      $skills = $this->get_value($value, 'skills');
      $skills_percentage = $this->get_value($value, 'skills_percentage');
      $social = $this->get_value($value, 'social');

      $blocks['photo'] = sprintf('<div class="tf-member-photo"><img src="%s" /></div>', esc_url($photo));
      $blocks['name'] = sprintf('<h3 class="tf-member-name">%s %s</h3>', esc_html($name), esc_html($lname));
      $blocks['position'] = sprintf('<h4 class="tf-member-position">%s</h4>', esc_html($position));
      $blocks['desc'] = sprintf('<p class="tf-member-bio">%s</p>', esc_html($desc));
      $blocks['skills'] = $this->get_skills($skills, $skills_percentage);
      $blocks['social'] = $this->get_social_links($social);


      $blocks = apply_filters('tf_member_blocks', $blocks);

      $html = '<div class="tf-member">';
      $html .= implode(PHP_EOL, $blocks);
      $html .= '</div>';

      return apply_filters('tf_members_member_output', $html, $blocks);
    }

    private function get_skills($skills, $percents) {
      $show_perc = $this->get_option('show-percent') ? $this->get_option('show-percent') : false;
      $cls_ex = class_exists('TFMembersCustomizer');
      $data = array_combine($skills, $percents);
      $html = '<div class="tf-member-skills">';

      foreach ( $data as $skill => $percent ) {
          if ( strpos( $percent, '%' ) !== false ) {
            $percent = str_replace('%', '', $percent);
          }

          $html .= '
            <div class="tf-member-skill ' . ( $show_perc ? 'with-perc-number' : '' ) . '">
              <h5 class="tf-member-skill-title">' . esc_html($skill) . '</h5>
              <div class="progress-wrap progress" ' . ( !$cls_ex ? 'style="width:' . esc_attr($percent) . '%"' : '' ) . ' data-progress-percent="'. esc_attr($percent) . '">
                  <div ' . ( !$cls_ex ? 'style="left:' . esc_attr($percent) . '%"' : '' ) . ' class="progress-bar progress"></div>
              </div>
              ' . ($show_perc ? '<span class="tf-member-skill-n">' . esc_html($percent) . '%</span>' : '') . '
            </div>
          ';
      }
      $html .= '</div>';

      return apply_filters('tf_member_skills_html', $html);
    }

    private function get_social_links($links) {
      $html = array('<div class="tf-member-social-links">');
      foreach ( $links as $link ) {
        $icon = $this->get_social_icon($link);
        if ( ! empty($icon) ) {
          $html[$icon['title']] = sprintf('<a title="%1$s" href="%2$s" target="_blank"><img src="%3$s" alt="%1$s" /></a>',
            esc_attr($icon['title']),
            esc_url($link),
            strpos($icon['src'], 'http') !== false ? esc_attr($icon['src']) : TF_TB_DIR . esc_attr($icon['src'])
          );
        }
      }
      $html[] = '</div>';

      $html = apply_filters('tf_members_social_icons', $html, $links);

      return implode(PHP_EOL, $html);
    }

    private function get_social_icon($link) {
      $icon = null;
      switch($link) {
        case strpos($link, 'facebook') !== false:
          $icon['src'] = 'assets/images/facebook.png';
          $icon['title'] = 'facebook';
        break;
        case strpos($link, 'twitter') !== false:
          $icon['src'] = 'assets/images/twitter.png';
          $icon['title'] = 'twitter';
        break;
        case strpos($link, 'instagram') !== false:
          $icon['src'] = 'assets/images/instagram.png';
          $icon['title'] = 'instagram';
        break;
        default:
          $icon = null;
        break;
      }

      return apply_filters('tf_members_social_icon', $icon, $link);
    }

    private function get_value($values, $name) {
      $prefix = $this->prefix;
      $value = isset($values["{$prefix}{$name}"]) ? $values["{$prefix}{$name}"] : '';

      return $value;
    }

    function get_option($name) {
      $id = $this->member_id;
      $option = apply_filters(
        'tf_members_get_option',
        isset($this->options[$id][$name]) ? $this->options[$id][$name] : false, $name
      );

      return $option ? $option['value'] : false;
    }

    private function assing_options() {
      $id = $this->member_id;
      $this->options[$id] = $this->options_data;
      array_walk($this->options[$id], array($this, 'map_options'), $id);
    }

    private function map_options($option, $key, $id) {
      $prefix = $this->prefix;
      $key = $option['id'];
      $value = get_post_meta( $this->member_id, $prefix.$option['id'], true );

      if ( !empty($option['output']) && 'on' == $value ) {
        $value = esc_attr($option['output']);
      }

      $this->options[$id][$key]['value'] = esc_html($value);
    }

    private function generate_styles() {
      $id = $this->member_id;
      $options = $this->options[$id];
      $css = array();

      foreach ( $options as $name => $option ) {
        $valid_style = !empty($option['selector']) && !empty($option['styles']) && !empty($option['value']);

        if ( $valid_style ) {
          $values = array();
          
          foreach ( $option['styles'] as $style ) {
            $values[] = $this->map_style($style, $option['value'], $option['before_field']);
          }

          $css[] = sprintf( '#%s%s{%s}',
              "tf-members-$id",
              esc_attr( $option['selector'] ),
              implode(' ', $values)
          );
        }
      }

      $css = apply_filters('tf-members-css-collection', $css);
      $css = implode(' ', $css);

      return apply_filters('tf-members-generate-styles', $css, $options);
    }

    private function map_style($style, $value, $before_field) {
      if ( in_array($before_field, array('px', 'em', '%')) ) {
        $value = $value . $before_field;
      }

      $styles = sprintf('%s:%s;', esc_attr($style), esc_attr($value));

      return $styles;
    }

    private function render_classes($class) {
      return implode(' ', $class);
    }

    private function render_atts($atts) {
      $result = '';
      foreach ( $atts as $att => $value ) {
          $result .= sprintf('%s="%s"', esc_attr($att), esc_attr($value));
      }

      return $result;
    }

    private function reduce_atts($trail, $item) {
      return sprintf('%s="%s"', esc_attr($trail), esc_attr($item));
    }

    public function tf_print_style(){
        $styles = $this->css;
        $css = '<style type="text/css">';
        foreach( $styles as $style ) {
          $css .= $style;
        }
        $css .= '</style>';

        if ( !empty($css) ) {
          echo $css;
        }
    }
  }//class ends
}//if !class_exists
