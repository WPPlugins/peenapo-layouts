<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * main admin class to initiate the plugin
 *
 *
 */
class Playouts_Admin {

    /*
     * is plugin enabled for the current post
     *
     */
    static $status = false;

    /*
     * is plugin enabled for the current post type
     *
     */
    static $status_post_type = false;

    /*
     * holds the plugin settings options
     *
     */
    static $options = array();

	/*
	 * post types to render the plugin
	 *
	 */
	static $post_types = array();

	/*
	 * initiates the admin functions
	 *
	 */
    static function init() {

        # the init actions
        add_action( 'init', array( 'Playouts_Admin', 'actions' ) );

        # main container classes
        add_action( 'playouts_main_class', array( 'Playouts_Admin', 'main_class' ) );

        # switch button classes
        add_action( 'playouts_switch_class', array( 'Playouts_Admin', 'switch_class' ) );

        # enqueue scripts
        add_action( 'admin_enqueue_scripts', array( 'Playouts_Admin', 'enqueue_scripts' ) );

        # on custom post type playouts_layout save / update
        add_action( 'save_post', array( 'Playouts_Admin', 'on_custom_layout_save' ) );

        # add settings link next to the plugin details
        add_filter( 'plugin_action_links_' . Playouts_Bootstrap::$plugin_slug, array( 'Playouts_Admin', 'add_settings_link' ) );

    }

    /*
     * main container classes
     *
     */
    static function main_class( $classes ) {

        if( Playouts_Admin::$status ) {
            if( ! ( isset( Playouts_Admin::$options['show_editor'] ) and Playouts_Admin::$options['show_editor'] ) ) {
                $classes[] = 'pl-hide-editor';
            }
        }

        return $classes;

    }

    /*
     * switch button classes
     *
     */
    static function switch_class( $classes ) {

        if( ! Playouts_Admin::$status ) {
            $classes[] = 'bw-switch-active';
        }

        return $classes;

    }

    /*
     * the init action hooks
     *
     */
    static function actions() {

        # check if the user have enough permissions
        if( ! current_user_can( 'manage_options' ) ) { return; }

        # on edit page
        add_action( 'load-post.php', array( 'Playouts_Admin', 'on_load_post' ) );
        add_action( 'load-post-new.php', array( 'Playouts_Admin', 'on_load_post' ) );

        # register the custom post type for custom layouts
        self::register_layouts_post_type();

    }

