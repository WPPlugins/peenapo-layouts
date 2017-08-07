<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * the public part starts here
 *
 *
 */
class Playouts_Public {

    /*
     * get the current post type
     *
     */
    static $current_post_type;

    /*
     * will be set to true if we are in the right place
     * and we can parse and render the content
     *
     */
    static $do_render = false;

    /*
     * holds all the modules
     *
     */
    static $modules = array();

    /*
     * holds the content as shortcode ids array
     *
     */
    static $parsed_ids = array();

    /*
     * all the plugin's options
     *
     */
    static $options = array();

    /*
     * Fire the public scrap
     *
     */
	static function init() {

        self::$modules = Playouts_Element::get_modules();

        # setup some options
        add_action( 'get_header', array( 'Playouts_Public', 'setup_header' ) );
        # enqueue scripts
        add_action( 'wp_enqueue_scripts', array( 'Playouts_Public', 'enqueue_scripts' ) );
        # set custom css
        add_action( 'wp_head', array( 'Playouts_Public', 'custom_css' ) );
        # custom class settings
        add_filter( 'body_class', array( 'Playouts_Public', 'body_class_settings' ) );
        # filter the content of the post
        add_filter( 'the_content', array( 'Playouts_Public', 'the_content' ) );
        # templates
        add_action( 'wp_footer', array( 'Playouts_Public', 'templates' ) );

	}

    /*
     * setup some options before header
     *
     */
    static function setup_header() {

        global $post;

        if( isset( $post->ID ) ) {
            if( self::is_builder_used( $post->ID ) and is_main_query() ) {
                if( isset( $post->post_content ) and ! empty( $post->post_content ) ) {

                    // lets use our own shortcode parser
                    include_once PLAYOUTS_DIR . 'inc/shortcode_parser.php';

                    self::parse_content( $post->post_content, true );

                }
            }
        }

        self::$options = get_option( 'playouts_layouts_options' );

    }

    /*
     * get the content of the post and render the shotcodes
     *
     */
    static function the_content( $content ) {

        if( ! self::is_builder_used() or ! is_main_query() or empty( $content ) ) {
            return $content;
        }

        $outer_class = apply_filters( 'playouts_content_wrap_class', array( 'pl-outer' ) );
        $outer_classes = implode( ' ', $outer_class );

        $outer_id = apply_filters( 'playouts_content_id', 'pl-outer' );

        $inner_class = apply_filters( 'playouts_content_inner_class', array( 'pl-inner' ) );
        $inner_classes = implode( ' ', $inner_class );

        // lets use our own shortcode parser
        include_once PLAYOUTS_DIR . 'inc/shortcode_parser.php';

		return $content = sprintf(
			'<div class="%2$s" id="%4$s">
				<div class="%3$s">
					%1$s
				</div>
			</div>
            <span id="pl-overlay-main" class="pl-overlay-main"></span>
            <span id="pl-overlay-container" class="pl-overlay pl-overlay-container"></span>',
			self::parse_content( $content ), esc_attr( $outer_classes ), esc_attr( $inner_classes ), esc_attr( $outer_id )
		);

    }

    static function is_builder_used( $post_id = false ) {
        if( ! $post_id ) { $post_id = get_the_ID(); }
        return get_post_meta( $post_id, '__playouts_status', true ) == '1';
    }

    /*
     * we will use our own parser
     * parse given content and extract shortcodes into array
     *
     */
    static function parse_content( $content, $get_ids = false ) {

        $shortcodes_arr = array();
        $shortcodes_arr = playouts_do_shortcodes( $shortcodes_arr, $content );

        $rendered = self::loop_shortcodes_and_render( $shortcodes_arr, $get_ids );

        return $rendered;

    }

    /*
     * loop array of shortcodes and render the corresponding templates
     *
     */
    static function loop_shortcodes_and_render( $shortcodes_arr, $get_ids = false ) {

        $html_output = '';

        foreach( $shortcodes_arr as $shortcode_arr ) {

            $module_id = $shortcode_arr['id'];

            if( $get_ids ) { // return only the module id

                // TODO: remove hidden elements
                self::$parsed_ids[] = $module_id;

            }

            $callable_template = self::$modules[ $module_id ]->class_name . '::output';
            $callable_construct = self::$modules[ $module_id ]->class_name . '::construct';

            if( is_callable( $callable_template ) ) {

                $content = '';

                // will be called before the child elements
                // can be used to pass variables to the child template
                if( ! $get_ids and is_callable( $callable_construct ) ) {
                    $html_output .= call_user_func_array( $callable_construct, array( $shortcode_arr['atts'], $content ) );
                }

                // render the content
                if( isset( $shortcode_arr['content'] ) ) {
                    $content = is_array( $shortcode_arr['content'] ) ? self::loop_shortcodes_and_render( $shortcode_arr['content'], $get_ids ) : Playouts_Functions::autop( $shortcode_arr['content'] );
                }

                // call the template
                if( ! $get_ids ) {
                    $html_output .= call_user_func_array( $callable_template, array( $shortcode_arr['atts'], $content ) );
                }

            }

        }

        return $html_output;
    }

    /*
     * display the custom css code
     *
     */
    static function custom_css() {

        if( self::is_builder_used() ) {

            $container_width = self::$options['container_width'];
            if( (int) $container_width == 0 ) { $container_width = 1100; }

            // TODO: fix this
            echo '<style>'.
                '.pl-row-layout-standard > .pl-row, .pl-row-layout-boxed > .pl-row {max-width:' . (int) $container_width . 'px;}'.
            '</style>';

            $post_css = get_post_meta( get_the_ID(), '__playouts_custom_css', true );

            echo "<style>" . strip_tags( $post_css ) . "</style>";

        }

    }

