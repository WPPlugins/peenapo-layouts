<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * declare elements
 *
 *
 */
class Playouts_Element {

    public $module;
    public $name;
    public $class_name;
    public $view;
    public $params = array();
    public $category = array();
    public $priority = 0;
    public $module_color;
    public $public = true;

    private static $modules_standard = array();
    private static $index = 0;

    function __construct() {

        self::$index++;

        $this->category = array( 'general' => __( 'General', 'peenapo-layouts-txd' ) );
        $this->class_name = get_class( $this );

        $this->init();

        self::$modules_standard[ $this->module ] = $this;

    }

	/*static function retrieve_fields() {
        return array();
    }*/

    static function output( $atts = array(), $content = null ) {
        return '';
    }

    static function get_modules() {
        return self::$modules_standard;
    }

    static function get_modules_arr() {

        $modules = array();
        foreach( self::get_modules() as $module ) {
            $modules[ $module->module ] = array(
                'name' => $module->name,
                'view' => $module->view,
                'category' => key( $module->category ),
                'public' => $module->public,

            );
        }
        return $modules;

    }

    static function get_modules_categories() {

        $categories = array();
        $modules = self::get_modules();
        foreach( $modules as $module ) {
            $category_id = key( $module->category );
            if( isset( $module->category[ $category_id ] ) ) {
                $categories[ $category_id ] = $module->category[ $category_id ];
            }
        }
        return $categories;

    }

    static function get_modules_color() {

        $modules_color = array();
        foreach( self::get_modules() as $module ) {
            $modules_color[ $module->module ] = $module->module_color;
        }
        return $modules_color;

    }

    static function get_modules_raw() {
        $modules = array();
        foreach( self::get_modules() as $module ) {
            $modules[] = $module->module;
        }
        return $modules;
    }

    static function get_module_view( $module ) {
        if( isset( self::$modules_standard[ $module ] ) ) {
            return self::$modules_standard[ $module ]->view;
        }
        return '__not_found';
    }
}

class Playouts_Repeater_Element extends Playouts_Element {

    public $module_item;

    private static $modules_repeater = array();

    function __construct() {

        parent::__construct();

        self::$modules_repeater[ $this->module ] = $this;

    }

    static function get_modules_repeater() {
        return self::$modules_repeater;
    }

    static function get_modules_repeater_raw() {
        $modules = array();
        foreach( self::get_modules_repeater() as $module ) {
            $modules[] = $module->module;
        }
        return $modules;
    }
}

class Playouts_Repeater_Item_Element extends Playouts_Element {

    public $module_parent;

    private static $modules_repeater_item = array();

    function __construct() {

        parent::__construct();

        self::$modules_repeater_item[ $this->module ] = $this;

        $this->public = false;
        $this->category = array();

    }

    static function get_modules_repeater_item() {
        return self::$modules_repeater_item;
    }

    static function get_modules_repeater_item_raw() {
        $modules = array();
        foreach( self::get_modules_repeater_item() as $module ) {
            $modules[] = $module->module;
        }
        return $modules;
    }
}

class Playouts_Element_Row extends Playouts_Element {

    static $vertical_alignment;

