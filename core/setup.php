<?php
namespace TFTeamBlocks\Core;

class Setup {

    private static $instance;
    static $version = TF_TB_VERSION;
    private $prefix;

    function get_prefix() {
      return $this->prefix;
    }

    function options() {
      $prefix = $this->prefix;
      $options = new \TMBOptions($prefix);

      return $options;
    }

    function __construct() {
      self::$instance = $this;

      $this->prefix = '_tft_';
      $this->hooks();
    }

    private function hooks() {
         add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
         add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
         add_action( 'init', array( $this, 'register_cpt' ) );
         add_action( 'cmb2_init', array( $this, 'members_metabox_init' ) );
         add_action( "manage_tf_members_posts_custom_column", array( $this, "tf_number_custom_columns" ), 10, 2 );
         add_action( 'admin_head', array( $this, 'collect_numbers' ) );
         add_action( 'admin_menu', array( $this, 'replace_submit_meta_box' ) );
         add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
         // add_action( 'plugins_loaded', array( $this, 'vc_support' ) );

         add_filter( 'mce_buttons', array( $this, 'register_button' ) );
         add_filter( 'mce_external_plugins', array( $this, 'add_button_js' ) );
         add_filter('plugin_row_meta',  array($this, 'team_blocks_links'), 10, 2);
         add_filter( "manage_edit-tf_members_columns", array( $this, "tf_number_post_columns" ) );
      }

      /**
      * Enqueue scripts and styles
      *
      */
      function enqueue_scripts() {
         wp_enqueue_style( 'tf_members-style', TF_TB_DIR . 'assets/css/style.css', self::$version );
      }

      function admin_enqueue_scripts() {
          $screen = get_current_screen();
          if( is_admin() && 'tf_members' === $screen->post_type ) {
             wp_enqueue_style( 'tf-members-admin', TF_TB_DIR . 'assets/css/admin.css', self::$version );
          }
      }

     /**
      * Register tf numbers button
      * to tinyMCE buttons
      *
      * @since   1.0.0
      */
      function register_button($buttons) {
        global $current_screen;
        $type = $current_screen->post_type;

        if( is_admin() ) {
          array_push( $buttons, 'tf_members' );
        }

        return $buttons;
      }


     /**
      * Add script callback to tf numbers
      * shortcode button in tinyMCE editor
      *
      * @since   1.0.0
      */
      function add_button_js($plugins) {
        if( is_admin() ) {
          $plugins['tf_members'] = TF_TB_DIR . 'assets/js/shortcode.js';
        }

        return $plugins;
      }


     /**
      * Collect Stats for inclusion
      * into shortcode selection.
      *
      * @since   1.0.0
      */
      function collect_numbers() {
        $args = array(
          'post_type'      => 'tf_members',
          'posts_per_page' => -1
        );
         $stats = get_posts($args);
         ?>
         <script type="text/javascript">
           var names = {};
           <?php foreach( $stats as $stat ): ?>
           names['<?php echo $stat->post_name ?>'] = ['<?php echo $stat->post_name; ?>'];
           <?php endforeach; ?>
         </script>
         <?php
       }

       // source: http://goo.gl/iAiPPI
       function team_blocks_links ($links, $file) {
          $base = TF_TB_BASENAME;
          if ($file == $base) {
              $links[] = '<a href="http://themeflection.com/docs/team-members/">' .'<i class="dashicons dashicons-book"></i>'. __('Documentation') . '</a>';
              // $links[] = '<a href="admin.php?edit.php?post_type=tf_stats&page=tf-addons">' .'<i class="dashicons dashicons-archive"></i>'. __('Addons', 'tf-numbers') . '</a>';
          }
          return $links;
      }

     /**
      * Initialize Stats custom
      * post_type
      *
      * @since   1.0.0
      */
      function register_cpt() {
        $labels = array(
            'name'          => esc_html__( 'Team Members', 'tf_numbers' ),
            'singlular_name'=> esc_html__( 'Team Member', 'tf_numbers' ),
            'plural_name'   => esc_html__( 'Team Members', 'tf_numbers' ),
            'add_new'       => esc_html__('Add Members', 'tf_numbers'),
            'add_new_item'  => esc_html__('Add Members', 'tf_numbers'),
            'new_item'      => esc_html__('New Members', 'tf_numbers'),
            'edit_item'     => esc_html__('Edit Members', 'tf_numbers'),
            'all_items'     => esc_html__('All Members', 'tf_numbers'),
            'not_found'     => esc_html__('No Members found', 'tf_numbers'),
            'not_found_in_trash'  => esc_html__('No Members found in trash', 'tf_numbers'),
        );

        register_post_type(
          'tf_members', array(
            'labels' => $labels,
            'public'  => false,
            'supports' => array('title'),
            'rewrite' => false,
            'publicly_queriable' => false,
            'show_ui' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'has_archive' => false,
            'menu_icon' => 'dashicons-slides',
            'menu_position'  => 65
          )
        );
      }