    /*
     * register post type layouts
     *
     */
    static function register_layouts_post_type() {

        # register the taxonomy
    	$taxonomy_labels = array(
    		'name'                       => _x( 'Categories', 'taxonomy general name', 'peenapo-layouts-txd' ),
    		'singular_name'              => _x( 'Category', 'taxonomy singular name', 'peenapo-layouts-txd' ),
    		'search_items'               => __( 'Search Categories', 'peenapo-layouts-txd' ),
    		'popular_items'              => __( 'Popular Categories', 'peenapo-layouts-txd' ),
    		'all_items'                  => __( 'All Categories', 'peenapo-layouts-txd' ),
    		'parent_item'                => null,
    		'parent_item_colon'          => null,
    		'edit_item'                  => __( 'Edit Category', 'peenapo-layouts-txd' ),
    		'update_item'                => __( 'Update Category', 'peenapo-layouts-txd' ),
    		'add_new_item'               => __( 'Add New Category', 'peenapo-layouts-txd' ),
    		'new_item_name'              => __( 'New Category Name', 'peenapo-layouts-txd' ),
    		'separate_items_with_commas' => __( 'Separate categories with commas', 'peenapo-layouts-txd' ),
    		'add_or_remove_items'        => __( 'Add or remove categories', 'peenapo-layouts-txd' ),
    		'choose_from_most_used'      => __( 'Choose from the most used categories', 'peenapo-layouts-txd' ),
    		'not_found'                  => __( 'No categories found.', 'peenapo-layouts-txd' ),
    		'menu_name'                  => __( 'Categories', 'peenapo-layouts-txd' ),
    	);
    	$taxonomy_args = array(
    		'hierarchical'          => false,
            'public'                => false,
    		'labels'                => $taxonomy_labels,
    		'show_ui'               => true,
    		'show_admin_column'     => true,
    		'update_count_callback' => '_update_post_term_count',
    		'query_var'             => true,
    		'rewrite'               => array( 'slug' => 'layout_category' ),
    	);
    	register_taxonomy( 'playouts_layout_category', 'playouts_layout', $taxonomy_args );

        # tagonomies as params
        $labels = array(
            'name'                  => esc_html__( 'Layout View', 'peenapo-layouts-txd' )
        );
    	$args = array(
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => false,
            'show_admin_column'     => true,
            'query_var'             => true,
            'show_in_nav_menus'     => false,
        );
        register_taxonomy( 'layout_view', 'playouts_layout', $args );

        # register the post type
        $post_type_labels = array(
            'name'                  => _x( 'Layouts', 'post type general name', 'peenapo-layouts-txd' ),
            'singular_name'         => _x( 'Layout', 'post type singular name', 'peenapo-layouts-txd' ),
            'menu_name'             => _x( 'Layouts', 'admin menu', 'peenapo-layouts-txd' ),
            'name_admin_bar'        => _x( 'Layout', 'add new on admin bar', 'peenapo-layouts-txd' ),
            'add_new'               => _x( 'Add New', 'layout', 'peenapo-layouts-txd' ),
            'add_new_item'          => __( 'Add New Layout', 'peenapo-layouts-txd' ),
            'new_item'              => __( 'New Layout', 'peenapo-layouts-txd' ),
            'edit_item'             => __( 'Edit Layout', 'peenapo-layouts-txd' ),
            'view_item'             => __( 'View Layout', 'peenapo-layouts-txd' ),
            'all_items'             => __( 'All Layouts', 'peenapo-layouts-txd' ),
            'search_items'          => __( 'Search Layouts', 'peenapo-layouts-txd' ),
            'parent_item_colon'     => __( 'Parent Layouts:', 'peenapo-layouts-txd' ),
            'not_found'             => __( 'No books found.', 'peenapo-layouts-txd' ),
            'not_found_in_trash'    => __( 'No books found in Trash.', 'peenapo-layouts-txd' )
    	);
    	$post_type_args = array(
            'labels'                => $post_type_labels,
            'description'           => __( 'Layouts', 'peenapo-layouts-txd' ),
            'taxonomies'            => array( 'playouts_layout_category' ),
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'layout' ),
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'menu_position'         => null,
            'supports'              => array( 'title', 'editor', 'revisions' ),
    	);
    	register_post_type( 'playouts_layout', $post_type_args );

    }

    /*static function after_title() {

        /*
         * get switch button template
         *
         *
        do_action( 'playouts_get_template_switch_button' );

    }*/

	/*
	 * check if the plugins is enabled for specific post
	 * basically if the button was clicked or not
	 *
	 */
    static function check_status( $post_id = false ) {

        if( ! $post_id ) { return; }

        $playouts_status = get_post_meta( $post_id, '__playouts_status', true );
        self::$status = $playouts_status;

    }

    static function check_post_type( $post_id = false ) {

        if( ! $post_id ) { return; }

        $enabled_post_types = isset( self::$options['post_types'] ) ? self::$options['post_types'] : array( 'post', 'page', 'playouts_layout' );
        if( array_key_exists( get_post_type( $post_id ), self::$post_types ) ) {

            self::$status_post_type = true;

        }

    }

    static function on_load_post() {

        # get the plugin options
        self::$options = get_option( 'playouts_layouts_options' );
        self::$post_types = isset( self::$options['post_types'] ) ? array_merge( array( 'playouts_layout' => 1 ), self::$options['post_types'] ) : array( 'post', 'page', 'playouts_layout' );

        # get current post type
        $screen = get_current_screen();
        $current_post_type = $screen->post_type;

        # if the post type supports wordpress editor
        if( post_type_supports( $current_post_type, 'editor' ) ) {
            # if plugin supports current post type
            if( array_key_exists( $current_post_type, self::$post_types ) ) {

                # get the post id
                $post_id = isset( $_GET['post'] ) ? (int)$_GET['post'] : false;

                # check the post status
                self::check_status( $post_id );
                # check the post type status
                self::check_post_type( $post_id );

                # add custom body classes
                add_filter( 'admin_body_class', array( 'Playouts_Admin', 'admin_body_class' ) );
                # register page builder
                add_action( 'add_meta_boxes', array( 'Playouts_Admin', 'add_custom_box' ) );
                # load footer templates
                add_action( 'admin_footer', array( 'Playouts_Admin', 'footer_templates' ) );
                # on save/edit post
                add_action( 'save_post', array( 'Playouts_Admin', 'save' ) );

            }
        }else{

            add_action( 'add_meta_boxes', array( 'Playouts_Admin', 'editor_not_supported' ) );

        }
    }

    /*
     * add custom body classes
     *
     */
    static function admin_body_class( $classes ) {
        if( self::$status and self::$status_post_type ) {
            return "{$classes} pl-active";
        }
    }

    /*static function get_current_post_type() {
        global $post, $typenow, $current_screen;
        if ( $post && $post->post_type ) {
            return $post->post_type;
        }elseif( $typenow ) {
            return $typenow;
        }elseif( $current_screen && $current_screen->post_type ) {
            return $current_screen->post_type;
        }elseif( isset( $_REQUEST['post_type'] ) ) {
            return sanitize_key( $_REQUEST['post_type'] );
        }
        return null;
    }*/

    static function add_custom_box() {

        $currnet_post_type = get_post_type();

        if( self::$status_post_type ) {

            add_meta_box(
                'peenapo_layouts_section_ui',
                __( 'Peenapo Layouts', 'peenapo-layouts-txd' ),
                array( 'Playouts_Admin', 'metabox_section_ui' ),
                $currnet_post_type,
                'normal',
                'high'
            );

            add_meta_box(
                'peenapo_layouts_section_switch',
                __( 'Peenapo Page Builder', 'peenapo-layouts-txd' ),
                array( 'Playouts_Admin', 'metabox_section_switch' ),
                $currnet_post_type,
                'side',
                'high'
            );

        }
    }



    static function editor_not_supported() {

        $currnet_post_type = get_post_type();

        add_meta_box(
            'peenapo_layouts_editor_not_supported',
            __( 'Peenapo Layouts Report', 'peenapo-layouts-txd' ),
            array( 'Playouts_Admin', 'metabox_section_editor_not_supported' ),
            $currnet_post_type,
            'normal',
            'high'
        );

    }

    static function get_children( $children ) {
        if( is_array( $children ) ) {
            return implode(',', $children );
        }
        return $children;
    }

    static function get_custom_css() {

        $playouts_custom_css = get_post_meta( get_the_ID(), '__playouts_custom_css', true );
        if ( ! isset( $playouts_custom_css ) or $playouts_custom_css == '' ) {
            $playouts_custom_css = '';
        }
        return $playouts_custom_css;

    }

    static function metabox_section_ui( $post ) {

        /*
         * get main template
         *
         */
        do_action( 'playouts_get_template_main' );

    }

    static function metabox_section_switch( $post ) {

        /*
         * get template for switch section
         *
         */
        do_action( 'playouts_get_template_switch' );

    }



    static function metabox_section_editor_not_supported() {

        /*
         * get template for editor not supported
         *
         */
        do_action( 'playouts_get_template_editor_not_supported' );

    }

    static function get_free_id() {

        $last_id = get_option( 'playouts_last_block_id', 0 );

        if ( $last_id <= 2 ) { $last_id = 2; }
        $last_id++;

        update_option( 'playouts_last_block_id', $last_id );

        return $last_id;

    }

    static function update_builder_data( $post_id, $value, $data ) {

        array_walk_recursive( $data, array( 'Playouts_Admin', 'sanitize_array' ) );
        update_post_meta( $post_id, $value, $data );

    }

    static function sanitize_array( &$item, $key ) {

        $item = htmlentities( $item, ENT_QUOTES );

    }

    static function post_param( $param, $default = null ) {

        return isset( $_POST[ $param ] ) ? sanitize_text_field( $_POST[ $param ] ) : $default;

    }

    static function footer_templates() {

        /*
         * generate all the element's templates
         *
         */
        do_action( 'playouts_get_template_elements' );

        /*
         * other partials
         *
         */
        do_action( 'playouts_get_template_partials' );

        /*
         * get template for settings panel
         *
         */
        do_action( 'playouts_get_template_settings_panel' );

        /*
         * generate a template for our icons
         *
         */
        do_action( 'playouts_get_template_icons' );

    }

    static function save( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

        if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

        $status = self::get_post_param( 'playouts_status' );
        $custom_css = isset( $_POST['bw_custom_css'] ) ? sanitize_text_field( $_POST['bw_custom_css'] ) : '';

        #d( $status );exit;

        // TODO: fix this
        if ( $status !== false ) {
            // add status
            if ( get_post_meta( $post_id, '__playouts_status' ) == '' ) {
                add_post_meta( $post_id, '__playouts_status', $status, true );
            }
            // update status
            elseif ( $status !== get_post_meta( $post_id, '__playouts_status', true ) ) {
                update_post_meta( $post_id, '__playouts_status', $status );
            }
            // delete status
            elseif ( $status == '' ) {
                delete_post_meta( $post_id, '__playouts_status', get_post_meta( $post_id, '__playouts_status', true ) );
            }
        }

        if ( $custom_css !== false ) {
            // add custom css
            if ( get_post_meta( $post_id, '__playouts_custom_css' ) == '' ) {
                add_post_meta( $post_id, '__playouts_custom_css', $custom_css, true );
            }
            // update custom css
            elseif ( $custom_css != get_post_meta( $post_id, '__playouts_custom_css', true ) ) {
                update_post_meta( $post_id, '__playouts_custom_css', $custom_css );
            }
            // delete custom css
            elseif ( $custom_css == '' ) {
                delete_post_meta( $post_id, '__playouts_custom_css', get_post_meta( $post_id, '__playouts_custom_css', true ) );
            }
        }
    }

    static function get_post_param( $param, $default = null ) {

        return isset( $_POST[ $param ] ) ? sanitize_text_field( $_POST[ $param ] ) : $default;

    }

    static function on_custom_layout_save( $layout_id ) {

        if ( wp_is_post_revision( $layout_id ) ) { return; } // do nothing, if this is just a revision

        if( get_post_type( $layout_id ) == 'playouts_layout' ) {

            // extract the first module of the layout
            $first_module = Playouts_Admin_Layout_Custom::extract_first_module( get_post_field( 'post_content', $layout_id ) );

            // get layout view by the first extracted module
            $layout_view = Playouts_Element::get_module_view( $first_module );

            // save the layout view
            wp_set_post_terms( $layout_id, $layout_view, 'layout_view' );

        }

    }

    static function enqueue_scripts() {

        if( self::$status_post_type or ( isset( $_GET['page'] ) and $_GET['page'] == 'playouts_options' ) ) {

            //TODO: fix this mess
            # css
            wp_enqueue_style( 'wp-color-picker' );
    		wp_enqueue_style( 'playouts', PLAYOUTS_ASSEST . 'admin/css/style.css' );
    		wp_enqueue_style( 'playouts-jquery-ui', PLAYOUTS_ASSEST . 'admin/css/vendors/jquery-ui.css' );

            # google fonts
            $query_args = array(
                'family' => 'Palanquin+Dark:400,600|Oxygen:400',
                'subset' => 'latin',
            );
            wp_enqueue_style( 'playouts-google-fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );

    		# js
            if( isset( $_GET['page'] ) and $_GET['page'] == 'playouts_options' ) {
                wp_enqueue_media();
            }
    		wp_enqueue_script( array( "jquery", "jquery-ui-core", "jquery-ui-dialog", "jquery-ui-sortable", "wp-color-picker", "jquery-ui-slider" ) );
            wp_register_script( 'playouts', PLAYOUTS_ASSEST . 'admin/js/main.js', array('jquery-ui-sortable'), '1.0', true );
    		wp_localize_script( 'playouts', 'playouts_admin_root', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
            wp_enqueue_script( 'playouts-smart-resize', PLAYOUTS_ASSEST . 'admin/js/vendors/jquery-smartresize-master/jquery.debouncedresize.js', array(), '1.0', true );
            wp_enqueue_script( 'playouts-vendors', PLAYOUTS_ASSEST . 'admin/js/vendors.js', array(), '1.0', true );
            wp_enqueue_script( 'playouts-php-default', PLAYOUTS_ASSEST . 'admin/js/vendors/php.default/php.default.min.js', array(), '1.0', true );
            wp_enqueue_script( 'playouts-colorpicker', PLAYOUTS_ASSEST . 'admin/js/vendors/wpcolorpicker/wp-colorpicker.min.js', array(), '1.0', true );
            wp_enqueue_script( 'playouts-blocker', PLAYOUTS_ASSEST . 'admin/js/playouts.blocker.js', array(), '1.0', true );
            wp_enqueue_script( 'playouts-shortcoder', PLAYOUTS_ASSEST . 'admin/js/playouts.shortcoder.js', array(), '1.0', true );
            wp_enqueue_script( 'playouts-layouts', PLAYOUTS_ASSEST . 'admin/js/playouts.layouts.js', array(), '1.0', true );
            wp_enqueue_script( 'playouts-mapper', PLAYOUTS_ASSEST . 'admin/js/playouts.mapper.js', array(), '1.0', true );

    		wp_enqueue_script( 'playouts' );

        }
    }

    static function add_settings_link( $links ) {
        $settings_link = '<a href="' . get_admin_url() . 'admin.php?page=playouts_options">' . esc_html__( 'Settings', 'peenapo-layouts-txd' ) . '</a>';
        array_push( $links, $settings_link );
        return $links;
    }

}

Playouts_Admin::init()

?>