    function init() {

        $this->module = 'bw_row';
        $this->name = esc_html__( 'Row', 'peenapo-layouts-txd' );
        $this->view = 'row';
        $this->params = array(
            'dummy' => array( // do not remove
                'type' => 'dummy',
                'ui_remove' => true
            ),
            'is_hidden' => array(
                'type' => 'textfield',
                'ui_remove' => true
            ),
            'row_columns' => array( // do not remove
                'type' => 'columns',
                'tab' => array( 'row_columns' => esc_html__( 'Columns', 'peenapo-layouts-txd' ) ),
            ),
            'row_layout' => array(
				'label'             => esc_html__( 'Row Layout', 'peenapo-layouts-txd' ),
				'type'              => 'radio_image',
				'description'       => esc_html__( 'Select the display version of the row.', 'peenapo-layouts-txd' ),
				'options'           => array(
                    'standard' => array(
                        'label' => 'Standard', 'image' => PLAYOUTS_ASSEST . 'admin/images/__tmp/row_standard.png'
                    ),
                    'full' => array(
                        'label' => 'Full-Width', 'image' => PLAYOUTS_ASSEST . 'admin/images/__tmp/row_full_width.png'
                    ),
                    'boxed' => array(
                        'label' => 'Boxed', 'image' => PLAYOUTS_ASSEST . 'admin/images/__tmp/row_boxed.png'
                    ),
                ),
                'value' => 'standard'
			),
            'background' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background', 'peenapo-layouts-txd' ),
				'description'       => esc_html__( 'Select row background type', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
				'options'           => array(
                    '' => 'None',
                    'color' => 'Color',
                    'image' => 'Image',
                    'parallax' => 'Parallax',
                    'video' => 'Video',
                ),
			),
            'bg_color' => array(
                'type'              => 'colorpicker',
				'label'             => esc_html__( 'Background Color', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'color' ),
			),
            'bg_image' => array(
                'type'              => 'image',
				'label'             => esc_html__( 'Background Image', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
			),
            'bg_image_position' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Position', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    ''                  => 'Default',
                    'top left'          => 'Top Left',
                    'top center'        => 'Top Center',
                    'top right'         => 'Top Right',
                    'center left'       => 'Center Left',
                    'center center'     => 'Center Center',
                    'center right'      => 'Center Right',
                    'bottom left'       => 'Bottom Left',
                    'bottom center'     => 'Bottom Center',
                    'bottom right'      => 'Bottom Right',
                ),
                'width'             => 50
			),
            'bg_image_size' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Size', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    ''                  => 'Default',
                    'auto'              => 'Auto',
                    'cover'             => 'Cover',
                    'contain'           => 'Contain',
                ),
                'width'             => 50
			),
            'bg_parallax_speed' => array(
                'type'              => 'number_slider',
                'label'             => esc_html__( 'Parallax Speed', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'parallax' ),
                'min'               => 0,
                'max'               => 300,
                'step'              => 10,
                'value'             => '',
            ),
            'bg_video_mp4' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Mp4', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_ogv' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Ogv', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_webm' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Webm', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_poster' => array(
                'type'              => 'image',
                'label'             => esc_html__( 'Video Poster', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'overlay_enable' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Enable Overlay', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
            ),
            'overlay_bg_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Overlay Background Color', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
                'width'             => 50
            ),
            'overlay_bg_second' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Overlay Second Background Color ( Optional )', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'For gradient background color.', 'peenapo-layouts-txd' ),
                'value'             => '#f5f5f5',
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'width'             => 50
			),
            'overlay_direction' => array(
                'label'             => esc_html__( 'Overlay Gradient Direction', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'top right'         => 'Top Right',
                    'top'               => 'Top',
                    'left top'          => 'Top Left',
                    'bottom right'      => 'Bottom Right',
                    'bottom'            => 'Bottom',
                    'bottom left'       => 'Bottom Left',
                    'right'             => 'Right',
                    'left'              => 'Left',
                ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
            ),
            'overlay_opacity' => array(
                'label'             => esc_html__( 'Overlay Opacity', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'min'               => 0,
                'max'               => 100,
                'step'              => 1,
                'value'             => 50,
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
            ),
            'vertical_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Vertical Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    ''                      => 'None',
                    'flex-start'            => 'Top',
                    'center'                => 'Middle',
                    'flex-end'              => 'Bottom',
                ),
                'width'             => 50
			),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    ''                  => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'enable_static_height' => array(
                'label'             => esc_html__( 'Set Static Row Height', 'peenapo-layouts-txd' ),
                'type'              => 'true_false'
			),
            'static_height' => array(
                'type'              => 'number_slider',
                'label'             => esc_html__( 'Static Height in Percentage', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Row height, 100% = full window height', 'peenapo-layouts-txd' ),
                'depends'           => array( 'element' => 'enable_static_height', 'value' => '1' ),
                'append_after'      => '%',
                'min'               => 30,
                'max'               => 100,
                'step'              => 1,
                'value'             => '',
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'value'             => ''
            ),
            'animation' => array(
                'label'             => esc_html__( 'Animation', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    ''          => 'None',
                    'scale'     => 'Scale',
                    'top'       => 'Top',
                    'right'     => 'Right',
                    'left'      => 'Left',
                    'bottom'    => 'Bottom',
                ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'animation_speed' => array(
                'label'             => esc_html__( 'Animation Speed', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Item animation speed in milliseconds.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds.',
                'min'               => 0,
                'max'               => 1000,
                'step'              => 50,
                'depends'           => array( 'element' => 'animation', 'value' => array( 'scale', 'top', 'right', 'bottom', 'left' ) ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'animation_delay' => array(
                'label'             => esc_html__( 'Animation Delay', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Item animation speed in milliseconds.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds.',
                'min'               => 0,
                'max'               => 1500,
                'step'              => 50,
                'depends'           => array( 'element' => 'animation', 'value' => array( 'scale', 'top', 'right', 'bottom', 'left' ) ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),

            'res_hide_desktop' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Desktop', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution larger than <strong>1200px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 33
            ),
            'res_hide_tablet' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Tablet', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution between <strong>768px and 1199px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 33
            ),
            'res_hide_phone' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Phone', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution smaller than <strong>767px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 34
            ),

            'margin_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Top', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( "Don't include 'px' in your string. e.g '40' - For perecent value '%' would be needed at the end e.g '10%'.", 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'margin_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'padding_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Top', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Right', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Left', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array(), $content = null ) {

        self::$vertical_alignment = ( isset( $atts['vertical_alignment'] ) and $atts['vertical_alignment'] ) ? esc_attr( $atts['vertical_alignment'] ) : '';

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'row_layout'        => 'standard',
            'is_hidden'         => false,

            'background'        => '',
            'bg_color'          => '',
            'bg_image'          => '',
            'bg_image_position' => 'center center',
            'bg_image_size'     => 'cover',
            'bg_parallax_speed' => 150,
            'bg_video_mp4'      => '',
            'bg_video_ogv'      => '',
            'bg_video_webm'     => '',
            'bg_video_poster'   => '',
            'overlay_enable'    => false,
            'overlay_bg_color'  => '',
            'overlay_bg_second' => '',
            'overlay_direction' => 'top right',
            'overlay_opacity'   => 0,

            'text_color'        => '',
            'text_alignment'    => '',
            'enable_static_height' => false,
            'static_height'     => '30',
            'vertical_alignment' => '',

            'res_hide_desktop'  => '',
            'res_hide_tablet'   => '',
            'res_hide_phone'    => '',

            'margin_top'        => '',
            'margin_bottom'     => '',
            'padding_top'       => '',
            'padding_right'     => '',
            'padding_bottom'    => '',
            'padding_left'      => '',

            'animation'         => 'none',
            'animation_speed'   => 0,
            'animation_delay'   => 0,

            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        if( $is_hidden ) { return ''; }

        $style = $class = $id = $overlay = $_gradient = '';

        if( $enable_static_height ) { $style .= 'min-height:' . (int) $static_height . 'vh;'; }
        if( $text_color ) { $style .= 'color:' . esc_attr( $text_color ) . ';'; }
        if( $text_alignment ) { $style .= 'text-align:' . esc_attr( $text_alignment ) . ';'; }
        if( $margin_top ) { $style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_top ) { $style .= 'padding-top:' . esc_attr( $padding_top ) . ( is_numeric( $padding_top ) ? 'px' : '' ) . ';'; }
        if( $padding_right ) { $style .= 'padding-right:' . esc_attr( $padding_right ) . ( is_numeric( $padding_right ) ? 'px' : '' ) . ';'; }
        if( $padding_bottom ) { $style .= 'padding-bottom:' . esc_attr( $padding_bottom ) . ( is_numeric( $padding_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_left ) { $style .= 'padding-left:' . esc_attr( $padding_left ) . ( is_numeric( $padding_left ) ? 'px' : '' ) . ';'; }

        if( $res_hide_desktop ) { $class .= ' pl-res-hide-d'; }
        if( $res_hide_tablet ) { $class .= ' pl-res-hide-t'; }
        if( $res_hide_phone ) { $class .= ' pl-res-hide-p'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $overlay_enable ) {
            $_gradient .= 'background-color:' . esc_attr( $overlay_bg_color ) . ';';
            $_gradient .= 'opacity:' . $overlay_opacity * 0.01 . ';';
            if( $overlay_bg_second ) {
                $_gradient .= 'background:linear-gradient(to ' . esc_attr( $overlay_direction ) . ',' . esc_attr( $overlay_bg_color ) . ',' . esc_attr( $overlay_bg_second ) . ');';
            }
            $overlay = '<span class="pl-overlay" style="' . $_gradient . '"></span>';
        }

        // column animations
        $_anim_class = $_anim_attr = '';
        if( $animation and $animation !== 'none' ) {

            $_anim_class .= ' pl-animation-stagger';

            $_anim_attr .= ' data-animation="' . esc_attr( $animation ) . '"';
            $_anim_attr .= ' data-animation-speed="' . (int) $animation_speed . '"';
            $_anim_attr .= ' data-animation-delay="' . (int) $animation_delay . '"';

        }

        return '<div class="pl-row-outer pl-row-layout-' . $row_layout . $class . '"' . $id . '>'.
            Playouts_Public::set_background( $background, $assigned_atts ).
            $overlay.
            '<div class="pl-row' . $_anim_class . '" style="' . $style . '"' . $_anim_attr . '>'.
                $content.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Row;

class Playouts_Element_Column extends Playouts_Element {

    function init() {

        $this->module = 'bw_column';
        $this->name = esc_html__( 'Column', 'peenapo-layouts-txd' );
        $this->view = 'column';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->public = false;
        $this->params = array(
            'dummy' => array( // do not remove
                'type' => 'dummy',
                'ui_remove' => true
            ),
            'col_width' => array( // do not remove
                'type' => 'textfield',
                'ui_hide' => true
            ),
            'background' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background', 'peenapo-layouts-txd' ),
				'description'       => esc_html__( 'Select row background type', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
				'options'           => array(
                    ''          => 'None',
                    'color'     => 'Color',
                    'image'     => 'Image',
                    'parallax'  => 'Parallax',
                    'video'     => 'Video',
                )
			),
            'bg_color' => array(
                'type'              => 'colorpicker',
				'label'             => esc_html__( 'Background Color', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'color' ),
			),
            'bg_image' => array(
                'type'              => 'image',
				'label'             => esc_html__( 'Background Image', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
			),
            'bg_image_position' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Position', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    ''                  => 'Default',
                    'top left'          => 'Top Left',
                    'top center'        => 'Top Center',
                    'top right'         => 'Top Right',
                    'center left'       => 'Center Left',
                    'center center'     => 'Center Center',
                    'center right'      => 'Center Right',
                    'bottom left'       => 'Bottom Left',
                    'bottom center'     => 'Bottom Center',
                    'bottom right'      => 'Bottom Right',
                ),
                'width'             => 50
			),
            'bg_image_size' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Size', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    ''                  => 'Default',
                    'auto'              => 'Auto',
                    'cover'             => 'Cover',
                    'contain'           => 'Contain',
                ),
                'width'             => 50
			),
            'bg_parallax_speed' => array(
                'type'              => 'number_slider',
                'label'             => esc_html__( 'Parallax Speed', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'parallax' ),
                'min'               => 0,
                'max'               => 300,
                'step'              => 10,
                'value'             => '',
            ),
            'bg_video_mp4' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Mp4', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_ogv' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Ogv', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_webm' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Webm', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_poster' => array(
                'type'              => 'image',
                'label'             => esc_html__( 'Video Poster', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'overlay_enable' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Enable Overlay', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
            ),
            'overlay_bg_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Overlay Background Color', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
                'width'             => 50
            ),
            'overlay_bg_second' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Overlay Second Background Color ( Optional )', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'For gradient background color.', 'peenapo-layouts-txd' ),
                'value'             => '#f5f5f5',
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
                'width'             => 50
			),
            'overlay_direction' => array(
                'label'             => esc_html__( 'Overlay Gradient Direction', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'top right'         => 'Top Right',
                    'top'               => 'Top',
                    'left top'          => 'Top Left',
                    'bottom right'      => 'Bottom Right',
                    'bottom'            => 'Bottom',
                    'bottom left'       => 'Bottom Left',
                    'right'             => 'Right',
                    'left'              => 'Left',
                ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
            ),
            'overlay_opacity' => array(
                'label'             => esc_html__( 'Overlay Opacity', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'min'               => 0,
                'max'               => 100,
                'step'              => 1,
                'value'             => 50,
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'value'             => '',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    ''                  => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'width'             => 50
			),
            'column_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Column Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    ''                  => 'Auto',
                    'flex-start'        => 'Top',
                    'center'            => 'Middle',
                    'flex-end'          => 'Bottom',
                ),
			),

            'res_hide_desktop' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Desktop', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution larger than <strong>1200px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 33
            ),
            'res_hide_tablet' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Tablet', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution between <strong>768px and 1199px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 33
            ),
            'res_hide_phone' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Phone', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution smaller than <strong>767px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 34
            ),

            'margin_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Top', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'margin_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Right', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'margin_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'margin_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Left', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Top', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Right', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Left', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'col_width'         => '',

            'background'        => '',
            'bg_color'          => '',
            'bg_image'          => '',
            'bg_image_position' => 'center center',
            'bg_image_size'     => 'cover',
            'bg_parallax_speed' => 150,
            'bg_video_mp4'      => '',
            'bg_video_ogv'      => '',
            'bg_video_webm'     => '',
            'bg_video_poster'   => '',
            'overlay_enable'    => false,
            'overlay_bg_color'  => '',
            'overlay_bg_second' => '',
            'overlay_direction' => 'top right',
            'overlay_opacity'   => 0,

            'text_color'        => '',
            'text_alignment'    => '',
            'column_alignment'  => '',

            'res_hide_desktop'  => '',
            'res_hide_tablet'   => '',
            'res_hide_phone'    => '',

            'margin_top'        => '',
            'margin_right'      => '',
            'margin_bottom'     => '',
            'margin_left'       => '',
            'padding_top'       => '',
            'padding_right'     => '',
            'padding_bottom'    => '',
            'padding_left'      => '',

            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $style_inside = $class = $id = $overlay = $attr = $_gradient = '';

        if( $col_width ) { $style .= 'flex:' . Playouts_Functions::set_column_width( $col_width, $margin_left, $margin_right ) . ';'; }

        if( $text_color ) { $style .= 'color:' . esc_attr( $text_color ) . ';'; }
        if( $text_alignment ) { $style .= 'text-align:' . esc_attr( $text_alignment ) . ';'; }

        if( Playouts_Element_Row::$vertical_alignment ) { $style .= 'justify-content:' . esc_attr( Playouts_Element_Row::$vertical_alignment ) . ';'; }
        if( $column_alignment ) { $style_inside .= 'align-self:' . esc_attr( $column_alignment ) . ';'; }

        if( $margin_top ) { $style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_right ) { $style .= 'margin-right:' . esc_attr( $margin_right ) . ( is_numeric( $margin_right ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }
        if( $margin_left ) { $style .= 'margin-left:' . esc_attr( $margin_left ) . ( is_numeric( $margin_left ) ? 'px' : '' ) . ';'; }

        if( $padding_top ) { $style .= 'padding-top:' . esc_attr( $padding_top ) . ( is_numeric( $padding_top ) ? 'px' : '' ) . ';'; }
        if( $padding_right ) { $style .= 'padding-right:' . esc_attr( $padding_right ) . ( is_numeric( $padding_right ) ? 'px' : '' ) . ';'; }
        if( $padding_bottom ) { $style .= 'padding-bottom:' . esc_attr( $padding_bottom ) . ( is_numeric( $padding_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_left ) { $style .= 'padding-left:' . esc_attr( $padding_left ) . ( is_numeric( $padding_left ) ? 'px' : '' ) . ';'; }

        if( $res_hide_desktop ) { $class .= ' pl-res-hide-d'; }
        if( $res_hide_tablet ) { $class .= ' pl-res-hide-t'; }
        if( $res_hide_phone ) { $class .= ' pl-res-hide-p'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $overlay_enable ) {
            $_gradient .= 'background-color:' . esc_attr( $overlay_bg_color ) . ';';
            $_gradient .= 'opacity:' . $overlay_opacity * 0.01 . ';';
            if( $overlay_bg_second ) {
                $_gradient .= 'background:linear-gradient(to ' . esc_attr( $overlay_direction ) . ',' . esc_attr( $overlay_bg_color ) . ',' . esc_attr( $overlay_bg_second ) . ');';
            }
            $overlay = '<span class="pl-overlay" style="' . $_gradient . '"></span>';
        }

        $class .= Playouts_Functions::set_column_class( $col_width );

        return '<div class="pl-column-outer' . $class . '" style="' . $style . '"' . $id . $attr . '>'.
            Playouts_Public::set_background( $background, $assigned_atts ).
            $overlay.
            '<div class="pl-column" style="' . $style_inside . '">'.
                $content.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Column;

class Playouts_Element_Row_Inner extends Playouts_Element {

    static $vertical_alignment;

    function init() {

        $this->module = 'bw_row_inner';
        $this->name = esc_html__( 'Row Inner', 'peenapo-layouts-txd' );
        $this->view = 'row_inner';
        $this->params = array(
            'dummy' => array( // do not remove
                'type' => 'dummy',
                'ui_remove' => true
            ),
            'row_columns' => array( // do not remove
                'type'               => 'columns',
                'tab'                => array( 'row_columns' => esc_html__( 'Columns', 'peenapo-layouts-txd' ) ),
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'value'             => '',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'enable_static_height' => array(
                'label'             => esc_html__( 'Set Static Row Height', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
			),
            'static_height' => array(
                'type'              => 'number_slider',
                'label'             => esc_html__( 'Static Height in Percentage', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Row height, 100% = full window height', 'peenapo-layouts-txd' ),
                'depends'           => array( 'element' => 'enable_static_height', 'value' => '1' ),
                'append_after'      => '%',
                'min'               => 30,
                'max'               => 100,
                'step'              => 1,
                'value'             => '',
            ),
            'vertical_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Vertical Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    ''                      => 'None',
                    'flex-start'            => 'Top',
                    'center'                => 'Middle',
                    'flex-end'              => 'Bottom',
                ),
			),
            'padding_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Top', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Right', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Left', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'background' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background', 'peenapo-layouts-txd' ),
				'description'       => esc_html__( 'Select row background type', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
				'options'           => array(
                    '' => 'None',
                    'color' => 'Color',
                    'image' => 'Image',
                    'parallax' => 'Parallax',
                    'video' => 'Video',
                ),
			),
            'bg_color' => array(
                'type'              => 'colorpicker',
				'label'             => esc_html__( 'Background Color', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'color' ),
			),
            'bg_image' => array(
                'type'              => 'image',
				'label'             => esc_html__( 'Background Image', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
			),
            'bg_image_position' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Position', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    ''                  => 'Default',
                    'top left'          => 'Top Left',
                    'top center'        => 'Top Center',
                    'top right'         => 'Top Right',
                    'center left'       => 'Center Left',
                    'center center'     => 'Center Center',
                    'center right'      => 'Center Right',
                    'bottom left'       => 'Bottom Left',
                    'bottom center'     => 'Bottom Center',
                    'bottom right'      => 'Bottom Right',
                ),
                'width'             => 50
			),
            'bg_image_size' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Size', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    ''                  => 'Default',
                    'auto'              => 'Auto',
                    'cover'             => 'Cover',
                    'contain'           => 'Contain',
                ),
                'width'             => 50
			),
            'bg_parallax_speed' => array(
                'type'              => 'number_slider',
                'label'             => esc_html__( 'Parallax Speed', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'parallax' ),
                'min'               => 0,
                'max'               => 300,
                'step'              => 10,
                'value'             => '',
            ),
            'bg_video_mp4' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Mp4', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_ogv' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Ogv', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_webm' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Webm', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_poster' => array(
                'type'              => 'image',
                'label'             => esc_html__( 'Video Poster', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'overlay_enable' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Enable Overlay', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
            ),
            'overlay_bg_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Overlay Background Color', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
                'width'             => 50
            ),
            'overlay_bg_second' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Overlay Second Background Color ( Optional )', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'For gradient background color.', 'peenapo-layouts-txd' ),
                'value'             => '#f5f5f5',
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
                'width'             => 50
			),
            'overlay_direction' => array(
                'label'             => esc_html__( 'Overlay Gradient Direction', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'top right'         => 'Top Right',
                    'top'               => 'Top',
                    'left top'          => 'Top Left',
                    'bottom right'      => 'Bottom Right',
                    'bottom'            => 'Bottom',
                    'bottom left'       => 'Bottom Left',
                    'right'             => 'Right',
                    'left'              => 'Left',
                ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
            ),
            'overlay_opacity' => array(
                'label'             => esc_html__( 'Overlay Opacity', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'min'               => 0,
                'max'               => 100,
                'step'              => 1,
                'value'             => 50,
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
            ),
            'animation' => array(
                'label'             => esc_html__( 'Animation', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'none'      => 'None',
                    'scale'     => 'Scale',
                    'top'       => 'Top',
                    'right'     => 'Right',
                    'left'      => 'Left',
                    'bottom'    => 'Bottom',
                ),
                'value'             => 'none',
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'animation_speed' => array(
                'label'             => esc_html__( 'Animation Speed', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Item animation speed in milliseconds.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds.',
                'min'               => 50,
                'max'               => 1000,
                'step'              => 50,
                'value'             => 200,
                'depends'           => array( 'element' => 'animation', 'value' => array( 'scale', 'top', 'right', 'bottom', 'left' ) ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'animation_delay' => array(
                'label'             => esc_html__( 'Animation Delay', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Item animation speed in milliseconds.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds.',
                'min'               => 0,
                'max'               => 1500,
                'step'              => 50,
                'value'             => 100,
                'depends'           => array( 'element' => 'animation', 'value' => array( 'scale', 'top', 'right', 'bottom', 'left' ) ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),

            'res_hide_desktop' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Desktop', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution larger than <strong>1200px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 33
            ),
            'res_hide_tablet' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Tablet', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution between <strong>768px and 1199px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 33
            ),
            'res_hide_phone' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Phone', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution smaller than <strong>767px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 34
            ),

            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array(), $content = null ) {

        self::$vertical_alignment = ( isset( $atts['vertical_alignment'] ) and $atts['vertical_alignment'] ) ? esc_attr( $atts['vertical_alignment'] ) : '';

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'is_hidden'         => false,
            'text_color'        => '',
            'text_alignment'    => '',
            'enable_static_height' => false,
            'static_height'     => '30',
            'vertical_alignment' => '',
            'margin_top'        => '',
            'margin_bottom'     => '',
            'padding_top'       => '',
            'padding_right'     => '',
            'padding_bottom'    => '',
            'padding_left'      => '',

            'background'        => '',
            'bg_color'          => '',
            'bg_image'          => '',
            'bg_image_position' => 'center center',
            'bg_image_size'     => 'cover',
            'bg_parallax_speed' => 150,
            'bg_video_mp4'      => '',
            'bg_video_ogv'      => '',
            'bg_video_webm'     => '',
            'bg_video_poster'   => '',
            'overlay_enable'    => false,
            'overlay_bg_color'  => '',
            'overlay_bg_second' => '',
            'overlay_direction' => 'top right',
            'overlay_opacity'   => 0,

            'animation'         => 'none',
            'animation_speed'   => 200,
            'animation_delay'   => 0,

            'res_hide_desktop'  => '',
            'res_hide_tablet'   => '',
            'res_hide_phone'    => '',

            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $_gradient = '';

        if( $enable_static_height ) { $style .= 'min-height:' . (int) $static_height . 'vh;'; }
        if( $text_color ) { $style .= 'color:' . esc_attr( $text_color ) . ';'; }
        if( $text_alignment ) { $style .= 'text-align:' . esc_attr( $text_alignment ) . ';'; }
        if( $margin_top ) { $style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_top ) { $style .= 'padding-top:' . esc_attr( $padding_top ) . ( is_numeric( $padding_top ) ? 'px' : '' ) . ';'; }
        if( $padding_right ) { $style .= 'padding-right:' . esc_attr( $padding_right ) . ( is_numeric( $padding_right ) ? 'px' : '' ) . ';'; }
        if( $padding_bottom ) { $style .= 'padding-bottom:' . esc_attr( $padding_bottom ) . ( is_numeric( $padding_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_left ) { $style .= 'padding-left:' . esc_attr( $padding_left ) . ( is_numeric( $padding_left ) ? 'px' : '' ) . ';'; }

        if( $res_hide_desktop ) { $class .= ' pl-res-hide-d'; }
        if( $res_hide_tablet ) { $class .= ' pl-res-hide-t'; }
        if( $res_hide_phone ) { $class .= ' pl-res-hide-p'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        // column animations
        $_anim_class = $_anim_attr = $overlay = '';
        if( $animation and $animation !== 'none' ) {

            $_anim_class .= ' pl-animation-stagger';

            $_anim_attr .= ' data-animation="' . esc_attr( $animation ) . '"';
            $_anim_attr .= ' data-animation-speed="' . (int) $animation_speed . '"';
            $_anim_attr .= ' data-animation-delay="' . (int) $animation_delay . '"';

        }

        if( $overlay_enable ) {
            $_gradient .= 'background-color:' . esc_attr( $overlay_bg_color ) . ';';
            $_gradient .= 'opacity:' . $overlay_opacity * 0.01 . ';';
            if( $overlay_bg_second ) {
                $_gradient .= 'background:linear-gradient(to ' . esc_attr( $overlay_direction ) . ',' . esc_attr( $overlay_bg_color ) . ',' . esc_attr( $overlay_bg_second ) . ');';
            }
            $overlay = '<span class="pl-overlay" style="' . $_gradient . '"></span>';
        }

        return '<div class="pl-row-inner-outer' . $class . '"' . $id . '>'.
            Playouts_Public::set_background( $background, $assigned_atts ).
            $overlay.
            '<div class="pl-row-inner' . $_anim_class . '" style="' . $style . '"' . $_anim_attr . '>'.
                $content.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Row_Inner;

class Playouts_Element_Column_Inner extends Playouts_Element {

    function init() {

        $this->module = 'bw_column_inner';
        $this->name = esc_html__( 'Column Inner', 'peenapo-layouts-txd' );
        $this->view = 'column_inner';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->public = false;
        $this->params = array(
            'dummy' => array( // do not remove
                'type' => 'dummy',
                'ui_remove' => true
            ),
            'col_width' => array( // do not remove
                'type' => 'textfield',
                'ui_hide' => true
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'value'             => '',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'column_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Column Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    ''                  => 'Auto',
                    'flex-start'        => 'Top',
                    'center'            => 'Middle',
                    'flex-end'          => 'Bottom',
                ),
			),
            'padding_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Top', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Right', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'padding_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Left', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'margin_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Top', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'margin_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Right', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'margin_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'margin_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Left', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'background' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background', 'peenapo-layouts-txd' ),
				'description'       => esc_html__( 'Select row background type', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
				'options'           => array(
                    ''          => 'None',
                    'color'     => 'Color',
                    'image'     => 'Image',
                    'parallax'  => 'Parallax',
                    'video'     => 'Video',
                )
			),
            'bg_color' => array(
                'type'              => 'colorpicker',
				'label'             => esc_html__( 'Background Color', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'color' ),
			),
            'bg_image' => array(
                'type'              => 'image',
				'label'             => esc_html__( 'Background Image', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
			),
            'bg_image_position' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Position', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    ''                  => 'Default',
                    'top left'          => 'Top Left',
                    'top center'        => 'Top Center',
                    'top right'         => 'Top Right',
                    'center left'       => 'Center Left',
                    'center center'     => 'Center Center',
                    'center right'      => 'Center Right',
                    'bottom left'       => 'Bottom Left',
                    'bottom center'     => 'Bottom Center',
                    'bottom right'      => 'Bottom Right',
                ),
                'width'             => 50
			),
            'bg_image_size' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Background Size', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => array( 'image', 'parallax' ) ),
                'options'           => array(
                    ''                  => 'Default',
                    'auto'              => 'Auto',
                    'cover'             => 'Cover',
                    'contain'           => 'Contain',
                ),
                'width'             => 50
			),
            'bg_parallax_speed' => array(
                'type'              => 'number_slider',
                'label'             => esc_html__( 'Parallax Speed', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'parallax' ),
                'min'               => 0,
                'max'               => 300,
                'step'              => 10,
                'value'             => '',
            ),
            'bg_video_mp4' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Mp4', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_ogv' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Ogv', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_webm' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Video Webm', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'bg_video_poster' => array(
                'type'              => 'image',
                'label'             => esc_html__( 'Video Poster', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'background', 'value' => 'video' ),
            ),
            'overlay_enable' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Enable Overlay', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
            ),
            'overlay_bg_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Overlay Background Color', 'peenapo-layouts-txd' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
                'width'             => 50
            ),
            'overlay_bg_second' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Overlay Second Background Color ( Optional )', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'For gradient background color.', 'peenapo-layouts-txd' ),
                'value'             => '#f5f5f5',
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
                'width'             => 50
			),
            'overlay_direction' => array(
                'label'             => esc_html__( 'Overlay Gradient Direction', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'top right'         => 'Top Right',
                    'top'               => 'Top',
                    'left top'          => 'Top Left',
                    'bottom right'      => 'Bottom Right',
                    'bottom'            => 'Bottom',
                    'bottom left'       => 'Bottom Left',
                    'right'             => 'Right',
                    'left'              => 'Left',
                ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
            ),
            'overlay_opacity' => array(
                'label'             => esc_html__( 'Overlay Opacity', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'min'               => 0,
                'max'               => 100,
                'step'              => 1,
                'value'             => 50,
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'overlay_enable', 'value' => '1' ),
            ),

            'res_hide_desktop' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Desktop', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution larger than <strong>1200px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 33
            ),
            'res_hide_tablet' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Tablet', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution between <strong>768px and 1199px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 33
            ),
            'res_hide_phone' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Hide on Phone', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Hide this element for resolution smaller than <strong>767px</strong>.', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 34
            ),

            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'col_width'         => '',
            'text_color'        => '',
            'text_alignment'    => '',
            'column_alignment' => '',
            'padding_top'       => '',
            'padding_right'     => '',
            'padding_bottom'    => '',
            'padding_left'      => '',
            'column_alignment' => '',
            'margin_top'       => '',
            'margin_right'     => '',
            'margin_bottom'    => '',
            'margin_left'      => '',

            'background'        => '',
            'bg_color'          => '',
            'bg_image'          => '',
            'bg_image_position' => 'center center',
            'bg_image_size'     => 'cover',
            'bg_parallax_speed' => 150,
            'bg_video_mp4'      => '',
            'bg_video_ogv'      => '',
            'bg_video_webm'     => '',
            'bg_video_poster'   => '',
            'overlay_enable'    => false,
            'overlay_bg_color'  => '',
            'overlay_bg_second' => '',
            'overlay_direction' => 'top right',
            'overlay_opacity'   => 0,

            'res_hide_desktop'  => '',
            'res_hide_tablet'   => '',
            'res_hide_phone'    => '',

            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $overlay = $_gradient = $style_inside = '';

        if( $col_width ) { $style .= 'flex:' . Playouts_Functions::set_column_width( $col_width, $margin_left, $margin_right ) . ';'; }

        if( $text_color ) { $style .= 'color:' . esc_attr( $text_color ) . ';'; }
        if( $text_alignment ) { $style .= 'text-align:' . esc_attr( $text_alignment ) . ';'; }

        if( Playouts_Element_Row_Inner::$vertical_alignment ) { $style .= 'justify-content:' . esc_attr( Playouts_Element_Row_Inner::$vertical_alignment ) . ';'; }
        if( $column_alignment ) { $style_inside .= 'align-self:' . esc_attr( $column_alignment ) . ';'; }

        if( $padding_top ) { $style .= 'padding-top:' . esc_attr( $padding_top ) . ( is_numeric( $padding_top ) ? 'px' : '' ) . ';'; }
        if( $padding_right ) { $style .= 'padding-right:' . esc_attr( $padding_right ) . ( is_numeric( $padding_right ) ? 'px' : '' ) . ';'; }
        if( $padding_bottom ) { $style .= 'padding-bottom:' . esc_attr( $padding_bottom ) . ( is_numeric( $padding_bottom ) ? 'px' : '' ) . ';'; }
        if( $padding_left ) { $style .= 'padding-left:' . esc_attr( $padding_left ) . ( is_numeric( $padding_left ) ? 'px' : '' ) . ';'; }

        if( $margin_top ) { $style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_right ) { $style .= 'margin-right:' . esc_attr( $margin_right ) . ( is_numeric( $margin_right ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }
        if( $margin_left ) { $style .= 'margin-left:' . esc_attr( $margin_left ) . ( is_numeric( $margin_left ) ? 'px' : '' ) . ';'; }

        if( $res_hide_desktop ) { $class .= ' pl-res-hide-d'; }
        if( $res_hide_tablet ) { $class .= ' pl-res-hide-t'; }
        if( $res_hide_phone ) { $class .= ' pl-res-hide-p'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $overlay_enable ) {
            $_gradient .= 'background-color:' . esc_attr( $overlay_bg_color ) . ';';
            $_gradient .= 'opacity:' . $overlay_opacity * 0.01 . ';';
            if( $overlay_bg_second ) {
                $_gradient .= 'background:linear-gradient(to ' . esc_attr( $overlay_direction ) . ',' . esc_attr( $overlay_bg_color ) . ',' . esc_attr( $overlay_bg_second ) . ');';
            }
            $overlay = '<span class="pl-overlay" style="' . $_gradient . '"></span>';
        }

        $class .= Playouts_Functions::set_column_class( $col_width );

        return '<div class="pl-column-inner-outer' . $class . '" style="' . $style . '"' . $id . '>'.
            Playouts_Public::set_background( $background, $assigned_atts ).
            $overlay.
            '<div class="pl-column-inner" style="' . $style_inside . '">'.
                $content.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Column_Inner;

class Playouts_Element_Text extends Playouts_Element {

    function init() {

        $this->module = 'bw_text';
        $this->name = esc_html__( 'Text', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#77e08a';
        $this->params = array(
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'value'             => '',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    ''                  => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'content' => array(
				'label'             => esc_html__( 'Content', 'peenapo-layouts-txd' ),
				'type'              => 'editor',
				'is_content'        => true,
                'value'             => '<p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p>',
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'text_color'        => '',
            'text_alignment'    => 'inherit',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $style .= ! empty( $text_color ) ? 'color:' . esc_attr( $text_color ) . ';' : '';
        $style .= ! empty( $text_alignment ) ? 'text-align:' . esc_attr( $text_alignment ) . ';' : '';

        return '<div class="pl-text' . $class . '" style="' . $style . '"' . $id . '>'.
            do_shortcode( $content ) .
        '</div>';

    }
}
new Playouts_Element_Text;

class Playouts_Element_Audio extends Playouts_Element {

    function init() {

        $this->module = 'bw_audio';
        $this->name = esc_html__( 'Audio Player', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#62cdef';
        $this->params = array(
            'audio_file' => array(
                'type'              => 'file',
                'label'             => esc_html__( 'Audio File', 'peenapo-layouts-txd' ),
            ),
            'cover_top' => array(
                'label'             => esc_html__( 'Display Full Cover', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'title' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Title', 'peenapo-layouts-txd' ),
            ),
            'artist' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Artist', 'peenapo-layouts-txd' ),
            ),
            'cover_image' => array(
                'type'              => 'image',
                'label'             => esc_html__( 'Cover Image', 'peenapo-layouts-txd' ),
            ),
            'background_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Background Color', 'peenapo-layouts-txd' ),
                'width'             => 50
            ),
            'text_color' => array(
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    '#000'  => 'Dark',
                    '#fff'  => 'Light',
                ),
                'width'             => 50
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'audio_file'        => '',
            'cover_top'         => false,
            'title'             => '',
            'artist'            => '',
            'cover_image'       => '',
            'background_color'  => '',
            'text_color'        => '#000',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $cover = $heading = '';

        if( $cover_image ) {
            $cover = '<div class="pl-audio-cover" style="background-image:url(' . esc_url( $cover_image ) . ');"></div>';
        }
        if( $title ) {
            $heading .= '<h3>' . esc_html( $title ) . '</h3>';
        }
        if( $artist ) {
            $heading .= '<p>' . esc_html__( 'By: ', 'peenapo-layouts-txd' ) . '<strong>' . esc_html( $artist ) . '</strong></p>';
        }
        if( $text_color ) {
            $style .= 'color:' . esc_attr( $text_color ) . ';';
        }
        if( $background_color ) {
            $style .= 'background-color:' . esc_attr( $background_color ) . ';';
        }
        if( $cover_top ) {
            $class .= ' pl-cover-full';
        }

        $class .= ' pl-audio-text-color-' . ( $text_color == '#000' ? 'dark' : 'light' );

        return '<div class="pl-audio' . $class . '" style="' . $style . '"' . $id . '>'.
            $cover.
            '<div class="pl-audio-content">'.
            '<div class="pl-audio-heading">' . $heading . '</div>'.
            do_shortcode( '[audio src="' . esc_url( $audio_file ) . '"]' ).
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Audio;

class Playouts_Element_Accordion extends Playouts_Repeater_Element {

    function init() {

        $this->module = 'bw_accordion';
        $this->module_item = 'bw_accordion_item';
        $this->name = esc_html__( 'Accordion', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#be6ef6';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Tab items', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many tabs as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'close_other' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Close Other Items?', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'Enable this option if you want to close the rest of accordion items on mouse click.', 'peenapo-layouts-txd' ),
            ),
            'line_height' => array(
                'label'             => esc_html__( 'Titles Height', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 40,
                'max'               => 150,
                'step'              => 1,
                'value'             => 90,
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'close_other'       => false,
            'line_height'       => 90,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $close_other ) { $class .= ' pl-close-other'; }
        $style .= 'line-height:' . (int) $line_height . 'px;';

        return '<div class="pl-accordion' . $class . '" style="' . $style . '"' . $id . '>' . $content . '</div>';

    }
}
new Playouts_Element_Accordion;

class Playouts_Element_Accordion_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_accordion_item';
        $this->module_parent = 'bw_accordion';
        $this->name = esc_html__( 'Accordion Item', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'title' => array(
				'label'              => esc_html__( 'Title', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
			),
            'active' => array(
                'label'             => esc_html__( 'Active by Default?', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'content' => array(
				'label'             => esc_html__( 'Content', 'peenapo-layouts-txd' ),
				'type'              => 'editor',
				'is_content'        => true,
                'value'             => 'Accordion item. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.',
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'title'             => '',
            'active'            => false,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $active ) { $class .= ' pl-active'; }

        return '<div class="pl-accirdion-item' . $class . '" style="' . $style . '"' . $id . '>'.
            '<div class="pl-accordion-title pl-no-select"><strong><div class="pl-accordion-toggle"><i></i></div>' . esc_html( $title ) . '</strong></div>'.
            '<div class="pl-accordion-content"' . ( $active ? ' style="height:auto;"' : '' ) . '>'.
                '<div class="pl-accordion-content-inner">'.
                    do_shortcode( $content ).
                '</div>'.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Accordion_Item;

class Playouts_Element_Tabs extends Playouts_Repeater_Element {

    function init() {

        $this->module = 'bw_tabs';
        $this->module_item = 'bw_tab_item';
        $this->name = esc_html__( 'Tabs', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#be6ef6';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Tab items', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many tabs as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'nav_border' => array(
                'label'             => esc_html__( 'Enable Tabs Bottom Border', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'line_height' => array(
                'label'             => esc_html__( 'Tab Line Height', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 40,
                'max'               => 100,
                'step'              => 1,
                'value'             => 65,
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array(), $content = null ) {

        Playouts_Element_Tab_Item::$tabs = array();

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'nav_border'        => false,
            'line_height'       => 65,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $nav_border ) { $class .= ' pl-bottom-border'; }

        $tabs_output = '<ul class="pl-tab-nav" style="line-height:' . (int) $line_height . 'px;">';
        $c = 0;
        foreach( Playouts_Element_Tab_Item::$tabs as $tab_id => $tab ) {
            $tabs_output .= '<li' . ( $c == 0 ? ' class="pl-active"' : '' ) . '><a href="#tab-' . $tab_id . '">' . esc_attr( $tab ) . '</a></li>';
            $c++;
        }
        $tabs_output .= '<li class="pl-nav-border"></li>';
        $tabs_output .= '</ul>';

        return '<div class="pl-tabs' . $class . '" style="' . $style . '"' . $id . '>' . $tabs_output . $content . '</div>';

    }
}
new Playouts_Element_Tabs;

class Playouts_Element_Tab_Item extends Playouts_Repeater_Item_Element {

    static $tabs = array();

    function init() {

        $this->module = 'bw_tab_item';
        $this->module_parent = 'bw_tabs';
        $this->name = esc_html__( 'Tab', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'title' => array(
                'type'               => 'textfield',
				'label'              => esc_html__( 'Title', 'peenapo-layouts-txd' ),
				'value'              => esc_html__( 'Tab title', 'peenapo-layouts-txd' ),
			),
            'content' => array(
                'type'              => 'editor',
				'label'             => esc_html__( 'Content', 'peenapo-layouts-txd' ),
				'is_content'        => true,
                'value'             => 'Accordion item. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.',
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'title'             => '',
            'inline_class'      => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $tab_id = Playouts_Shortcode_Parser::get_unique_id();

        $class .= empty( self::$tabs ) ? ' pl-active' : '';

        self::$tabs[ $tab_id ] = $title;

        return '<div id="tab-' . $tab_id . '" class="pl-tab-section' . $class . '" style="' . $style . '">'.
            do_shortcode( $content ).
        '</div>';

    }
}
new Playouts_Element_Tab_Item;

class Playouts_Element_Progress_Bars extends Playouts_Repeater_Element {

    static $enable_animation;

    function init() {

        $this->module = 'bw_progress_bars';
        $this->module_item = 'bw_progress_bar';
        $this->name = esc_html__( 'Progress Bars', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#72cdf3';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Tab items', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many tabs as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'enable_animation' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Enable Animation', 'peenapo-layouts-txd' ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'animation_speed' => array(
                'label'             => esc_html__( 'Animation Speed', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Item animation speed in milliseconds.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds.',
                'min'               => 50,
                'max'               => 1000,
                'step'              => 50,
                'value'             => 150,
                'depends'           => array( 'element' => 'enable_animation', 'value' => '1' ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'animation_delay' => array(
                'label'             => esc_html__( 'Animation Delay', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Animation delay between bars in milliseconds.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds.',
                'min'               => 10,
                'max'               => 300,
                'step'              => 10,
                'value'             => 80,
                'depends'           => array( 'element' => 'enable_animation', 'value' => '1' ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array(), $content = null ) {

        self::$enable_animation = ( isset( $atts['enable_animation'] ) and $atts['enable_animation'] ) ? true : false;

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'enable_animation'  => false,
            'animation_speed'   => 0,
            'animation_delay'   => 0,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $attr = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $enable_animation and $animation_speed and $animation_delay ) {
            $class .= ' pl-is-animated';
            $attr .= ' data-animation-speed="' . (int) $animation_speed * 0.001 . '"';
            $attr .= ' data-animation-delay="' . (int) $animation_delay * 0.001 . '"';
        }

        if( ! empty( $content ) ) {
            return '<div class="pl-progress-bars' . $class . '" style="' . $style . '"' . $id . $attr . '>' . $content . '</div>';
        }
    }
}
new Playouts_Element_Progress_Bars;

class Playouts_Element_Progress_Bars_Item extends Playouts_Repeater_Item_Element {

    static $tabs = array();

    function init() {

        $this->module = 'bw_progress_bar';
        $this->module_parent = 'bw_progress_bars';
        $this->name = esc_html__( 'Progress Bar', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'title' => array(
				'label'              => esc_html__( 'Title', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
				'value'              => esc_html__( 'Some title goes here', 'peenapo-layouts-txd' ),
			),
            'value' => array(
				'label'              => esc_html__( 'Value ( Optional )', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
			),
            'progress' => array(
                'label'             => esc_html__( 'Progress', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'description'       => esc_html__( 'Some description', 'peenapo-layouts-txd' ),
                'append_after'      => '%',
                'min'               => 0,
                'max'               => 100,
                'step'              => 1,
                'value'             => 50,
            ),
            'bar_color' => array(
                'label'             => esc_html__( 'Bar Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'bar_color_secondary' => array(
                'label'             => esc_html__( 'Bar Secondary Color ( Optional )', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'For gradient background color.', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'bar_bg_color' => array(
                'label'             => esc_html__( 'Bar Background Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'text_color' => array(
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'counter_color' => array(
                'label'             => esc_html__( 'Counter Text Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'direction' => array(
                'label'             => esc_html__( 'Gradient Direction', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'top'               => 'Top',
                    'top right'         => 'Top Right',
                    'right'             => 'Right',
                    'bottom right'      => 'Bottom Right',
                    'bottom'            => 'Bottom',
                    'bottom left'       => 'Bottom Left',
                    'left'              => 'Left',
                    'left top'      => 'Top Left',
                ),
                'value'             => 'right',
                'width'             => 50
            ),
            'padding_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Top', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'padding_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Padding Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'title'             => '',
            'value'             => '',
            'progress'          => 0,
            'bar_color'         => '',
            'bar_color_secondary' => '',
            'direction'         => 'right',
            'bar_bg_color'      => '',
            'text_color'        => '',
            'counter_color'     => '',
            'padding_top'       => '',
            'padding_bottom'    => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $_bg = '';

        if( $padding_top ) { $style .= 'padding-top:' . esc_attr( $padding_top ) . ( is_numeric( $padding_top ) ? 'px' : '' ) . ';'; }
        if( $padding_bottom ) { $style .= 'padding-bottom:' . esc_attr( $padding_bottom ) . ( is_numeric( $padding_bottom ) ? 'px' : '' ) . ';'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $_bg .= 'background-color:' . esc_attr( $bar_color ) . ';';
        if( $bar_color_secondary ) {
            $_bg .= 'background:linear-gradient(to ' . esc_attr( $direction ) . ',' . esc_attr( $bar_color ) . ',' . esc_attr( $bar_color_secondary ) . ');';
        }

        $enable_animation = Playouts_Element_Progress_Bars::$enable_animation;

        return '<div data-progress="' . (int) $progress . '" class="pl-progress-bar' . $class . '" style="' . $style . '"' . $id . '>'.
            '<div class="pl-progress-label"' . ( $text_color ? ' style="color:' . esc_attr( $text_color ) . '"' : '' ) . '>' . esc_attr( $title ) . '</div>'.
            '<div class="pl-the-progress"' . ( $bar_bg_color ? ' style="background-color:' . esc_attr( $bar_bg_color ) . '"' : '' ) . '>'.
                '<span class="pl-the-bar" style="' . $_bg . ( ! $enable_animation ? 'width:' . (int) $progress . '%;' : '' ) . '">'.
                    '<span class="pl-progress-counter" style="' . ( $counter_color ? 'color:' . esc_attr( $counter_color ) . ';' : '' ) . ( ! $enable_animation ? 'opacity:1;' : '' ) . '"><em>' . (int) $progress . '</em>' . esc_attr( $value ) . '</span>'.
                '</span>'.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Progress_Bars_Item;

class Playouts_Element_Button extends Playouts_Element {

    function init() {

        $this->module = 'bw_button';
        $this->name = esc_html__( 'Button', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#8d61f2';

        $this->params = array(
            'label' => array(
				'label'              => esc_html__( 'Label', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
				'value'              => esc_html__( 'This is a button', 'peenapo-layouts-txd' ),
			),
            'link' => array(
				'label'              => esc_html__( 'Link', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
				'placeholder'        => 'http://',
			),
            'target' => array(
                'label'             => esc_html__( 'Open in a New Tab?', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'style' => array(
                'label'             => esc_html__( 'Style', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'extra_small'       => 'Extra Small',
                    'small'             => 'Small',
                    'medium'            => 'Medium',
                    'large'             => 'Large',
                    'extra_large'       => 'Extra Large',
                    'huge'              => 'Huge',
                ),
                'value'             => 'medium'
            ),
            'bold' => array(
                'label'             => esc_html__( 'Bold Text?', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'bg_color' => array(
                'label'             => esc_html__( 'Background Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'bg_color_second' => array(
                'label'             => esc_html__( 'Second Background Color ( Optional )', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'For gradient background color.', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'direction' => array(
                'label'             => esc_html__( 'Gradient Direction', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'top'               => 'Top',
                    'top right'         => 'Top Right',
                    'right'             => 'Right',
                    'bottom right'      => 'Bottom Right',
                    'bottom'            => 'Bottom',
                    'bottom left'       => 'Bottom Left',
                    'left'              => 'Left',
                    'left top'          => 'Top Left',
                ),
                'value'             => 'bottom right',
            ),
            'text_color' => array(
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
            ),
            'border_radius' => array(
                'label'             => esc_html__( 'Border Radius', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 0,
                'max'               => 60,
                'step'              => 1,
                'value'             => 3,
            ),
            'transform_top' => array(
                'label'             => esc_html__( 'Transform Top Position', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'tab'               => array( 'hover' => esc_html__( 'Hover Styles', 'peenapo-layouts-txd' ) ),
            ),
            'shadow' => array(
                'label'             => esc_html__( 'Add Shadow', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'tab'               => array( 'hover' => esc_html__( 'Hover Styles', 'peenapo-layouts-txd' ) ),
            ),
            'hover_bg_color' => array(
                'label'             => esc_html__( 'Background Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'tab'               => array( 'hover' => esc_html__( 'Hover Styles', 'peenapo-layouts-txd' ) ),
            ),
            'hover_text_color' => array(
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'tab'               => array( 'hover' => esc_html__( 'Hover Styles', 'peenapo-layouts-txd' ) ),
            ),
            'margin_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Top', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'margin_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Right', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'margin_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'margin_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Left', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'label'             => '',
            'link'              => '',
            'target'            => false,
            'style'             => 'medium',
            'bold'              => false,
            'bg_color'          => '#f93d66',
            'bg_color_second'   => '',
            'direction'         => 'bottom right',
            'text_color'        => '',
            'border_radius'     => 0,
            'transform_top'     => false,
            'shadow'            => false,
            'hover_bg_color'    => '',
            'hover_text_color'  => '',
            'margin_top'        => '',
            'margin_right'      => '',
            'margin_bottom'     => '',
            'margin_left'       => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $_style = $class = $id = $attr = $_gradient = '';

        if( $margin_top ) { $_style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_right ) { $_style .= 'margin-right:' . esc_attr( $margin_right ) . ( is_numeric( $margin_right ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $_style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }
        if( $margin_left ) { $_style .= 'margin-left:' . esc_attr( $margin_left ) . ( is_numeric( $margin_left ) ? 'px' : '' ) . ';'; }

        $class .= ' pl-button-style-' . esc_attr( $style );
        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $class .= $transform_top ? ' pl-button-transform-top' : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';

        $_style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';
        $_style .= ! empty( $border_radius ) ? 'border-radius:' . (int) $border_radius . 'px;' : '';
        $_style .= ! empty( $text_color ) ? 'color:' . esc_attr( $text_color ) . ';' : '';
        $_style .= $bold ? 'font-weight:bold;' : '';

        $attr .= $target ? ' target="_blank"' : '';
        $attr .= $hover_bg_color ? ' data-hover-bg-color-override="' . esc_attr( $hover_bg_color ) . '"' : '';
        $attr .= $hover_text_color ? ' data-hover-text-color-override="' . esc_attr( $hover_text_color ) . '"' : '';
        $shadow_color = $hover_bg_color ? $hover_bg_color : $bg_color;
        $attr .= $shadow ? ' data-hover-shadow-override="' . esc_attr( $shadow_color ) . '"' : '';

        $_gradient .= 'background-color:' . esc_attr( $bg_color ) . ';';
        $_gradient .= 'background:linear-gradient(to ' . esc_attr( $direction ) . ',' . esc_attr( $bg_color ) . ',' . esc_attr( $bg_color_second ) . ');';

        return '<a href="' . esc_url( $link ) . '" class="pl-button' . $class . '" style="' . $_style . $_gradient . '"' . $id . $attr . '>'.
            esc_attr( $label ).
        '</a>';

    }
}
new Playouts_Element_Button;

class Playouts_Element_Code extends Playouts_Element {

    function init() {

        $this->module = 'bw_code';
        $this->name = esc_html__( 'Code', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#e2de7d';

        $this->params = array(
            'content' => array(
				'label'             => esc_html__( 'Code', 'peenapo-layouts-txd' ),
				'type'              => 'textarea',
				'is_content'        => true,
                'value'             => '&lt;div class="example"&gt;
    ' . esc_html__( 'Some code goes here..', 'peenapo-layouts-txd' ) . '
&lt;/div&gt;',
			),
            'margin_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Top', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'margin_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'margin_top'        => '',
            'margin_bottom'     => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        if( $margin_top ) { $style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';

        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        return '<pre class="pl-code' . $class . '" style="' . $style . '"' . $id . '>'.
            htmlentities( $content ).
        '</pre>';

    }
}
new Playouts_Element_Code;

class Playouts_Element_Sidebar extends Playouts_Element {

    function init() {

        $this->module = 'bw_sidebar';
        $this->name = esc_html__( 'Sidebar', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#3d60f9';

        $this->params = array(
            'sidebar' => array(
                'type'              => 'sidebars',
                'label'             => esc_html__( 'Select Sidebar', 'peenapo-layouts-txd' ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'sidebar'           => 0,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        ob_start();

        echo '<div class="pl-sidebar' . $class . '" style="' . $style . '"' . $id . '>';
            dynamic_sidebar( $sidebar );
        echo '</div>';

        return ob_get_clean();

    }
}
new Playouts_Element_Sidebar;

if( in_array( 'contact-form-7/wp-contact-form-7.php', Playouts_Bootstrap::$active_plugins ) ) {
    class Playouts_Element_Contact_Form_7 extends Playouts_Element {

        function init() {

            $this->module = 'bw_contact_form_7';
            $this->name = esc_html__( 'Contact Form 7', 'peenapo-layouts-txd' );
            $this->view = 'element';
            $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
            $this->module_color = '#15dcc7';

            global $wpdb;

            $forms = $wpdb->get_results("
                SELECT $wpdb->posts.*
                FROM $wpdb->posts, $wpdb->postmeta
                WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
                AND $wpdb->postmeta.meta_key = '_form'
                AND $wpdb->posts.post_status = 'publish'
                AND $wpdb->posts.post_type = 'wpcf7_contact_form'
                ORDER BY $wpdb->posts.post_name DESC
            ");

            $form_options = array();
            if( $forms ) {
                $form_options[0] = __( 'Select Form', 'peenapo-layouts-txd' );
                foreach ( $forms as $form ) {
                    $form_options[ (int)$form->ID ] = esc_attr( $form->post_title );
                }
            }else{
                $form_options[0] = __( 'No contact forms found.', 'peenapo-layouts-txd' );
            }

            $this->params = array(
                'form_id' => array(
                    'type'              => 'select',
                    'label'             => esc_html__( 'Select Contact Form', 'peenapo-layouts-txd' ),
                    'options'           => $form_options,
                ),
            );

        }

        static function output( $atts = array(), $content = null ) {

            extract( $assigned_atts = shortcode_atts( array(
                'form_id'           => 0,
                'inline_class'      => '',
                'inline_id'         => '',
                'inline_css'        => '',
            ), $atts ) );

            $style = $class = $id = '';

            $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
            $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
            $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

            return '<div class="pl-contact-form-7' . $class . '" style="' . $style . '"' . $id . '>'.
                do_shortcode( '[contact-form-7 id="' . (int) $form_id . '"]' ).
            '</div>';

        }
    }
    new Playouts_Element_Contact_Form_7;
}

class Playouts_Element_Divider extends Playouts_Element {

    function init() {

        $this->module = 'bw_divider';
        $this->name = esc_html__( 'Divider', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#1593dc';

        $this->params = array(
            'height' => array(
                'label'             => esc_html__( 'Separator height', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels.',
                'min'               => 10,
                'max'               => 300,
                'step'              => 5,
                'value'             => 60,
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'height'            => 0,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';
        $style .= ! empty( $height ) ? 'height:' . (int) $height . 'px;' : '';

        ob_start();

        echo '<div class="pl-divider' . $class . '" style="' . $style . '"' . $id . '></div>';

        return ob_get_clean();

    }
}
new Playouts_Element_Divider;

class Playouts_Element_Image extends Playouts_Element {

    function init() {

        $this->module = 'bw_image';
        $this->name = esc_html__( 'Image', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#c5e375';

        $this->params = array(
            'image' => array(
                'type'              => 'image',
				'label'             => esc_html__( 'Image', 'peenapo-layouts-txd' ),
			),
            'alt_tag' => array(
                'type'              => 'textfield',
				'label'             => esc_html__( 'Alt Tag ( Optional )', 'peenapo-layouts-txd' ),
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'image'             => '',
            'alt_tag'           => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        return '<div class="pl-image' . $class . '" style="' . $style . '"' . $id . '>'.
            '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $alt_tag ) . '">'.
        '</div>';

    }
}
new Playouts_Element_Image;

class Playouts_Element_Auto_Type extends Playouts_Element {

    function init() {

        $this->module = 'bw_auto_type';
        $this->module_item = 'bw_auto_type_item';
        $this->name = esc_html__( 'Auto Type Heading', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#75a9e3';

        $this->params = array(
            'items' => array(
                'label'              => esc_html__( 'Texts', 'peenapo-layouts-txd' ),
                'type'               => 'repeater',
                'description'        => esc_html__( 'You can add as many texts as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'static_heading' => array(
                'label'              => esc_html__( 'Static Heading', 'peenapo-layouts-txd' ),
                'type'               => 'textfield',
                'value'              =>  esc_html__( 'This is the main title', 'peenapo-layouts-txd' ),
            ),
            'h_tag' => array(
                'label'             => esc_html__( 'Select Heading Title Tag', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                ),
                'value'             => 'h2'
            ),
            'font_size' => array(
                'label'             => esc_html__( 'Font Size', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 14,
                'max'               => 150,
                'step'              => 1,
                'value'             => 75,
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
				'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
			),
            'auto_type_color' => array(
                'type'              => 'colorpicker',
				'label'             => esc_html__( 'Auto Type Color', 'peenapo-layouts-txd' ),
			),
            'bold_text' => array(
                'label'             => esc_html__( 'Bold Text', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'static_heading'    => '',
            'h_tag'             => 'h2',
            'font_size'         => 75,
            'text_color'        => '',
            'auto_type_color'   => '',
            'bold_text'         => false,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';
        $style .= ! empty( $font_size ) ? 'font-size:' . (int) $font_size . 'px;' : '';
        $style .= ! empty( $text_color ) ? 'color:' . esc_attr( $text_color ) . ';' : '';
        $style .= $bold_text ? 'font-weight:800;' : '';

        $id = 'pl-auto-type-' . Playouts_Shortcode_Parser::get_unique_id();

        return '<div class="pl-auto-type-holder' . $class . '" style="' . $style . '" id="' . $id . '">'.
            '<' . esc_attr( $h_tag ) . '>'.
                '<em>' . wp_kses( $static_heading, array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )) . '</em>'.
                '<span class="pl-auto-type" style="' . ( ! empty( $auto_type_color ) ? 'color:' . esc_attr( $auto_type_color ) . ';' : '' ) . '"></span>'.
            '</' . esc_attr( $h_tag ) . '>'.
            '<ul class="pl-auto-type-texts">' . $content . '</ul>'.
        '</div>';

    }
}
new Playouts_Element_Auto_Type;

class Playouts_Element_Auto_Type_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_auto_type_item';
        $this->module_item = 'bw_auto_type';
        $this->name = esc_html__( 'Auto Type Text', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );

        $this->params = array(
            'text' => array(
                'label'             => esc_html__( 'Text', 'peenapo-layouts-txd' ),
                'type'              => 'textfield',
                'value'             => esc_html__( 'Auto type text here.', 'peenapo-layouts-txd' ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'text'              => '',
        ), $atts ) );

        return '<li>' . esc_attr( $text ) . '</li>';

    }
}
new Playouts_Element_Auto_Type_Item;

class Playouts_Element_Testimonials extends Playouts_Repeater_Element {

    static $layout;
    static $bg_color;
    static $text_color;

    function init() {

        $this->module = 'bw_testimonials';
        $this->module_item = 'bw_testimonial_item';
        $this->name = esc_html__( 'Testimonial Slider', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#70acf1';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Testimonials', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many items as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'layout' => array(
                'label'             => esc_html__( 'Layout', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'standard'  => 'Standard',
                    'box'       => 'Box',
                ),
                'value'             => 'some2'
            ),
            'slide_width' => array(
                'label'             => esc_html__( 'Slides Width', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '%',
                'min'               => 20,
                'max'               => 100,
                'step'              => 1,
                'value'             => 35,
            ),
            'adaptive_height' => array(
                'label'             => esc_html__( 'Enable Adaptive Height', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
            ),
            'group_slides_enable' => array(
                'label'             => esc_html__( 'Enable Slides Grouping', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Groups cells together in slides', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'group_slides' => array(
                'label'             => esc_html__( 'Group Slides', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'slides',
                'min'               => 2,
                'max'               => 5,
                'step'              => 1,
                'value'             => 2,
                'depends'           => array( 'element' => 'group_slides_enable', 'value' => '1' ),
            ),
            'autoplay_enable' => array(
                'label'             => esc_html__( 'Enable Auto-play', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'autoplay' => array(
                'label'             => esc_html__( 'Auto-play Timeout', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Advance cells ever {Number} milliseconds. 1500 will advance cells every 1.5 seconds', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'slides',
                'min'               => 1000,
                'max'               => 10000,
                'step'              => 500,
                'value'             => 4000,
                'depends'           => array( 'element' => 'autoplay_enable', 'value' => '1' ),
            ),
            'stop_autoplay_hover' => array(
                'label'             => esc_html__( 'Stop Auto-playing on Hover', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Auto-playing will pause when the user hovers over the carousel', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'depends'           => array( 'element' => 'autoplay_enable', 'value' => '1' ),
            ),
            'pagination_enable' => array(
                'label'             => esc_html__( 'Enable Dot Pagination', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'width'             => 50
            ),
            'infinite' => array(
                'label'             => esc_html__( 'Infinite Loop', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'At the end of cells, wrap-around to the other end for infinite scrolling.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'width'             => 50
            ),
            'has_focus' => array(
                'label'             => esc_html__( 'Enable Focus Accent', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Enable this option to add accent color to focused slides.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'width'             => 50
            ),
            'invert_color' => array(
                'label'             => esc_html__( 'Invret Colors', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'enable this options if you use a dark background.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'width'             => 50
            ),
            'bg_color' => array(
                'label'             => esc_html__( 'Background Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'text_color' => array(
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'animation_speed' => array(
                'label'             => esc_html__( 'Custom Animation Speed', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Set custom slider speed.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'width'             => 50
            ),
            'fine_text' => array(
                'label'             => esc_html__( 'Fine Text', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'This will make your content text finer.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'width'             => 50
            ),
            'attraction' => array(
                'label'             => esc_html__( 'Attraction', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Attracts the position of the slider to the selected cell. Higher attraction makes the slider move faster. Lower makes it move slower.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '',
                'min'               => 0.005,
                'max'               => 0.3,
                'step'              => 0.01,
                'value'             => 0.025,
                'depends'           => array( 'element' => 'animation_speed', 'value' => '1' ),
            ),
            'friction' => array(
                'label'             => esc_html__( 'Friction', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Slows the movement of slider. Higher friction makes the slider feel stickier and less bouncy. Lower friction makes the slider feel looser and more wobbly.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '',
                'min'               => 0.05,
                'max'               => 0.9,
                'step'              => 0.01,
                'value'             => 0.28,
                'depends'           => array( 'element' => 'animation_speed', 'value' => '1' ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array(), $content = null ) {

        self::$layout = ( isset( $atts['layout'] ) and ! empty( $atts['layout'] ) ) ? esc_attr( $atts['layout'] ) : 'standard';
        self::$bg_color = ( isset( $atts['bg_color'] ) and ! empty( $atts['bg_color'] ) ) ? esc_attr( $atts['bg_color'] ) : '';
        self::$text_color = ( isset( $atts['text_color'] ) and ! empty( $atts['text_color'] ) ) ? esc_attr( $atts['text_color'] ) : '';

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'layout'                => 'standard',
            'slide_width'           => 35,
            'adaptive_height'       => false,
            'group_slides_enable'   => false,
            'group_slides'          => false,
            'autoplay_enable'       => false,
            'autoplay'              => 4000,
            'stop_autoplay_hover'   => false,
            'pagination_enable'     => false,
            'infinite'              => false,
            'has_focus'             => false,
            'invert_color'          => false,
            'fine_text'             => false,
            'animation_speed'       => false,
            'attraction'            => 0.025,
            'friction'              => 0.28,
            'inline_class'          => '',
            'inline_id'             => '',
            'inline_css'            => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $class .= ' pl-layout-' . esc_attr( $layout );
        $class .= $pagination_enable ? ' pl-is-pagination' : '';
        $class .= $has_focus ? ' pl-has-focus' : '';
        $class .= $invert_color ? ' pl-invert-color' : '';
        $class .= $fine_text ? ' pl-finer' : ' pl-not-finer';

        $attr  = $adaptive_height ? ' data-adaptive-height="true"' : '';
        $attr .= ( $group_slides_enable and $group_slides > 1 ) ? ' data-group="' . (int) $group_slides . '"' : '';
        $attr .= ( $autoplay_enable ) ? ' data-autoplay="' . (int) $autoplay . '"' : '';
        $attr .= ( $autoplay_enable and $stop_autoplay_hover ) ? ' data-autoplay-stop="true"' : '';
        $attr .= $slide_width ? ' data-slide-width="' . (int) $slide_width . '"' : '';
        $attr .= $infinite ? ' data-infinite="true"' : '';
        $attr .= $pagination_enable ? ' data-pagination="true"' : '';
        if( $animation_speed ) {
            $attr .= $attraction ? ' data-attraction="' . esc_attr( $attraction ) . '"' : '';
            $attr .= $friction ? ' data-friction="' . esc_attr( $friction ) . '"' : '';
        }

        if( ! empty( $content ) ) {
            return '<div class="pl-testimonials pl-slider' . $class . '" style="' . $style . '"' . $id . $attr . '>' . $content . '</div>';
        }

    }
}
new Playouts_Element_Testimonials;

class Playouts_Element_Testimonial_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_testimonial_item';
        $this->module_parent = 'bw_testimonials';
        $this->name = esc_html__( 'Testimonial Slide', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'name' => array(
				'label'              => esc_html__( 'Name', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
                'value'             => esc_html__( 'Testimonial name', 'peenapo-layouts-txd' ),
			),
            'title' => array(
				'label'              => esc_html__( 'Title', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
                'value'             => esc_html__( 'Testimonial title', 'peenapo-layouts-txd' ),
			),
            'enable_star_rating' => array(
                'label'             => esc_html__( 'Enable Star Rating', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Your can add star rating if you want', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'star_rating' => array(
                'label'             => esc_html__( 'Star Rating', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'stars',
                'min'               => 0,
                'max'               => 5,
                'step'              => 0.5,
                'value'             => 3.5,
                'depends'           => array( 'element' => 'enable_star_rating', 'value' => '1' ),
            ),
            'content' => array(
				'label'             => esc_html__( 'Content', 'peenapo-layouts-txd' ),
				'type'              => 'textarea',
				'is_content'        => true,
                'value'             => 'Testimonial item. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.',
			),
            'thumb' => array(
                'type'              => 'image',
				'label'             => esc_html__( 'Thumbnail', 'peenapo-layouts-txd' ),
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'name'              => '',
            'title'             => '',
            'enable_star_rating' => false,
            'star_rating'       => 0,
            'thumb'             => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $image = $style_content = $_title = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';
        $style_content .= ! empty( Playouts_Element_Testimonials::$bg_color ) ? 'background-color:' . esc_attr( Playouts_Element_Testimonials::$bg_color ) . ';' : '';
        $style_content .= ! empty( Playouts_Element_Testimonials::$text_color ) ? 'color:' . esc_attr( Playouts_Element_Testimonials::$text_color ) . ';' : '';

        if( ! empty( $thumb ) ) {

            $class .= ' pl-has-image';
            $image_id = Playouts_Functions::get_image_id_from_url( $thumb );
            $image_thumbnail = Playouts_Functions::get_size_by_attachment_id( $image_id );

            if( ! empty( $image_thumbnail ) ) {
                $image = '<span class="pl-testimonial-image"><img src="' . esc_url( $image_thumbnail ) . '" alt="' . esc_html( $name ) . '"></span>';
            }
        }

        if( ! empty( $title ) ) {
            $_title = '<span class="pl-testimonial-title">' . esc_html( $title ) . '</span>';
        }

        $_data_box = $_data_standard = $_rating_box = $_rating_standard = '';

        if( Playouts_Element_Testimonials::$layout == 'box' ) {

            if( $enable_star_rating ) {
                $_rating_box .= '<span class="pl-star-rating"><span style="width:' . ( $star_rating * 20 ) . '%" class="pl-rating-fill"></span></span>';
            }

            if( ! empty( $name ) or ! empty( $title ) or ! empty( $image ) ) {
                $_data_box = '<div class="pl-testimonial-data">'.
                    $image.
                    '<span class="pl-data-wrap">'.
                    '<span class="pl-testimonial-name">' . esc_html( $name ) . '</span>'.
                    $_title.
                    '</span>'.
                '</div>';
            }

        }else{

            if( $enable_star_rating ) {
                $_rating_standard .= '<span class="pl-star-rating"><span style="width:' . ( $star_rating * 20 ) . '%" class="pl-rating-fill"></span></span>';
            }

            $_data_standard = '<div class="pl-testimonial-data">'.
                $image.
                '<span class="pl-testimonial-name">' . esc_html( $name ) . '</span>'.
                $_title.
                $_rating_standard.
            '</div>';

        }



        return '<blockquote class="pl-testimonial-item' . $class . '" style="' . $style . '"' . $id . '>'.
            '<div class="pl-testimonial-content pl-flickity-focus" style="' . $style_content . '">'.
                $_data_box.
                $_rating_box.
                '<p>' . esc_html( $content ) . '</p>'.
                '<span class="pl-testimonial-border"><span><span style="border-color:' . esc_attr( Playouts_Element_Testimonials::$bg_color ) . '"></span></span></span>'.
            '</div>'.
            $_data_standard.
        '</blockquote>';

    }
}
new Playouts_Element_Testimonial_Item;

class Playouts_Element_Clients extends Playouts_Repeater_Element {

    static $items_per_row;

    function init() {

        $this->module = 'bw_clients';
        $this->module_item = 'bw_clients_item';
        $this->name = esc_html__( 'Clients', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#de8686';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Client Items', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many items as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'items_per_row' => array(
                'label'             => esc_html__( 'Items Per Row', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'clients per row',
                'min'               => 1,
                'max'               => 8,
                'step'              => 1,
                'value'             => 5,
            ),
            'enable_animation' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Enable Animation', 'peenapo-layouts-txd' ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'animation_speed' => array(
                'label'             => esc_html__( 'Animation Speed', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Item animation speed in milliseconds.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds.',
                'min'               => 100,
                'max'               => 1000,
                'step'              => 50,
                'value'             => 400,
                'depends'           => array( 'element' => 'enable_animation', 'value' => '1' ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'animation_delay' => array(
                'label'             => esc_html__( 'Animation Delay', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'The appearance delay between each item in milliseconds.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds.',
                'min'               => 100,
                'max'               => 1000,
                'step'              => 50,
                'value'             => 100,
                'depends'           => array( 'element' => 'enable_animation', 'value' => '1' ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array() ) {

        self::$items_per_row = ( isset( $atts['items_per_row'] ) and ! empty( $atts['items_per_row'] ) ) ? esc_attr( $atts['items_per_row'] ) : 5;

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'enable_animation'      => '',
            'animation_speed'       => 400,
            'animation_delay'       => 100,
            'inline_class'          => '',
            'inline_id'             => '',
            'inline_css'            => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $enable_animation ) {
            $class .= ' pl-animated-appearance';
            $attr .= ' data-animation-speed="' . (int) $animation_speed . '"';
            $attr .= ' data-animation-delay="' . (int) $animation_delay . '"';
        }

        if( ! empty( $content ) ) {
            return '<div class="pl-clients' . $class . '" style="' . $style . '"' . $id . $attr . '>' . $content . '</div>';
        }

    }
}
new Playouts_Element_Clients;

class Playouts_Element_Clients_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_clients_item';
        $this->module_parent = 'bw_clients';
        $this->name = esc_html__( 'Client', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'image' => array(
                'type'               => 'image',
				'label'              => esc_html__( 'Client Image', 'peenapo-layouts-txd' ),
			),
            'enable_link' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Link?', 'peenapo-layouts-txd' ),
            ),
            'url' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Url', 'peenapo-layouts-txd' ),
                'value'             => '',
                'placeholder'       => 'http://',
                'depends'           => array( 'element' => 'enable_link', 'value' => '1' ),
            ),
            'new_tab' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Open in a New Tab?', 'peenapo-layouts-txd' ),
                'depends'           => array( 'element' => 'enable_link', 'value' => '1' ),
            ),
            'image_alt' => array(
                'type'               => 'textfield',
				'label'              => esc_html__( 'Alt Tag for Image ( Optional )', 'peenapo-layouts-txd' ),
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'image'             => '',
            'enable_link'       => false,
            'url'               => '',
            'new_tab'           => false,
            'image_alt'         => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $attr = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $style .= 'width:' . ( 100 / (int) Playouts_Element_Clients::$items_per_row ) . '%;';

        if( ! empty( $image ) ) {
        $_tag = 'div';
        $_link = '';
        if( $enable_link ) {
            $_tag = 'a';
            $_link .= ' href="' . esc_url( $url ) . '"';
            if( $new_tab ) {
                $_link .= ' target="_blank"';
            }
        }

        return '<div class="pl-clients-item' . $class . '" style="' . $style . '"' . $id . $attr . '>'.
                '<' . $_tag . $_link . ' class="pl-clients-outer">'.
                    '<img src="' . esc_url( $image ) . '" alt="' . esc_html( $image_alt ) . '">'.
                '</' . $_tag . '>'.
            '</div>';
        }

    }
}
new Playouts_Element_Clients_Item;

class Playouts_Element_Clients_Slider extends Playouts_Repeater_Element {

    function init() {

        $this->module = 'bw_clients_slider';
        $this->module_item = 'bw_clients_slider_item';
        $this->name = esc_html__( 'Clients Slider', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#de8686';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Client Items', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many items as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'slide_width' => array(
                'label'             => esc_html__( 'Slides Width', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '%',
                'min'               => 20,
                'max'               => 100,
                'step'              => 1,
                'value'             => 23,
            ),
            'adaptive_height' => array(
                'label'             => esc_html__( 'Enable Adaptive Height', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
            ),
            'group_slides_enable' => array(
                'label'             => esc_html__( 'Enable Slides Grouping', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Groups cells together in slides', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'group_slides' => array(
                'label'             => esc_html__( 'Group Slides', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'slides',
                'min'               => 2,
                'max'               => 5,
                'step'              => 1,
                'value'             => 2,
                'depends'           => array( 'element' => 'group_slides_enable', 'value' => '1' ),
            ),
            'autoplay_enable' => array(
                'label'             => esc_html__( 'Enable Auto-play', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'autoplay' => array(
                'label'             => esc_html__( 'Auto-play Timeout', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Advance cells ever {Number} milliseconds. 1500 will advance cells every 1.5 seconds', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'slides',
                'min'               => 1000,
                'max'               => 10000,
                'step'              => 500,
                'value'             => 4000,
                'depends'           => array( 'element' => 'autoplay_enable', 'value' => '1' ),
            ),
            'stop_autoplay_hover' => array(
                'label'             => esc_html__( 'Stop Auto-playing on Hover', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Auto-playing will pause when the user hovers over the carousel', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'depends'           => array( 'element' => 'autoplay_enable', 'value' => '1' ),
            ),
            'pagination_enable' => array(
                'label'             => esc_html__( 'Enable Dot Pagination', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'width'             => 50
            ),
            'infinite' => array(
                'label'             => esc_html__( 'Infinite Loop', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'At the end of cells, wrap-around to the other end for infinite scrolling.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'width'             => 50
            ),
            'animation_speed' => array(
                'label'             => esc_html__( 'Custom Animation Speed', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Set custom slider speed.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'width'             => 50
            ),
            'invert_color' => array(
                'label'             => esc_html__( 'Invret Colors', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'enable this options if you use a dark background.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'width'             => 50
            ),
            'attraction' => array(
                'label'             => esc_html__( 'Attraction', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Attracts the position of the slider to the selected cell. Higher attraction makes the slider move faster. Lower makes it move slower.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '',
                'min'               => 0.005,
                'max'               => 0.3,
                'step'              => 0.01,
                'value'             => 0.025,
                'depends'           => array( 'element' => 'animation_speed', 'value' => '1' ),
            ),
            'friction' => array(
                'label'             => esc_html__( 'Friction', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Slows the movement of slider. Higher friction makes the slider feel stickier and less bouncy. Lower friction makes the slider feel looser and more wobbly.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '',
                'min'               => 0.05,
                'max'               => 0.9,
                'step'              => 0.01,
                'value'             => 0.28,
                'depends'           => array( 'element' => 'animation_speed', 'value' => '1' ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'slide_width'           => 23,
            'adaptive_height'       => false,
            'group_slides_enable'   => false,
            'group_slides'          => false,
            'autoplay_enable'       => false,
            'autoplay'              => 4000,
            'stop_autoplay_hover'   => false,
            'pagination_enable'     => false,
            'infinite'              => false,
            'animation_speed'       => false,
            'invert_color'          => false,
            'attraction'            => 0.025,
            'friction'              => 0.28,
            'inline_class'          => '',
            'inline_id'             => '',
            'inline_css'            => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $class .= $pagination_enable ? ' pl-is-pagination' : '';
        $class .= $invert_color ? ' pl-invert-color' : '';

        $attr  = $adaptive_height ? ' data-adaptive-height="true"' : '';
        $attr .= ( $group_slides_enable and $group_slides > 1 ) ? ' data-group="' . (int) $group_slides . '"' : '';
        $attr .= ( $autoplay_enable ) ? ' data-autoplay="' . (int) $autoplay . '"' : '';
        $attr .= ( $autoplay_enable and $stop_autoplay_hover ) ? ' data-autoplay-stop="true"' : '';
        $attr .= $slide_width ? ' data-slide-width="' . (int) $slide_width . '"' : '';
        $attr .= $infinite ? ' data-infinite="true"' : '';
        $attr .= $pagination_enable ? ' data-pagination="true"' : '';
        if( $animation_speed ) {
            $attr .= $attraction ? ' data-attraction="' . esc_attr( $attraction ) . '"' : '';
            $attr .= $friction ? ' data-friction="' . esc_attr( $friction ) . '"' : '';
        }

        if( ! empty( $content ) ) {
            return '<div class="pl-clients-slider pl-slider' . $class . '" style="' . $style . '"' . $id . $attr . '>' . $content . '</div>';
        }

    }
}
new Playouts_Element_Clients_Slider;

class Playouts_Element_Clients_Slider_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_clients_slider_item';
        $this->module_parent = 'bw_clients_slider';
        $this->name = esc_html__( 'Client Slide', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'image' => array(
                'type'               => 'image',
				'label'              => esc_html__( 'Client Image', 'peenapo-layouts-txd' ),
			),
            'enable_link' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Link?', 'peenapo-layouts-txd' ),
            ),
            'url' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Url', 'peenapo-layouts-txd' ),
                'value'             => '',
                'placeholder'       => 'http://',
                'depends'           => array( 'element' => 'enable_link', 'value' => '1' ),
            ),
            'new_tab' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Open in a New Tab?', 'peenapo-layouts-txd' ),
                'depends'           => array( 'element' => 'enable_link', 'value' => '1' ),
            ),
            'image_alt' => array(
                'type'               => 'textfield',
				'label'              => esc_html__( 'Alt Tag for Image ( Optional )', 'peenapo-layouts-txd' ),
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'image'             => '',
            'enable_link'       => false,
            'url'               => '',
            'new_tab'           => false,
            'image_alt'         => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( ! empty( $image ) ) {
        $_tag = 'div';
        $_link = '';
        if( $enable_link ) {
            $_tag = 'a';
            $_link .= ' href="' . esc_url( $url ) . '"';
            if( $new_tab ) {
                $_link .= ' target="_blank"';
            }
        }
        return '<div class="pl-clients-slider-item' . $class . '" style="' . $style . '"' . $id . '>'.
                '<' . $_tag . $_link . ' class="pl-clients-outer">'.
                    '<img src="' . esc_url( $image ) . '" alt="' . esc_html( $image_alt ) . '">'.
                '</' . $_tag . '>'.
            '</div>';
        }

    }
}
new Playouts_Element_Clients_Slider_Item;

class Playouts_Element_Image_Stack extends Playouts_Repeater_Element {

    function init() {

        $this->module = 'bw_image_stack';
        $this->module_item = 'bw_image_stack_item';
        $this->name = esc_html__( 'Image Stack', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#7b73a6';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Client Items', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many items as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'inline_class'          => '',
            'inline_id'             => '',
            'inline_css'            => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( ! empty( $content ) ) {
            return '<div class="pl-image-stack' . $class . '" style="' . $style . '"' . $id . '>' . $content . '</div>';
        }

    }
}
new Playouts_Element_Image_Stack;

class Playouts_Element_Image_Stack_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_image_stack_item';
        $this->module_parent = 'bw_image_stack';
        $this->name = esc_html__( 'Image Stack Item', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'image' => array(
                'type'               => 'image',
				'label'              => esc_html__( 'Client Image', 'peenapo-layouts-txd' ),
			),
            'position_top' => array(
                'label'             => esc_html__( 'Top Position', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'description'       => esc_html__( 'Set the top ( vertical ) position of the image.', 'peenapo-layouts-txd' ),
                'append_after'      => '%',
                'min'               => -50,
                'max'               => 50,
                'step'              => 1,
                'value'             => 0,
            ),
            'position_left' => array(
                'label'             => esc_html__( 'Left Position', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'description'       => esc_html__( 'Set the left ( horizontal ) position of the image.', 'peenapo-layouts-txd' ),
                'append_after'      => '%',
                'min'               => -50,
                'max'               => 50,
                'step'              => 1,
                'value'             => 0,
            ),
            'shadow' => array(
                'type'               => 'true_false',
				'label'              => esc_html__( 'Enable Shadow', 'peenapo-layouts-txd' ),
			),
            'image_alt' => array(
                'type'               => 'textfield',
				'label'              => esc_html__( 'Alt Tag for Image ( Optional )', 'peenapo-layouts-txd' ),
			),
            'animation' => array(
                'label'             => esc_html__( 'Animation', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'none'      => 'None',
                    'scale'     => 'Scale',
                    'top'       => 'Top',
                    'right'     => 'Right',
                    'left'      => 'Left',
                    'bottom'    => 'Bottom',
                ),
                'value'             => 'none',
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'animation_delay' => array(
                'label'             => esc_html__( 'Animation Delay', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Appearance delay in milliseconds.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds.',
                'min'               => 0,
                'max'               => 1000,
                'step'              => 50,
                'value'             => 0,
                'depends'           => array( 'element' => 'animation', 'value' => array( 'scale', 'top', 'right', 'bottom', 'left' ) ),
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'image'             => '',
            'position_top'      => 0,
            'position_left'     => 0,
            'shadow'            => false,
            'image_alt'         => '',
            'animation'         => 'none',
            'animation_delay'   => 0,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $class_outer = $id = $attr = $style_inner = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $transform = 'transform:translateY(' . (int) $position_top . '%) translateX(' . (int) $position_left . '%);';

        if( $shadow ) {
            $style_inner .= 'box-shadow:0 55px 120px rgba(0,0,0,0.1), 0 16px 72px rgba(0,0,0,0.11);';
        }

        if( ! empty( $animation ) and $animation !== 'none' ) {
            $class_outer .= ' pl-animation';
            $attr .= ' data-animation="' . esc_attr( $animation ) . '"';
            $attr .= ' data-animation-delay="' . (int) $animation_delay . '"';
        }

        return '<div class="pl-image-stack-item' . $class . '" style="' . $style . '"' . $id . '>'.
            '<div class="pl-image-stack-outer' . $class_outer . '"' . $attr . '>'.
                '<div class="pl-image-stack-inner" style="' . $transform . '-webkit-' . $transform . $style_inner . '">'.
                    '<img src="' . esc_url( $image ) . '" alt="' . esc_html( $image_alt ) . '">'.
                '</div>'.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Image_Stack_Item;

class Playouts_Element_Video_Modal extends Playouts_Element {

    function init() {

        $this->module = 'bw_video_modal';
        $this->name = esc_html__( 'Video Modal', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#f07373';
        $this->params = array(
            'url' => array(
				'label'             => esc_html__( 'Video Url', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Only Youtube video support', 'peenapo-layouts-txd' ),
				'type'              => 'textfield',
				'placeholder'       => 'http://',
				'is_content'        => true,
			),
            'size' => array(
                'label'             => esc_html__( 'Select Video Screen Size', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Select the size of the video popup', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'small'     => 'Small',
                    'medium'    => 'Medium',
                    'large'     => 'Large',
                ),
                'value'             => 'medium',
                'width'             => 50
            ),
            'size_button' => array(
                'label'             => esc_html__( 'Select Button Size', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'small'     => 'Small',
                    'medium'    => 'Medium',
                    'large'     => 'Large',
                ),
                'value'             => 'medium',
                'width'             => 50
            ),
            'autoplay' => array(
                'label'             => esc_html__( 'Autoplay Video', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Color', 'peenapo-layouts-txd' ),
                'value'             => '',
                'width'             => 50,
            ),
            'bg_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Background Color ( Optional )', 'peenapo-layouts-txd' ),
                'value'             => '',
                'width'             => 50,
            ),
            'text' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Text Label', 'peenapo-layouts-txd' ),
                'value'             => '',
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'url'               => '',
            'size'              => 'medium',
            'size_button'       => 'medium',
            'autoplay'          => false,
            'color'             => '',
            'bg_color'          => '',
            'text'              => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $attr = $border_style = $svg_style = $_text = $_bg = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $class .= ' pl-button-size-' . esc_attr( $size_button );

        $attr .= ' data-screen-size="' . esc_attr( $size ) . '"';
        $attr .= $autoplay ? ' data-autoplay="true"' : '';

        $border_style .= ! empty( $color ) ? 'border-color:' . esc_attr( $color ) . ';' : '';
        $svg_style .= ! empty( $color ) ? 'fill:' . esc_attr( $color ) . ';' : '';

        if( ! empty( $text ) ) {
            $_text .= '<span style="color:' . esc_attr( $color ) . '">' . esc_html( $text ) . '</span>';
        }
        if( ! empty( $bg_color ) ) {
            $_bg = '<span class="pl-video-button-background" style="background-color:' . esc_attr( $bg_color ) . '"></span>';
        }

        return '<a href="' . esc_url( $content ) .  '" class="pl-video-modal' . $class . '" style="' . $style . '"' . $id . $attr . '>'.
            '<div class="pl-video-button">'.
                '<span class="pl-before" style="' . $border_style . '"></span>'.
                '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="600px" height="800px" x="0px" y="0px" viewBox="0 0 600 800" enable-background="new 0 0 600 800" xml:space="preserve"><path style="' . $svg_style . '" fill="none" d="M0-1.79v800L600,395L0-1.79z"></path></svg>'.
                '<span class="pl-after" style="' . $border_style . '"></span>'.
                $_bg.
            '</div>'.
            $_text.
        '</a>';

    }
}
new Playouts_Element_Video_Modal;

class Playouts_Element_Image_Slider extends Playouts_Repeater_Element {

    static $spacing;
    static $thumbnail_size;
    static $lazy;

    function init() {

        $this->module = 'bw_image_slider';
        $this->module_item = 'bw_image_slider_item';
        $this->name = esc_html__( 'Image Slider', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#9485e8';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Image Slides', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many items as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'slide_width' => array(
                'label'             => esc_html__( 'Slides Width', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '%',
                'min'               => 20,
                'max'               => 100,
                'step'              => 1,
                'value'             => 72,
            ),
            'spacing' => array(
                'label'             => esc_html__( 'Slides Spacing', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'Set the space between the slides.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 0,
                'max'               => 100,
                'step'              => 1,
                'value'             => 25,
            ),
            'thumbnail_size' => array(
                'label'             => esc_html__( 'Image Thumbnail Size', 'peenapo-layouts-txd' ),
                'type'              => 'thumbnail_sizes',
                'value'             => 'large',
            ),
            'adaptive_height' => array(
                'label'             => esc_html__( 'Enable Adaptive Height', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
            ),
            'lazy' => array(
                'label'             => esc_html__( 'Lazy Image Load', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'It will load the images only when needed.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'free' => array(
                'label'             => esc_html__( 'Free Scroll', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Enables content to be freely scrolled without aligning cells to an end position.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'group_slides_enable' => array(
                'label'             => esc_html__( 'Enable Slides Grouping', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Groups cells together in slides', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'group_slides' => array(
                'label'             => esc_html__( 'Group Slides', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'slides',
                'min'               => 2,
                'max'               => 5,
                'step'              => 1,
                'value'             => 2,
                'depends'           => array( 'element' => 'group_slides_enable', 'value' => '1' ),
            ),
            'autoplay_enable' => array(
                'label'             => esc_html__( 'Enable Auto-play', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'autoplay' => array(
                'label'             => esc_html__( 'Auto-play Timeout', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Advance cells ever {Number} milliseconds. 1500 will advance cells every 1.5 seconds', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'slides',
                'min'               => 1000,
                'max'               => 10000,
                'step'              => 500,
                'value'             => 4000,
                'depends'           => array( 'element' => 'autoplay_enable', 'value' => '1' ),
            ),
            'stop_autoplay_hover' => array(
                'label'             => esc_html__( 'Stop Auto-playing on Hover', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Auto-playing will pause when the user hovers over the carousel', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'depends'           => array( 'element' => 'autoplay_enable', 'value' => '1' ),
            ),
            'pagination_enable' => array(
                'label'             => esc_html__( 'Enable Dot Pagination', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'width'             => 50
            ),
            'infinite' => array(
                'label'             => esc_html__( 'Infinite Loop', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'At the end of cells, wrap-around to the other end for infinite scrolling.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
                'width'             => 50
            ),
            'animation_speed' => array(
                'label'             => esc_html__( 'Custom Animation Speed', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Set custom slider speed.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'width'             => 50
            ),
            'invert_color' => array(
                'label'             => esc_html__( 'Invret Colors', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Enable this options if you use a dark background.', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'width'             => 50
            ),
            'attraction' => array(
                'label'             => esc_html__( 'Attraction', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Attracts the position of the slider to the selected cell. Higher attraction makes the slider move faster. Lower makes it move slower.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '',
                'min'               => 0.005,
                'max'               => 0.3,
                'step'              => 0.01,
                'value'             => 0.025,
                'depends'           => array( 'element' => 'animation_speed', 'value' => '1' ),
            ),
            'friction' => array(
                'label'             => esc_html__( 'Friction', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Slows the movement of slider. Higher friction makes the slider feel stickier and less bouncy. Lower friction makes the slider feel looser and more wobbly.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '',
                'min'               => 0.05,
                'max'               => 0.9,
                'step'              => 0.01,
                'value'             => 0.28,
                'depends'           => array( 'element' => 'animation_speed', 'value' => '1' ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array(), $content = null ) {

        self::$spacing = ( isset( $atts['spacing'] ) and ! empty( $atts['spacing'] ) ) ? (int) $atts['spacing'] : 0;
        self::$thumbnail_size = ( isset( $atts['thumbnail_size'] ) and ! empty( $atts['thumbnail_size'] ) ) ? $atts['thumbnail_size'] : 'large';
        self::$lazy = ( isset( $atts['lazy'] ) and ! empty( $atts['lazy'] ) ) ? $atts['lazy'] : false;

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'slide_width'           => 23,
            'adaptive_height'       => false,
            'lazy'                  => false,
            'free'                  => false,
            'group_slides_enable'   => false,
            'group_slides'          => false,
            'autoplay_enable'       => false,
            'autoplay'              => 4000,
            'stop_autoplay_hover'   => false,
            'pagination_enable'     => false,
            'infinite'              => false,
            'animation_speed'       => false,
            'invert_color'          => false,
            'attraction'            => 0.025,
            'friction'              => 0.28,
            'inline_class'          => '',
            'inline_id'             => '',
            'inline_css'            => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $class .= $pagination_enable ? ' pl-is-pagination' : '';
        $class .= $invert_color ? ' pl-invert-color' : '';
        $class .= $lazy ? ' pl-is-lazy' : '';

        $attr  = $adaptive_height ? ' data-adaptive-height="true"' : '';
        $attr .= ( $group_slides_enable and $group_slides > 1 ) ? ' data-group="' . (int) $group_slides . '"' : '';
        $attr .= ( $autoplay_enable ) ? ' data-autoplay="' . (int) $autoplay . '"' : '';
        $attr .= ( $autoplay_enable and $stop_autoplay_hover ) ? ' data-autoplay-stop="true"' : '';
        $attr .= $slide_width ? ' data-slide-width="' . (int) $slide_width . '"' : '';
        $attr .= $infinite ? ' data-infinite="true"' : '';
        $attr .= $pagination_enable ? ' data-pagination="true"' : '';
        $attr .= $free ? ' data-free="true"' : '';
        if( $animation_speed ) {
            $attr .= $attraction ? ' data-attraction="' . esc_attr( $attraction ) . '"' : '';
            $attr .= $friction ? ' data-friction="' . esc_attr( $friction ) . '"' : '';
        }

        if( ! empty( $content ) ) {
            return '<div class="pl-image-slider pl-slider' . $class . '" style="' . $style . '"' . $id . $attr . '>' . $content . '</div>';
        }

    }
}
new Playouts_Element_Image_Slider;

class Playouts_Element_Image_Slider_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_image_slider_item';
        $this->module_parent = 'bw_image_slider';
        $this->name = esc_html__( 'Image Slide', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'image' => array(
                'type'               => 'image',
				'label'              => esc_html__( 'Image', 'peenapo-layouts-txd' ),
			),
            'image_alt' => array(
                'type'               => 'textfield',
				'label'              => esc_html__( 'Alt Tag for Image ( Optional )', 'peenapo-layouts-txd' ),
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'image'             => '',
            'image_alt'         => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $spacing = Playouts_Element_Image_Slider::$spacing;
        if( (int) $spacing > 0 ) {
            $style .= 'margin:0 ' . (int) $spacing . 'px!important;';
        }

        $thumbnail_size = Playouts_Element_Image_Slider::$thumbnail_size;

        if( empty( $image ) ) { return ''; }

        $image_sized = wp_get_attachment_image_src( Playouts_Functions::get_image_id_from_url( $image ), $thumbnail_size );
        if( ! isset( $image_sized[0] ) ) {
            $image_sized[0] = $image;
        }

        if( ! Playouts_Element_Image_Slider::$lazy ) {
            $_image = '<img src="' . esc_url( $image_sized[0] ) . '" alt="' . esc_html( $image_alt ) . '">';
        }else{
            $_image = '<img src="' . PLAYOUTS_ASSEST . 'images/pixel.png" data-flickity-lazyload="' . esc_url( $image_sized[0] ) . '" alt="' . esc_html( $image_alt ) . '">';
        }

        return '<div class="pl-image-slide' . $class . '" style="' . $style . '"' . $id . '>'.
            '<div class="pl-image-slide-inner">'.
                $_image.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Image_Slider_Item;

class Playouts_Element_Animated_Text extends Playouts_Repeater_Element {

    static $tag;

    function init() {

        $this->module = 'bw_animated_text';
        $this->module_item = 'bw_animated_text_item';
        $this->name = esc_html__( 'Animated Text', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#d27ab0';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Text Lines', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many text lines as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'h_tag' => array(
                'label'             => esc_html__( 'Select Title Tag', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                ),
                'value'             => 'h4'
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
			),
            'font_size' => array(
                'label'             => esc_html__( 'Font Size', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 14,
                'max'               => 150,
                'step'              => 1,
                'value'             => 40,
            ),
            'line_height' => array(
                'label'             => esc_html__( 'Line Height', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '%',
                'min'               => 100,
                'max'               => 300,
                'step'              => 5,
                'value'             => 120,
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'value'             => '',
            ),
            'bold_text' => array(
                'label'             => esc_html__( 'Bold Text', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'value'             => '1',
            ),
            'speed' => array(
                'label'             => esc_html__( 'Animation Speed', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds',
                'min'               => 200,
                'max'               => 2000,
                'step'              => 50,
                'value'             => 450,
            ),
            'delay' => array(
                'label'             => esc_html__( 'Animation Delay Between Text Lines', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds',
                'min'               => 50,
                'max'               => 500,
                'step'              => 25,
                'value'             => 100,
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array(), $content = null ) {

        self::$tag = ( isset( $atts['h_tag'] ) and ! empty( $atts['h_tag'] ) ) ? esc_attr( $atts['h_tag'] ) : 'h4';

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'text_alignment'        => '',
            'font_size'             => 40,
            'text_color'            => '',
            'line_height'           => 150,
            'bold_text'             => false,
            'speed'                 => 0,
            'delay'                 => 0,
            'inline_class'          => '',
            'inline_id'             => '',
            'inline_css'            => '',
        ), $atts ) );

        $style = $class = $id = $attr = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        if( $text_alignment ) { $style .= 'text-align:' . esc_attr( $text_alignment ) . ';'; }
        if( $font_size ) { $style .= 'font-size:' . (int) $font_size . 'px;'; }
        if( $text_color ) { $style .= 'color:' . esc_attr( $text_color ) . ';'; }
        if( $line_height ) { $style .= 'line-height:' . (int) $line_height . '%;'; }
        if( $bold_text ) { $style .= 'font-weight:800;'; }

        $attr .= $speed ? ' data-animation-speed="' . esc_attr( $speed ) . '"' : '';
        $attr .= $delay ? ' data-animation-delay="' . esc_attr( $delay ) . '"' : '';

        if( ! empty( $content ) ) {
            return '<div class="pl-animated-texts' . $class . '" style="' . $style . '"' . $id . $attr . '>' . $content . '</div>';
        }

    }
}
new Playouts_Element_Animated_Text;

class Playouts_Element_Animated_Text_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_animated_text_item';
        $this->module_parent = 'bw_animated_text';
        $this->name = esc_html__( 'Animated Text Line', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'text' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Text', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( 'Text line goes here', 'peenapo-layouts-txd' ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'text'              => '',
            'tag'               => 'h4',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $_tag = esc_attr( Playouts_Element_Animated_Text::$tag );

        return '<div class="pl-animated-text' . $class . '" style="' . $style . '"' . $id . '>'.
            '<div class="pl-animated-text-inner">'.
                "<{$_tag} class='pl-animated-text-title'>" . esc_attr( $text ) . "</{$_tag}>".
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Animated_Text_Item;

class Playouts_Element_Heading extends Playouts_Element {

    function init() {

        $this->module = 'bw_heading';
        $this->name = esc_html__( 'Heading Title', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#e6cf4d';
        $this->params = array(
            'title' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Title', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( 'This is a title', 'peenapo-layouts-txd' ),
            ),
            'content' => array(
				'label'             => esc_html__( 'Content', 'peenapo-layouts-txd' ),
				'type'              => 'editor',
				'is_content'        => true,
			),
            'top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Top Title', 'peenapo-layouts-txd' ),
            ),
            'h_tag' => array(
                'label'             => esc_html__( 'Select Heading Title Tag', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                ),
                'value'             => 'h3'
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
                'value'             => '',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'font_size_heading' => array(
                'label'             => esc_html__( 'Heading Font Size', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 14,
                'max'               => 120,
                'step'              => 1,
                'value'             => 40,
            ),
            'font_size_content' => array(
                'label'             => esc_html__( 'Content Font Size', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 13,
                'max'               => 60,
                'step'              => 1,
                'value'             => 15,
            ),
            'font_size_top' => array(
                'label'             => esc_html__( 'Top Font Size', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 13,
                'max'               => 60,
                'step'              => 1,
                'value'             => 15,
            ),
            'bold_text' => array(
                'label'             => esc_html__( 'Bold Text', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'enable_max_width' => array(
                'label'             => esc_html__( 'Enable Maximum Width?', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'max_width' => array(
                'label'             => esc_html__( 'Animation Speed', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds',
                'min'               => 300,
                'max'               => 900,
                'step'              => 10,
                'value'             => 600,
                'depends'           => array( 'element' => 'enable_max_width', 'value' => '1' ),
            ),
            'enable_animation' => array(
                'label'             => esc_html__( 'Enable Animation', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
            ),
            'speed' => array(
                'label'             => esc_html__( 'Animation Speed', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds',
                'min'               => 200,
                'max'               => 2000,
                'step'              => 50,
                'value'             => 450,
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'enable_animation', 'value' => '1' ),
            ),
            'delay' => array(
                'label'             => esc_html__( 'Animation Delay Between Text Lines', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'milliseconds',
                'min'               => 50,
                'max'               => 500,
                'step'              => 25,
                'value'             => 100,
                'tab'               => array( 'animation' => esc_html__( 'Animation', 'peenapo-layouts-txd' ) ),
                'depends'           => array( 'element' => 'enable_animation', 'value' => '1' ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'title'             => '',
            'top'               => '',
            'h_tag'             => 'h3',
            'text_color'        => '',
            'text_alignment'    => '',
            'font_size_heading' => 40,
            'font_size_content' => 15,
            'font_size_top'     => 15,
            'bold_text'         => false,
            'enable_max_width'  => false,
            'max_width'         => 600,
            'enable_animation'  => false,
            'speed'             => 0,
            'delay'             => 0,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $attr = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $style .= ! empty( $text_color ) ? 'color:' . esc_attr( $text_color ) . ';' : '';
        $style .= ! empty( $text_alignment ) ? 'text-align:' . esc_attr( $text_alignment ) . ';' : '';
        $class .= ! empty( $text_alignment ) ? ' pl-heading-align-' . esc_attr( $text_alignment ) : '';

        $anim_wrap_start = $anim_wrap_end = '';
        if( $enable_animation ) {
            $class .= ' pl-is-animated';
            $anim_wrap_start = '<div class="pl-anim-wrap">';
            $anim_wrap_end = '</div>';
            $attr .= $speed ? ' data-animation-speed="' . esc_attr( $speed ) . '"' : '';
            $attr .= $delay ? ' data-animation-delay="' . esc_attr( $delay ) . '"' : '';
        }

        $_tag = esc_attr( $h_tag );
        $_top = ! empty( $top ) ? '<span class="pl-heading-top" style="font-size:' . (int) $font_size_top . 'px;">' . esc_attr( $top ) . '</span>' : '';

        return '<div class="pl-heading' . $class . '" style="' . $style . '"' . $id . $attr . '>'.
            ( $enable_max_width ? '<div class="pl-heading-inner" style="max-width:' . (int) $max_width . 'px;">' : '' ) .
            $anim_wrap_start . $_top . $anim_wrap_end .
            $anim_wrap_start . "<$_tag class='pl-heading-title' style='font-weight:" . ( $bold_text ? '800' : '400' ) . ";font-size:" . (int) $font_size_heading . "px;'>" . Playouts_Functions::kses( $title ) . "</$_tag>" . $anim_wrap_end .
            $anim_wrap_start . '<div class="pl-heading-content" style="font-size:' . (int) $font_size_content . 'px;">' . do_shortcode( $content ) . '</div>' . $anim_wrap_end .
            ( $enable_max_width ? '</div>' : '' ) .
        '</div>';

    }
}
new Playouts_Element_Heading;

class Playouts_Element_Gradient_Text extends Playouts_Element {

    function init() {

        $this->module = 'bw_gradient_text';
        $this->name = esc_html__( 'Gradient Text', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#4dd2e6';
        $this->params = array(
            'text' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Text', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( 'This is a text with gradient', 'peenapo-layouts-txd' ),
            ),
            'h_tag' => array(
                'label'             => esc_html__( 'Select Heading Title Tag', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                ),
                'value'             => 'h3'
            ),
            'text_color_from' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color From', 'peenapo-layouts-txd' ),
                'value'             => '#555070',
                'width'             => 50
            ),
            'text_color_to' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color To', 'peenapo-layouts-txd' ),
                'value'             => '#f43e66',
                'width'             => 50
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
                'width'             => 50
			),
            'direction' => array(
                'label'             => esc_html__( 'Gradient Direction', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'top'               => 'Top',
                    'top right'         => 'Top Right',
                    'right'             => 'Right',
                    'bottom right'      => 'Bottom Right',
                    'bottom'            => 'Bottom',
                    'bottom left'       => 'Bottom Left',
                    'left'              => 'Left',
                    'left top'      => 'Top Left',
                ),
                'value'             => 'bottom right',
                'width'             => 50
            ),
            'font_size' => array(
                'label'             => esc_html__( 'Font Size', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 14,
                'max'               => 120,
                'step'              => 1,
                'value'             => 40,
            ),
            'bold_text' => array(
                'label'             => esc_html__( 'Bold Text', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'text'              => '',
            'h_tag'             => 'h3',
            'text_color_from'   => '#555070',
            'text_color_to'     => '#f43e66',
            'text_alignment'    => '',
            'direction'         => 'right',
            'font_size'         => 40,
            'bold_text'         => false,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $_gradient = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $style .= ! empty( $text_color ) ? 'color:' . esc_attr( $text_color ) . ';' : '';
        $style .= ! empty( $text_alignment ) ? 'text-align:' . esc_attr( $text_alignment ) . ';' : '';
        $style .= ! empty( $font_size ) ? 'font-size:' . (int) $font_size . 'px;' : '';
        $style .= $bold_text ? 'font-weight:800;' : '';

        $_tag = esc_attr( $h_tag );
        $_gradient .= 'color:' . esc_attr( $text_color_from ) . ';';
        $_gradient .= 'background:linear-gradient(to ' . esc_attr( $direction ) . ',' . esc_attr( $text_color_from ) . ',' . esc_attr( $text_color_to ) . ');';
        $_gradient .= 'background-clip:text;-webkit-background-clip:text;text-fill-color:transparent;-webkit-text-fill-color:transparent;';

        return '<div class="pl-gradient' . $class . '" style="' . $style . '"' . $id . '>'.
            "<$_tag class='pl-gradient-text' style='" . $_gradient . "'>" . esc_attr( $text ) . "</$_tag>" .
        '</div>';

    }
}
new Playouts_Element_Gradient_Text;

class Playouts_Element_Notion_Box extends Playouts_Element {

    function init() {

        $this->module = 'bw_notion_box';
        $this->name = esc_html__( 'Notion Box', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#db899b';
        $this->params = array(
            'top_text' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Top Text ( Optional )', 'peenapo-layouts-txd' ),
            ),
            'text' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Text', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( 'This is a notion box', 'peenapo-layouts-txd' ),
            ),
            'sub_text' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Sub Text ( Optional )', 'peenapo-layouts-txd' ),
            ),
            'h_tag' => array(
                'label'             => esc_html__( 'Select Heading Title Tag', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                ),
                'value'             => 'h3'
            ),
            'text_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Text Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    'inherit'           => 'Inherit',
                    'left'              => 'Left',
                    'center'            => 'Center',
                    'right'             => 'Right',
                ),
                'value'             => '',
			),
            'font_size' => array(
                'label'             => esc_html__( 'Font Size', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 14,
                'max'               => 60,
                'step'              => 1,
                'value'             => 32,
            ),
            'bold_title' => array(
                'label'             => esc_html__( 'Bold Title Text', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'text_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Text Color', 'peenapo-layouts-txd' ),
            ),
            'enable_link' => array(
                'label'             => esc_html__( 'Point to Link?', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'link' => array(
				'label'              => esc_html__( 'Link', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
				'placeholder'        => 'http://',
                'depends'           => array( 'element' => 'enable_link', 'value' => '1' ),
			),
            'target' => array(
                'label'             => esc_html__( 'Open in a New Tab?', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'depends'           => array( 'element' => 'enable_link', 'value' => '1' ),
            ),
            'bg_color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Background Color', 'peenapo-layouts-txd' ),
                'value'             => '#f5f5f5',
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
			),
            'image' => array(
				'label'              => esc_html__( 'Background Image', 'peenapo-layouts-txd' ),
				'type'               => 'image',
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
			),
            'scale' => array(
                'label'             => esc_html__( 'Scale Image on Hover?', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'overlay' => array(
                'label'             => esc_html__( 'Enable Overlay?', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
                'width'             => 50
            ),
            'overlay_bg' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Overlay Background Color', 'peenapo-layouts-txd' ),
                'value'             => '#f5f5f5',
                'depends'           => array( 'element' => 'overlay', 'value' => '1' ),
                'tab'               => array( 'background' => esc_html__( 'Background', 'peenapo-layouts-txd' ) ),
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'top_text'          => '',
            'text'              => '',
            'sub_text'          => '',
            'h_tag'             => 'h3',
            'text_alignment'    => '',
            'font_size'         => 32,
            'bold_title'        => false,
            'enable_link'       => false,
            'link'              => '',
            'target'            => false,
            'bg_color'          => '#f5f5f5',
            'text_color'        => '',
            'image'             => 32,
            'scale'             => false,
            'overlay'           => false,
            'overlay_bg'        => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $attr = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $_h_tag = esc_attr( $h_tag );

        $style .= ! empty( $text_color ) ? 'color:' . esc_attr( $text_color ) . ';' : '';
        $style .= ! empty( $text_alignment ) ? 'text-align:' . esc_attr( $text_alignment ) . ';' : '';

        $class .= $scale ? ' pl-is-scale' : '';
        $class .= $overlay ? ' pl-is-over' : '';

        $_tag = 'div';
        if( $enable_link and ! empty( $link ) ) {
            $_tag = 'a';
            $attr .= ' href="' . esc_url( $link ) . '"';
            $attr .= $target ? ' target="_blank"' : '';
        }

        return '<' . $_tag . ' class="pl-notion-box' . $class . '" style="' . $style . '"' . $id . $attr . '>'.
            '<div class="pl-notion-background" style="background-color:' . esc_attr( $bg_color ) . '">'. // TODO: add video, parallax, etc. background styles
                ( $image ? '<div class="pl-notion-image" style="background-image:url(' . esc_url( $image ) . ');"></div>' : '' ) .
                ( $overlay ? '<span class="pl-notion-over" style="' . ( $overlay_bg ? 'background-color:' . esc_attr( $overlay_bg ) : '' ) . '"></span>' : '' ) .
            '</div>'.
            '<div class="pl-notion-content">'.
                ( $top_text ? '<div class="pl-notion-meta"><span class="pl-notion-top" style="' . ( $text_color ? 'border-color:' . esc_attr( $text_color ) : '' ) . '">' . esc_attr( $top_text ) . '</span></div>' : '' ) .
                ( $text ? "<div class='pl-notion-title'><$_h_tag class='pl-notion-text'>" . esc_attr( $text ) . "</$_h_tag></div>" : '' ) .
                ( $sub_text ? '<div class="pl-notion-footer"><span class="pl-notion-sub">' . esc_attr( $sub_text ) . '</span></div>' : '' ) .
            '</div>'.
        '</' . $_tag . '>';

    }
}
new Playouts_Element_Notion_Box;

class Playouts_Element_Image_Comparison extends Playouts_Element {

    function init() {

        $this->module = 'bw_image_comparison';
        $this->name = esc_html__( 'Image Comparison', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#2d49c6';
        $this->params = array(
            'image_left' => array(
                'label'              => esc_html__( 'Left Image', 'peenapo-layouts-txd' ),
                'type'               => 'image',
            ),
            'image_right' => array(
                'label'              => esc_html__( 'Right Image', 'peenapo-layouts-txd' ),
                'type'               => 'image',
            ),
            'color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Color', 'peenapo-layouts-txd' ),
			),
            'direction' => array(
                'label'             => esc_html__( 'Direction', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'vorizontal'  => 'Horizontal',
                    'vertical'    => 'Vertical',
                ),
                'value'             => 'vorizontal'
            ),
            'offset' => array(
                'label'             => esc_html__( 'Offset Position', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'description'       => esc_html__( 'How much of the before image is visible when the page loads', 'peenapo-layouts-txd' ),
                'append_after'      => '%',
                'min'               => 0,
                'max'               => 100,
                'step'              => 5,
                'value'             => 50,
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'image_left'        => '',
            'image_right'       => '',
            'color'             => '',
            'direction'         => 'horizontal',
            'offset'            => 0,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $attr = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $style .= ! empty( $color ) ? 'background-color:' . esc_attr( $color ) . ';' : '';
        $attr .= ! empty( $direction ) ? ' data-direction="' . esc_attr( $direction ) . '"' : '';
        $attr .= ' data-offset="' . esc_attr( $offset ) . '"';

        return '<div class="pl-image-comparison' . $class . '" style="' . $style . '"' . $id . $attr . '>'.
            ( ! empty( $image_left ) ? '<img src="' . esc_url( $image_left ) . '" alt="">' : '' ).
            ( ! empty( $image_right ) ? '<img src="' . esc_url( $image_right ) . '" alt="">' : '' ).
        '</div>';

    }
}
new Playouts_Element_Image_Comparison;

class Playouts_Element_Image_Hotspots extends Playouts_Repeater_Element {

    static $color;
    static $box_bg_color;
    static $box_text_color;

    function init() {

        $this->module = 'bw_image_hotspots';
        $this->module_item = 'bw_image_hotspots_item';
        $this->name = esc_html__( 'Image Hotspots', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#75b677';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Image Hotspots', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many items as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'image' => array(
				'label'              => esc_html__( 'Background Image', 'peenapo-layouts-txd' ),
				'type'               => 'image',
			),
            'color' => array(
                'label'             => esc_html__( 'Hotspot Background Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
            ),
            'box_bg_color' => array(
                'label'             => esc_html__( 'Box Background Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
            ),
            'box_text_color' => array(
                'label'             => esc_html__( 'Box Text Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
            ),
            'enable_animation' => array(
                'label'             => esc_html__( 'Enable Pulse Animation', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array(), $content = null ) {

        self::$color = ( isset( $atts['color'] ) and ! empty( $atts['color'] ) ) ? esc_attr( $atts['color'] ) : '';
        self::$box_bg_color = ( isset( $atts['box_bg_color'] ) and ! empty( $atts['box_bg_color'] ) ) ? esc_attr( $atts['box_bg_color'] ) : '';
        self::$box_text_color = ( isset( $atts['box_text_color'] ) and ! empty( $atts['box_text_color'] ) ) ? esc_attr( $atts['box_text_color'] ) : '';

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'image'                 => '',
            'color'                 => '',
            'box_bg_color'          => '',
            'box_text_color'        => '',
            'enable_animation'      => false,
            'inline_class'          => '',
            'inline_id'             => '',
            'inline_css'            => '',
        ), $atts ) );

        $style = $class = $id = $attr = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $class .= $enable_animation ? ' pl-is-animated' : '';

        if( ! empty( $content ) and ! empty( $image ) ) {
            return '<div class="pl-hotspots' . $class . '" style="' . $style . '"' . $id . $attr . '>'.
                '<div class="pl-hotspots-image">'.
                    '<img src="' . esc_url( $image ) . '" alt="">'.
                '</div>'.
                '<div class="pl-hotspots-list">'.
                    $content.
                '</div>'.
            '</div>';
        }

    }
}
new Playouts_Element_Image_Hotspots;

class Playouts_Element_Image_Hotspots_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_image_hotspots_item';
        $this->module_parent = 'bw_image_hotspots';
        $this->name = esc_html__( 'Image Hotspot', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'title' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Title', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( 'Title goes here', 'peenapo-layouts-txd' ),
            ),
            'text' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Text', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( 'Text goes here', 'peenapo-layouts-txd' ),
            ),
            'position_top' => array(
                'label'             => esc_html__( 'Left Position', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '%',
                'min'               => 0,
                'max'               => 100,
                'step'              => 1,
                'value'             => 50,
            ),
            'position_left' => array(
                'label'             => esc_html__( 'Right Position', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '%',
                'min'               => 0,
                'max'               => 100,
                'step'              => 1,
                'value'             => 50,
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'title'             => '',
            'text'              => '',
            'position_top'      => 50,
            'position_left'     => 50,
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $style .= 'top:' . (int) $position_top . '%;';
        $style .= 'left:' . (int) $position_left . '%;';

        $color = Playouts_Element_Image_Hotspots::$color;
        $box_bg_color = Playouts_Element_Image_Hotspots::$box_bg_color;
        $box_text_color = Playouts_Element_Image_Hotspots::$box_text_color;

        return '<div class="pl-hotspot' . $class . '" style="' . $style . '"' . $id . '>'.
            '<span style="' . ( $color ? esc_attr( 'background-color:' . esc_attr( $color ) . ';border-color:' . esc_attr( $color ) . ';' ) : '' ) . '"></span>'.
            '<div class="pl-hotspot-box" style="background-color:' . esc_attr( $box_bg_color ) . ';color:' . esc_attr( $box_text_color ) . ';">'.
                ( ! empty( $title ) ? '<strong>' . esc_html( $title ). '</strong>' : '' ) .
                ( ! empty( $text ) ? '<p>' . esc_html( $text ) . '</p>' : '' ) .
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Image_Hotspots_Item;

class Playouts_Element_Pricing_Tables extends Playouts_Repeater_Element {

    static $color;
    static $box_bg_color;
    static $box_text_color;

    function init() {

        $this->module = 'bw_pricing_tables';
        $this->module_item = 'bw_pricing_column';
        $this->name = esc_html__( 'Pricing Tables', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#68d0b9';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Pricing Columns', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many columns as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'vertical_alignment' => array(
                'type'              => 'select',
				'label'             => esc_html__( 'Table Vertical Alignment', 'peenapo-layouts-txd' ),
                'options'           => array(
                    'stretch'               => 'Stretch',
                    'flex-start'            => 'Top',
                    'center'                => 'Middle',
                    'flex-end'              => 'Bottom',
                ),
			),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array(), $content = null ) {

        self::$color = ( isset( $atts['color'] ) and ! empty( $atts['color'] ) ) ? esc_attr( $atts['color'] ) : '';
        self::$box_bg_color = ( isset( $atts['box_bg_color'] ) and ! empty( $atts['box_bg_color'] ) ) ? esc_attr( $atts['box_bg_color'] ) : '';
        self::$box_text_color = ( isset( $atts['box_text_color'] ) and ! empty( $atts['box_text_color'] ) ) ? esc_attr( $atts['box_text_color'] ) : '';

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'vertical_alignment'    => 'stretch',
            'inline_class'          => '',
            'inline_id'             => '',
            'inline_css'            => '',
        ), $atts ) );

        $style = $class = $id = $attr = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $style .= 'align-items:' . esc_attr( $vertical_alignment );

        if( ! empty( $content ) ) {
            return '<div class="pl-pricing-tables-outer">'.
                '<div class="pl-pricing-tables' . $class . '" style="' . $style . '"' . $id . $attr . '>'.
                    $content.
                '</div>'.
            '</div>';
        }

    }
}
new Playouts_Element_Pricing_Tables;

class Playouts_Element_Pricing_Tables_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_pricing_column';
        $this->module_parent = 'bw_pricing_tables';
        $this->name = esc_html__( 'Pricing Column', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'top_title' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Top Title ( Optional )', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( 'Top title goes here', 'peenapo-layouts-txd' ),
            ),
            'second_top_title' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Second Top Title ( Optional )', 'peenapo-layouts-txd' ),
            ),
            'price' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Price', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( '$10', 'peenapo-layouts-txd' ),
            ),
            'bottom_title' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Bottom Title ( Optional )', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( 'Bottom title goes here', 'peenapo-layouts-txd' ),
            ),
            'content' => array(
                'label'             => esc_html__( 'Content', 'peenapo-layouts-txd' ),
                'type'              => 'editor',
                'value'             => '<ul>
    <li>' . esc_html__( 'First row goes here', 'peenapo-layouts-txd' ) . '</li>
    <li>' . esc_html__( 'Another row mate', 'peenapo-layouts-txd' ) . '</li>
    <li>' . esc_html__( 'Last one', 'peenapo-layouts-txd' ) . '</li>
    <li>-</li>
    <li>-</li>
</ul>',
                'is_content'        => true,
            ),
            'button_label' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Button Label', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( 'Button', 'peenapo-layouts-txd' ),
            ),
            'button_link' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Button Link', 'peenapo-layouts-txd' ),
                'placeholder'       => 'http://',
            ),
            'button_target' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Button New Tab?', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Opens the button link in a new tab of your browser.', 'peenapo-layouts-txd' ),
            ),
            'main_color' => array(
                'label'             => esc_html__( 'Main Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'secondary_color' => array(
                'label'             => esc_html__( 'Secondary Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'width'             => 50
            ),
            'direction' => array(
                'label'             => esc_html__( 'Gradient Direction', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'top'               => 'Top',
                    'top right'         => 'Top Right',
                    'right'             => 'Right',
                    'bottom right'      => 'Bottom Right',
                    'bottom'            => 'Bottom',
                    'bottom left'       => 'Bottom Left',
                    'left'              => 'Left',
                    'left top'      => 'Top Left',
                ),
                'value'             => 'bottom right',
            ),
            'focus' => array(
                'type'              => 'true_false',
                'label'             => esc_html__( 'Focus This Column?', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( 'Make this column more visible.', 'peenapo-layouts-txd' ),
            ),
            'focus_color' => array(
                'label'             => esc_html__( 'Focus Color', 'peenapo-layouts-txd' ),
                'type'              => 'colorpicker',
                'depends'           => array( 'element' => 'focus', 'value' => '1' ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'top_title'         => '',
            'second_top_title'  => '',
            'price'             => '',
            'bottom_title'      => '',
            'button_label'      => '',
            'button_link'       => '',
            'button_target'     => '',
            'main_color'        => '#f93d66',
            'secondary_color'   => '',
            'focus'             => '',
            'focus_color'       => '',
            'direction'         => 'right',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $class .= $focus ? ' pl-is-focus' : '';

        $_button = '';
        if( ! empty( $button_label ) ) {
            $_button = '<a href="' . esc_url( $button_link ) . '"' . ( $button_target ? ' target="_blank"' : '' ) . ' class="pl-button pl-button-transform-top"' . ( $main_color ? ' data-hover-shadow-override="' . esc_attr( $main_color ) . '"' : '' ) . ' style="background-color:' . esc_attr( $main_color ) . ';background:linear-gradient(to ' . esc_attr( $direction ) . ',' . esc_attr( $main_color ) . ', ' . esc_attr( $secondary_color ) . ');">'.
                esc_attr( $button_label ).
            '</a>';
        }

        return '<div class="pl-pricing-column' . $class . '" style="' . $style . '"' . $id . '>'.
            ( $focus ? '<span class="pl-before" style="background-color:' . esc_attr( $focus_color ) . '"></span>' : '' ).
            '<div class="pl-pricing-header">
                <h5>' . esc_attr( $top_title ) . '</h5>
                <span style="color:' . esc_attr( $focus_color ) . '">' . esc_attr( $second_top_title ) . '</span>
                <h2 class="pl-pricing-title" style="color:' . esc_attr( $main_color ) . ';background:linear-gradient(to ' . esc_attr( $direction ) . ',' . esc_attr( $main_color ) . ', ' . esc_attr( $secondary_color ) . ');' . ( ! empty( $secondary_color ) ? '-webkit-background-clip:text;-webkit-text-fill-color:transparent;' : '' ) . '">' . esc_attr( $price ) . '</h2>
                <h4 class="pl-pricing-title">' . esc_attr( $bottom_title ) . '</h4>
            </div>'.
            '<div class="pl-pricing-content">'.
                do_shortcode( $content ).
            '</div>'.
            '<div class="pl-pricing-footer">'.
                $_button.
            '</div>'.
        '</div>';

    }
}
new Playouts_Element_Pricing_Tables_Item;

class Playouts_Element_Number_Counter extends Playouts_Element {

    function init() {

        $this->module = 'bw_number_counter';
        $this->name = esc_html__( 'Number Counter', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#14abf4';
        $this->params = array(
            'number' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Number', 'peenapo-layouts-txd' ),
            ),
            'font_size' => array(
                'label'             => esc_html__( 'Font Size', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 14,
                'max'               => 150,
                'step'              => 1,
                'value'             => 85,
            ),
            'bold' => array(
                'label'             => esc_html__( 'Bold Text?', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'duration' => array(
                'label'             => esc_html__( 'Duration', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'description'       => esc_html__( 'Animation duration in seconds.', 'peenapo-layouts-txd' ),
                'append_after'      => 'seconds',
                'min'               => 1,
                'max'               => 6,
                'step'              => 1,
                'value'             => 2,
            ),
            'color' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Color', 'peenapo-layouts-txd' ),
                'value'             => '',
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'number'            => 0,
            'font_size'         => 85,
            'bold'              => false,
            'duration'          => 2,
            'color'             => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = $attr = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $style .= ! empty( $color ) ? 'color:' . esc_attr( $color ) . ';' : '';
        $style .= ! empty( $font_size ) ? 'font-size:' . (int) $font_size . 'px;' : '';
        $style .= $bold ? 'font-weight:800;' : '';

        $attr .= ' data-number="' . esc_attr( $number ) . '"';
        $attr .= ' data-duration="' . (int) $duration * 1000 . '"';

        return '<div class="pl-number-counter' . $class . '" style="' . $style . '"' . $id . $attr . '>'.
            '<span>0</span>'.
        '</div>';

    }
}
new Playouts_Element_Number_Counter;

class Playouts_Element_Icon extends Playouts_Element {

    function init() {

        $this->module = 'bw_icon';
        $this->name = esc_html__( 'Icon', 'peenapo-layouts-txd' );
        $this->view = 'element';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#8064af';
        $this->params = array(
            'icon' => array(
                'label'             => esc_html__( 'Icon', 'peenapo-layouts-txd' ),
                'type'              => 'icon',
                'value'             => 'pl-7s-album',
            ),
            'text' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Text ( Optional )', 'peenapo-layouts-txd' ),
            ),
            'font_size' => array(
                'label'             => esc_html__( 'Font Size', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => 'pixels',
                'min'               => 15,
                'max'               => 200,
                'step'              => 1,
                'value'             => 60,
            ),
            'bold' => array(
                'label'             => esc_html__( 'Bold Icon?', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'color_main' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Main Color', 'peenapo-layouts-txd' ),
                'value'             => '',
                'width'             => 50
            ),
            'color_secondary' => array(
                'type'              => 'colorpicker',
                'label'             => esc_html__( 'Secondary Color ( Optional )', 'peenapo-layouts-txd' ),
                'value'             => '',
                'width'             => 50
            ),
            'direction' => array(
                'label'             => esc_html__( 'Gradient Direction', 'peenapo-layouts-txd' ),
                'type'              => 'select',
                'options'           => array(
                    'top'               => 'Top',
                    'top right'         => 'Top Right',
                    'right'             => 'Right',
                    'bottom right'      => 'Bottom Right',
                    'bottom'            => 'Bottom',
                    'bottom left'       => 'Bottom Left',
                    'left'              => 'Left',
                    'left top'      => 'Top Left',
                ),
                'value'             => 'bottom right',
            ),
            'margin_top' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Top', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'margin_right' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Right', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'margin_bottom' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Bottom', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'margin_left' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Margin Left', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
                'width'             => 25
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'icon'              => '',
            'text'              => '',
            'font_size'         => 60,
            'bold'              => false,
            'color_main'        => '',
            'color_secondary'   => '',
            'direction'         => '',
            'margin_top'       => '',
            'margin_right'     => '',
            'margin_bottom'    => '',
            'margin_left'      => '',
            'inline_class'      => '',
            'inline_id'         => '',
            'inline_css'        => '',
        ), $atts ) );

        $style = $class = $id = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $style .= $bold ? 'font-weight:800;' : '';
        $style .= ! empty( $font_size ) ? 'font-size:' . (int) $font_size . 'px;' : '';

        if( $margin_top ) { $style .= 'margin-top:' . esc_attr( $margin_top ) . ( is_numeric( $margin_top ) ? 'px' : '' ) . ';'; }
        if( $margin_right ) { $style .= 'margin-right:' . esc_attr( $margin_right ) . ( is_numeric( $margin_right ) ? 'px' : '' ) . ';'; }
        if( $margin_bottom ) { $style .= 'margin-bottom:' . esc_attr( $margin_bottom ) . ( is_numeric( $margin_bottom ) ? 'px' : '' ) . ';'; }
        if( $margin_left ) { $style .= 'margin-left:' . esc_attr( $margin_left ) . ( is_numeric( $margin_left ) ? 'px' : '' ) . ';'; }

        $_gradient = 'color:' . esc_attr( $color_main ) . ';';
        if( $color_secondary ) {
            $_gradient .= 'background:linear-gradient(to ' . esc_attr( $direction ) . ',' . esc_attr( $color_main ) . ',' . esc_attr( $color_secondary ) . ');';
            $_gradient .= 'background-clip:text;-webkit-background-clip:text;text-fill-color:transparent;-webkit-text-fill-color:transparent;';
        }

        return '<div class="pl-icon' . $class . '" style="' . $style . '"' . $id . '>'.
            '<i class="' . esc_attr( $icon ) . '" style="' . $_gradient . '"></i>'.
            ( ! empty( $text ) ? '<p>' . esc_html( $text ) . '</p>' : '' ) .
        '</div>';

    }
}
new Playouts_Element_Icon;

class Playouts_Element_Google_Map extends Playouts_Repeater_Element {

    //static $color;

    function init() {

        $this->module = 'bw_google_map';
        $this->module_item = 'bw_google_map_pin';
        $this->name = esc_html__( 'Google Map', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#fbae70';
        $this->params = array(
            'items' => array(
                'type'               => 'repeater',
                'label'              => esc_html__( 'Map Pins', 'peenapo-layouts-txd' ),
                'description'        => esc_html__( 'You can add as many map pins as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'zoom' => array(
                'label'             => esc_html__( 'Map Zoom', 'peenapo-layouts-txd' ),
                'description'             => esc_html__( 'This scale represents the zoom of the map.', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'min'               => 1,
                'max'               => 21,
                'step'              => 1,
                'value'             => 17,
            ),
            'height' => array(
                'label'             => esc_html__( 'Map Height', 'peenapo-layouts-txd' ),
                'type'              => 'number_slider',
                'append_after'      => '%',
                'min'               => 15,
                'max'               => 100,
                'step'              => 1,
                'value'             => 50,
            ),
            'enable_controls_zoom' => array(
                'label'             => esc_html__( 'Enable Map Zoom Controls', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'custom_center' => array(
                'label'             => esc_html__( 'Set Custom Map Center', 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),
            'custom_center_lat' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Latitude', 'peenapo-layouts-txd' ),
                'depends'           => array( 'element' => 'custom_center', 'value' => '1' ),
                'width'             => 50
            ),
            'custom_center_lng' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Longitude', 'peenapo-layouts-txd' ),
                'depends'           => array( 'element' => 'custom_center', 'value' => '1' ),
                'width'             => 50
            ),
            /*'bounds' => array(
                'label'             => esc_html__( 'Center Map Between Pins', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( "Center the map between all the available pins automatically. If enable, it will ignore the 'Custom Map Center' option.", 'peenapo-layouts-txd' ),
                'type'              => 'true_false',
            ),*/
            'styles' => array(
                'type'              => 'base64',
                'label'             => esc_html__( 'Map Styles ( Advanced )', 'peenapo-layouts-txd' ),
                'description'       => esc_html__( "Customize colors, roads, labels, and more. Use Javascript array only. Here you can find pre-defined map styles: <a href='https://snazzymaps.com' target='_blank' rel='nofollow'>https://snazzymaps.com</a>", 'peenapo-layouts-txd' ),
            ),
            'inline_class' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'CSS Classes', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_id' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Element ID', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
            'inline_css' => array(
                'type'              => 'textarea',
                'label'             => esc_html__( 'Inline CSS', 'peenapo-layouts-txd' ),
                'tab'               => array( 'inline' => esc_html__( 'Inline', 'peenapo-layouts-txd' ) ),
            ),
        );

    }

    static function construct( $atts = array(), $content = null ) {

        //self::$color = ( isset( $atts['color'] ) and ! empty( $atts['color'] ) ) ? esc_attr( $atts['color'] ) : '';

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'zoom'                  => 17,
            'height'                => 50,
            'enable_controls_zoom'  => false,
            'custom_center'         => false,
            'custom_center_lat'     => '',
            'custom_center_lng'     => '',
            //'bounds'                => false,
            'styles'                => '',
            'inline_class'          => '',
            'inline_id'             => '',
            'inline_css'            => '',
        ), $atts ) );

        $style = $class = $id = $attr = '';

        $class .= ! empty( $inline_class ) ? ' ' . esc_attr( $inline_class ) : '';
        $id .= ! empty( $inline_id ) ? ' id="' . esc_attr( $inline_id ) . '"' : '';
        $style .= ! empty( $inline_css ) ? esc_attr( $inline_css ) : '';

        $_styles = '';
        if( ! empty( $styles ) ) {
            $_styles = '<script>if( typeof playouts_map_styles == "undefined" ) { var playouts_map_styles = []; } playouts_map_styles[\'pl-some-id-temp\'] = ' . Playouts_Functions::base64_from_param_decode( $styles ) . ';</script>';
        }

        if( ! isset( Playouts_Public::$options['google_map_api_key'] ) or empty( Playouts_Public::$options['google_map_api_key'] ) ) {
            // TODO: change message and add link to settings panel
            return '<p class="pl-warning">' . esc_html__( 'Please add a Google Map Api Key to display your map correctly. You can do this by the plugin\'s settings panel.', '' ) . '</p>';
        }

        $style .= ! empty( $height ) ? 'min-height:' . (int) $height . 'vh;' : '';

        $attr .= ' data-zoom="true"';
        $attr .= ' data-zoom-level="' . (int) $zoom . '"';
        if( $custom_center and $custom_center_lat and $custom_center_lng ) {
            $attr .= ' data-center-lat="' . esc_attr( $custom_center_lat ) . '"';
            $attr .= ' data-center-lng="' . esc_attr( $custom_center_lng ) . '"';
        }
        //$attr .= $bounds ? ' data-bounds="true"' : '';
        $attr .= $enable_controls_zoom ? ' data-zoom-controls="true"' : '';

        if( ! empty( $content ) ) {
            return '<div class="pl-google-map-outer"' . $id . '>'.
                '<div class="pl-google-map' . $class . '" id="pl-some-id-temp" style="' . $style . '"' . $attr . '></div>'.
                '<ul class="pl-google-pins">' . $content . '</ul>'.
                $_styles.
            '</div>';
        }

    }
}
new Playouts_Element_Google_Map;

class Playouts_Element_Google_Map_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_google_map_pin';
        $this->module_parent = 'bw_google_map';
        $this->name = esc_html__( 'Google Pin', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'title' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Title', 'peenapo-layouts-txd' ),
                'value'             => esc_html__( 'Out Location', 'peenapo-layouts-txd' ),
            ),
            'lat' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Latitude', 'peenapo-layouts-txd' ),
                'width'             => 50
            ),
            'lng' => array(
                'type'              => 'textfield',
                'label'             => esc_html__( 'Longitude', 'peenapo-layouts-txd' ),
                'width'             => 50
            ),
            'image' => array(
				'label'              => esc_html__( 'Pin Image ( Optional )', 'peenapo-layouts-txd' ),
				'type'               => 'image',
			),
        );

    }

    static function output( $atts = array(), $content = null ) {

        extract( $assigned_atts = shortcode_atts( array(
            'title'             => '',
            'lat'               => '',
            'lng'               => '',
            'image'             => '',
        ), $atts ) );

        return '<li
            data-title="' . esc_attr( $title ) . '"
            data-lat="' . esc_attr( $lat ) . '"
            data-lng="' . esc_attr( $lng ) . '"
            data-image="' . esc_url( $image ) . '">
        </li>';

    }
}
new Playouts_Element_Google_Map_Item;

/*class Playouts_Element_Tabs extends Playouts_Repeater_Element {

    function init() {

        $this->module = 'bw_tabs';
        $this->module_item = 'bw_tab_item';
        $this->name = esc_html__( 'Tabs', 'peenapo-layouts-txd' );
        $this->view = 'repeater';
        $this->category = array( 'content' => __( 'Content', 'peenapo-layouts-txd' ) );
        $this->module_color = '#be6ef6';
        $this->params = array(
            'tabs' => array(
                'label'              => esc_html__( 'Tab items', 'peenapo-layouts-txd' ),
                'type'               => 'repeater',
                'description'        => esc_html__( 'You can add as many tabs as you need, just click the plus icon.', 'peenapo-layouts-txd' ),
            ),
            'title' => array(
				'label'              => esc_html__( 'Tab title', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
				'description'        => esc_html__( 'Here you can add some title', 'peenapo-layouts-txd' ),
			),
            'title2' => array(
				'label'              => esc_html__( 'Tab title 2', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
                'tab'                => array( 'new' => esc_html__( 'New Tab Man', 'peenapo-layouts-txd' ) ),
				'description'        => esc_html__( 'Here you can add some title', 'peenapo-layouts-txd' ),
			),
            'image' => array(
				'label'              => esc_html__( 'Tab title 2', 'peenapo-layouts-txd' ),
				'type'               => 'image',
				'description'        => esc_html__( 'Here you can add some image', 'peenapo-layouts-txd' ),
			)
        );

    }

    static function output( $atts = array(), $content = null ) {

        return '<div class="pl-tabs">' . $content . '</div>';

    }
}
new Playouts_Element_Tabs;

class Playouts_Element_Tab_Item extends Playouts_Repeater_Item_Element {

    function init() {

        $this->module = 'bw_tab_item';
        $this->module_parent = 'bw_tabs';
        $this->name = esc_html__( 'Tab Item', 'peenapo-layouts-txd' );
        $this->view = 'repeater_item';

        $this->params = array(
            'title2' => array(
				'label'              => esc_html__( 'Tab item title', 'peenapo-layouts-txd' ),
				'type'               => 'textfield',
				'description'        => esc_html__( 'Here you can add some title 2', 'peenapo-layouts-txd' ),
			),
            'image' => array(
				'label'              => esc_html__( 'Tab title 2', 'peenapo-layouts-txd' ),
				'type'               => 'image',
				'description'        => esc_html__( 'Here you can add some image', 'peenapo-layouts-txd' ),
			)
        );

    }

    static function output( $atts = array(), $content = null ) {

        return '<div class="pl-tab-item">' . $content . '</div>';

    }
}
new Playouts_Element_Tab_Item;*/

/*'title' => array(
    'label'             => esc_html__( 'Title', 'peenapo-layouts-txd' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'title2' => array(
    'label'             => esc_html__( 'Title', 'peenapo-layouts-txd' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
    'width'             => 50
),
'title3' => array(
    'label'             => esc_html__( 'Title', 'peenapo-layouts-txd' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
    'width'             => 50
),
'title4' => array(
    'label'             => esc_html__( 'Title', 'peenapo-layouts-txd' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'title7' => array(
    'label'             => esc_html__( 'Title', 'peenapo-layouts-txd' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'title8' => array(
    'label'             => esc_html__( 'Title', 'peenapo-layouts-txd' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'title5' => array(
    'label'             => esc_html__( 'Title 2', 'peenapo-layouts-txd' ),
    'tab'               => array( 'new' => esc_html__( 'New Tab Man', 'peenapo-layouts-txd' ) ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'title6' => array(
    'label'             => esc_html__( 'Title 3', 'peenapo-layouts-txd' ),
    'tab'               => array( 'new' => esc_html__( 'New Tab Man', 'peenapo-layouts-txd' ) ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
),
'content' => array(
    'label'             => esc_html__( 'Content', 'peenapo-layouts-txd' ),
    'type'              => 'editor',
    'value'             => 'Helloooo! <strong>man</strong>! How are you?',
    'is_content'        => true,
),*/
/*'select5' => array(
    'label'             => esc_html__( 'Select 5 Post Type', 'peenapo-layouts-txd' ),
    'type'              => 'taxonomy',
    'multiple'          => true,
    'post_type'         => 'category',
),
'select1' => array(
    'label'             => esc_html__( 'Select', 'peenapo-layouts-txd' ),
    'type'              => 'sidebars',
    'description'       => esc_html__( 'Some description', 'peenapo-layouts-txd' ),
),
'number_slider1' => array(
    'label'             => esc_html__( 'Number Slider', 'peenapo-layouts-txd' ),
    'type'              => 'number_slider',
    'description'       => esc_html__( 'Some description', 'peenapo-layouts-txd' ),
    //'append_before'     => 'before..',
    'append_after'      => 'pixels',
    'min'               => 10,
    'max'               => 100,
    'step'              => 5,
    'value'             => 55,
),
'number_slider2' => array(
    'label'             => esc_html__( 'Number Slider 2', 'peenapo-layouts-txd' ),
    'type'              => 'number_slider',
    'description'       => esc_html__( 'Some description', 'peenapo-layouts-txd' ),
    'append_before'     => 'before..',
    'append_after'      => 'after..',
    'min'               => 0,
    'max'               => 20,
    'step'              => 1,
    'value'             => 0,
),*/
/*'checkbox121' => array(
    'label'             => esc_html__( 'Checkbox', 'peenapo-layouts-txd' ),
    'type'              => 'true_false',
),
'icon1' => array(
    'label'             => esc_html__( 'Checkbox', 'peenapo-layouts-txd' ),
    'type'              => 'icon',
),
'icon2' => array(
    'label'             => esc_html__( 'Checkbox 2', 'peenapo-layouts-txd' ),
    'type'              => 'icon',
),
'select1' => array(
    'label'             => esc_html__( 'Select', 'peenapo-layouts-txd' ),
    'type'              => 'select',
    'description'       => esc_html__( 'Some description', 'peenapo-layouts-txd' ),
    'options'           => array(
        'some1' => 'Some 1',
        'some2' => 'Some 2',
        'some3' => 'Some 3',
    ),
    'value'             => 'some2'
),*/
/*'row_layout' => array(
    'label'             => esc_html__( 'Row Layout', 'peenapo-layouts-txd' ),
    'type'              => 'radio_image',
    'description'       => esc_html__( 'Select the display version of the row.', 'peenapo-layouts-txd' ),
    'options'           => array(
        'standard' => array(
            'label' => 'Standard', 'image' => PLAYOUTS_ASSEST . 'admin/images/__tmp/row_standard.png'
        ),
        'full' => array(
            'label' => 'Full-Width', 'image' => PLAYOUTS_ASSEST . 'admin/images/__tmp/row_full_width.png'
        ),
        'boxed' => array(
            'label' => 'Boxed', 'image' => PLAYOUTS_ASSEST . 'admin/images/__tmp/row_boxed.png'
        ),
    ),
    'value'             => 'standard'
),
'file1' => array(
    'label'             => esc_html__( 'File 1', 'peenapo-layouts-txd' ),
    'type'              => 'image',
),
'image1' => array(
    'label'             => esc_html__( 'Image 1', 'peenapo-layouts-txd' ),
    'type'              => 'image',
    'value'             => '',
),
'image2' => array(
    'label'             => esc_html__( 'Image 2', 'peenapo-layouts-txd' ),
    'type'              => 'image',
    'value'             => '',
),*/
/*'checkbox11' => array(
    'label'             => esc_html__( 'Checkbox', 'peenapo-layouts-txd' ),
    'type'              => 'true_false',
),
'image3' => array(
    'label'             => esc_html__( 'Image Depends', 'peenapo-layouts-txd' ),
    'type'              => 'image',
    'value'             => '',
    'depends'           => array( 'element' => 'checkbox11', 'value' => '1' ),
),
'title3' => array(
    'label'             => esc_html__( 'Title 3 depends', 'peenapo-layouts-txd' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
    'depends'        => array( 'element' => 'checkbox11', 'value' => '1' ),
),
'editor1' => array(
    'label'             => esc_html__( 'Editor1', 'peenapo-layouts-txd' ),
    'type'              => 'editor',
    'value'             => 'Helloooo! <strong>man</strong>! How are you?',
    'is_content'        => true,
),
'title2' => array(
    'label'             => esc_html__( 'Title 2', 'peenapo-layouts-txd' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
    'width'             => 50
),
'checkbox11' => array(
    'label'             => esc_html__( 'Checkbox', 'peenapo-layouts-txd' ),
    'type'              => 'true_false',
),
'bg_color22' => array(
    'label'             => esc_html__( 'Background Color', 'peenapo-layouts-txd' ),
    'type'              => 'colorpicker',
    'description'       => esc_html__( 'Some description', 'peenapo-layouts-txd' ),
    'width'             => 50
),
'radio11' => array(
    'label'             => esc_html__( 'Some Radio Button', 'peenapo-layouts-txd' ),
    'type'              => 'radio',
    'description'       => esc_html__( 'Some description', 'peenapo-layouts-txd' ),
    'options'           => array(
        'some1' => 'Some 1',
        'some2' => 'Some 2',
        'some3' => 'Some 3',
    ),
    'value'             => 'some1'
),
'title4' => array(
    'label'             => esc_html__( 'Title 4 depends', 'peenapo-layouts-txd' ),
    'type'              => 'textfield',
    'value'             => 'Helloooo!',
    'depends'        => array( 'element' => 'radio11', 'value' => 'some2' ),
),
'heading' => array(
    'label'             => esc_html__( 'Heading', 'peenapo-layouts-txd' ),
    'type'              => 'heading',
    'description'       => esc_html__( 'Some description', 'peenapo-layouts-txd' ),
),
'textarea' => array(
    'label'             => esc_html__( 'Textarea', 'peenapo-layouts-txd' ),
    'type'              => 'textarea',
    'value'             => esc_html__( 'Some textarea value here...', 'peenapo-layouts-txd' ),
    'description'       => esc_html__( 'Some description', 'peenapo-layouts-txd' ),
),
'base64' => array(
    'label'             => esc_html__( 'Base 64', 'peenapo-layouts-txd' ),
    'type'              => 'base64',
    'value'             => Playouts_Functions::base64_to_param_encode( 'dfgdfgdf dfg dfgwer456' ),
    'description'       => esc_html__( 'Some description', 'peenapo-layouts-txd' ),
),*/