      /**
       * Create metaboxes for options
       *
       */
      function members_metabox_init() {
          $this->options()->init();
      }

      function add_metaboxes() {
        add_meta_box('tfmb_addons', sprintf( '<b style="#333">%s</b>', esc_html__( 'Thank You For Using Our Plugin!', 'tf_members' ) ), array( $this, 'addon_meta' ), 'tf_members', 'side', 'high');
      }

     function addon_meta() {
       ?>
       <h3 style="font-style: italic;display:block;color: green"><?php esc_html_e( 'Make your members more stunning with addons', 'tmb-string' ); ?></h3>
       <div class="addons">

        <div class="one-fourth">
          <img src="<?php echo TF_TB_DIR . 'assets/images/customizer.jpg'; ?>"/>
          <p class="desc">
            <strong class="h4">Customizer Addon</strong>
            gives you more customization options - circle member photo layout, 5 more social network icons(linkedin, dribbble, behance, pinterest and google plus), animated skills bar, show number with percentage in skills bar, 4 Photo filters for member photo and social icons and more.
            <a target="_blank" href="http://themeflection.com/plug/team-members-customizer/" class="learn-more">Get It</a>
          </p>';
        </div>

       </div>
       <?php
     }

     /**
      * Add Custom Columns
      * post edit screen
      *
      */
      function tf_number_post_columns($cols) {
        $cols = array(
          'cb' => '<input type="checkbox" />',
          'title' => esc_html__('Title', 'tmb-string'),
          'shortcode' => esc_html__('Shortcode', 'tmb-string')
        );

        return $cols;
      }

     /**
     * custom columns callback
     *
     * @since    1.0.0
     */
      function tf_number_custom_columns( $column, $post_id ) {
        switch( $column ) {
          case 'shortcode':
            global $post;
            $name = $post->post_name;
            $shortcode = '<span style="border: solid 2px cornflowerblue; background:#fafafa; padding:2px 7px 5px; font-size:17px; line-height:40px;">[tf_numbers name="'.esc_attr($name).'"]</strong>';
          echo $shortcode;
          break;
        }
      }

      function replace_submit_meta_box() {
          $item = 'tf_members';
          remove_meta_box('submitdiv', $item, 'core');
          add_meta_box('submitdiv', __('Save/Update Members', 'tmb-string'), array( $this, 'submit_meta_box' ), $item, 'side', 'low');
     }

     /**
      * Custom edit of default wordpress publish box callback
      * loop through each custom post type and remove default
      * submit box, replacing it with custom one that has
      * only submit button with custom text on it (add/update)
      *
      * @global $action, $post
      * @see wordpress/includes/metaboxes.php
      * @since  1.0
      *
      */
       function submit_meta_box() {
          global $action, $post;

          $post_type = $post->post_type;
          $post_type_object = get_post_type_object($post_type);
          $can_publish = current_user_can($post_type_object->cap->publish_posts);
          $item = 'tf_members';
          ?>
          <div class="submitbox" id="submitpost">
           <div id="major-publishing-actions">
           <?php
           do_action( 'post_submitbox_start' );
           ?>
           <div id="delete-action">
           <?php
           if ( current_user_can( "delete_post", $post->ID ) ) {
             if ( !EMPTY_TRASH_DAYS )
                  $delete_text = esc_html__('Delete Permanently');
             else
                  $delete_text = esc_html__('Move to Trash');
           ?>
           <a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
           } //if ?>
          </div>
           <div id="publishing-action">
           <span class="spinner"></span>
           <?php
           if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
                if ( $can_publish ) : ?>
                  <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Save Numbers') ?>" />
                  <?php submit_button( sprintf( esc_html__( 'Save %' ), $item ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
           <?php
                endif;
           } else { ?>
                  <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Update ') . $item; ?>" />
                  <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e('Update ') . $item; ?>" />
           <?php
           } //if ?>
           </div>
           <div class="clear"></div>
           </div>
           </div>
        <?php
      }

    /**
    * Visual Composer Shortcode
    * @todo add VC compatibility
    * @since    1.0.0
    */
    // function vc_support() {
    //   if( class_exists('WPBakeryVisualComposerAbstract') ) {
    //     require_once 'vc-shortcode.php';
    //   }
    // }

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