    /*
     * control the body classes
     *
     */
    static function body_class_settings( $classes ) {

        if( self::is_builder_used() ) {
            $classes[] = 'pl-is-enabled';
        }
        return $classes;
    }

    static function set_background( $background, $atts ) {

        extract( $atts );

        if( ! empty( $background ) and $background !== 'none' ) {

            $style = $class = $data_attr = $inner = '';

            switch( $background ) {
                case 'color':
                    $style = 'background-color:' . esc_attr( $bg_color ) . ';';
                    break;
                case 'image':
                    $style  = 'background-image:url(' . esc_url( $bg_image ) . ');';
                    $style .= 'background-position:' . esc_attr( $bg_image_position ) . ';';
                    $style .= 'background-size:' . esc_attr( $bg_image_size ) . ';';
                    break;
                case 'parallax':
                    $class  = ' pl-parallax';
                    $style  = 'background-image:url(' . esc_url( $bg_image ) . ');';
                    $style .= 'background-position:' . esc_attr( $bg_image_position ) . ';';
                    $style .= 'background-size:' . esc_attr( $bg_image_size ) . ';';
                    $data_attr = ' data-parallax-speed="' . (int) $bg_parallax_speed . '"';
                    $inner = '<div class="pl-background-parallax"></div>';
                    break;
                case 'video':
                    //$class = ' pl-parallax';
                    //$data_attr = ' data-parallax-speed="300"';
                    if( ! empty( $bg_video_mp4 ) or ! empty( $bg_video_ogv ) or ! empty( $bg_video_webm ) ) {
                        $source = '';
                        if( ! empty( $bg_video_mp4 ) ) {
                            $source .= '<source src="' . esc_url( $bg_video_mp4 ) . '" type=\'video/webm; codecs="vp8.0, vorbis"\'>';
                        }
                        if( ! empty( $bg_video_ogv ) ) {
                            $source .= '<source src="' . esc_url( $bg_video_ogv ) . '" type=\'video/ogg; codecs="theora, vorbis"\'>';
                        }
                        if( ! empty( $bg_video_webm ) ) {
                            $source .= '<source src="' . esc_url( $bg_video_webm ) . '" type=\'video/mp4; codecs="avc1.4D401E, mp4a.40.2"\'>';
                        }
                        $inner = '<video poster="' . esc_url( $bg_video_poster ) . '" autobuffer autoplay loop muted>' . $source .
                        	'<p>' . esc_html__( 'Video not supported!', 'peenapo-layouts-txd' ) . '</p>'.
                        '</video>';
                    }
                    break;
            }

            return '<div class="pl-background-outer">'.
                '<div class="pl-background pl-background-' . esc_attr( $background ) . $class . '" style="' . $style . '"' . $data_attr . '>' . $inner . '</div>'.
            '</div>';

        }

        return '';

    }

    static function enqueue_scripts() {

        if( self::is_builder_used() ) {

            # css
            wp_enqueue_style( 'playouts-style', PLAYOUTS_ASSEST . 'css/style.css' );
            # icons
            wp_enqueue_style( 'playouts-stroke-7', PLAYOUTS_ASSEST . 'fonts/playouts-7-stroke/pe-icon-7-stroke.css' );
            # dynamic google fonts
            wp_enqueue_style( 'playouts-google-fonts', Playouts_Public_Fonts::output_google_font(), array('playouts-style') );
            wp_add_inline_style( 'playouts-google-fonts', Playouts_Public_Fonts::$font_declarations );

            # js
            wp_enqueue_script( 'jquery' );

            wp_enqueue_script( 'playouts-front-plugins', PLAYOUTS_ASSEST . 'js/playouts-front-plugins.js', array('jquery'), '1.0', true );
            wp_enqueue_script( 'playouts-front', PLAYOUTS_ASSEST . 'js/playouts-front.js', array('jquery'), '1.0', true );

            # dynamic enqueue
            if( in_array( 'bw_google_map', self::$parsed_ids ) ) {
                if( isset( Playouts_Public::$options['google_map_api_key'] ) and ! empty( Playouts_Public::$options['google_map_api_key'] ) ) {
                    wp_enqueue_script( 'playouts-google-map', '//maps.googleapis.com/maps/api/js?key=' . esc_attr( Playouts_Public::$options['google_map_api_key'] ) . '&callback=playouts_init_map', array( 'playouts-front' ), '1.0', true );
                }
            }
            if( in_array( 'bw_image_comparison', self::$parsed_ids ) ) {
                wp_enqueue_style( 'playouts-twentytwenty-css', PLAYOUTS_ASSEST . 'css/vendor/twentytwenty.css' );
                wp_enqueue_script( 'playouts-event-move', PLAYOUTS_ASSEST . 'js/vendor/jquery.event.move.js', array('jquery') );
                wp_enqueue_script( 'playouts-twentytwenty-js', PLAYOUTS_ASSEST . 'js/vendor/jquery.twentytwenty.js', array('jquery') );
            }


        }
    }

    static function templates() {

        do_action( 'playouts_get_public_templates' );

    }

}

function playouts_init_plugin() {
	Playouts_Public::init();
}
add_action( 'init', 'playouts_init_plugin' );
