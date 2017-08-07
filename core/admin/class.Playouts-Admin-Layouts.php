<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * class for build-in layouts
 *
 *
 */
class Playouts_Admin_Layout {

    public $id;
    public $name;
    public $image;
    public $class_name;
    public $category = array();
    public $public = true;
    public $layout_view;

    private static $layouts = array();
    private static $index = 0;

    function __construct() {

        self::$index++;

        $this->category = array( 'general' => __( 'General', 'peenapo-layouts-txd' ) );
        $this->image = PLAYOUTS_ASSEST . 'admin/images/default-layout.png';
        $this->class_name = get_class( $this );

        $this->init();

        self::$layouts[ $this->id ] = $this;

    }

    static function output() {
        return '';
    }

    static function get_layouts() {
        return self::$layouts;
    }

    static function get_modules_arr() {

        $layouts = array();
        foreach( self::get_layouts() as $layout ) {

            $layouts[ $layout->id ] = array(
                'name' => $layout->name,
                'category' => key( $layout->category ),
                'public' => $layout->public,
                'layout_view' => $layout->layout_view,
                'image' => $layout->image
            );

        }
        return $layouts;

    }

    static function get_layouts_output() {

        $layouts = array();
        foreach( self::get_layouts() as $layout ) {

            $output = call_user_func( $layout->class_name . '::output' );

            $layouts[ $layout->id ] = array(
                'id' => $layout->id,
                'output' => $output,
            );

        }
        return $layouts;

    }

    static function get_layout_categories() {

        $categories = array();
        $layouts = self::get_layouts();
        foreach( $layouts as $layout ) {
            $category_id = key( $layout->category );
            $categories[ $category_id ] = $layout->category[ $category_id ];
        }
        return $categories;

    }

}



class Playouts_Layout_About extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_about';
        $this->name = esc_html__( 'About', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/about.png';

    }

    static function output() {
        return '[bw_row row_layout="full" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" vertical_alignment="center" enable_static_height="1" static_height="100"][bw_column col_width="50" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" padding_top="50" padding_right="8%" padding_bottom="50" padding_left="8%"][bw_heading title="Cras pharetra semper ex id ornare. Integer elit est" h_tag="h2" text_alignment="inherit" font_size_heading="40" font_size_content="15" font_size_top="15" bold_text="1" max_width="600" speed="450" delay="100"]<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis porta eros. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget ligula quam. Nunc a dolor vitae enim semper auctor eu vitae neque. Cras pharetra semper ex id ornare. Integer elit est, porta non dui eget, elementum consequat leo.</p>[/bw_heading][bw_progress_bars enable_animation="1" animation_speed="150" animation_delay="80"][bw_progress_bar title="JavaScript" progress="85" bar_color="#23efde" bar_color_secondary="#a423ea" direction="right"][/bw_progress_bar][bw_progress_bar title="WordPress" progress="65" bar_color="#23efde" bar_color_secondary="#a423ea" direction="right"][/bw_progress_bar][bw_progress_bar title="UX Design" progress="75" bar_color="#23efde" bar_color_secondary="#a423ea" direction="right"][/bw_progress_bar][bw_progress_bar title="Photoshop" progress="55" bar_color="#23efde" bar_color_secondary="#a423ea" direction="right"][/bw_progress_bar][/bw_progress_bars][bw_button label="Contact me" link="#" style="small" bg_color="#a423ea" direction="bottom right" border_radius="60" transform_top="1" shadow="1" margin_top="50"][/bw_button][/bw_column][bw_column col_width="50" background="image" bg_image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" padding_top="50" padding_right="50" padding_bottom="50" padding_left="50"][bw_divider height="300"][/bw_divider][bw_divider height="300"][/bw_divider][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_About;



class Playouts_Layout_About_2 extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_about_2';
        $this->name = esc_html__( 'About 2', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/about_2.png';

    }

    static function output() {
        return '[bw_row row_layout="full" background="image" overlay_bg_second="#f5f5f5" overlay_direction="bottom right" overlay_opacity="50" vertical_alignment="center" enable_static_height="1" static_height="100"][bw_column col_width="30.0" background="image" bg_image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png" bg_image_position="center center" bg_image_size="cover" overlay_enable="1" overlay_bg_color="#24e5d8" overlay_bg_second="#9528fc" overlay_direction="top right" overlay_opacity="70"][bw_divider height="300"][/bw_divider][bw_divider height="300"][/bw_divider][/bw_column][bw_column col_width="70.0" background="image" bg_image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png" overlay_enable="1" overlay_bg_color="#ffffff" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="97" padding_top="50" padding_right="50" padding_bottom="50" padding_left="50"][bw_row_inner text_alignment="inherit" static_height="30" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100" inline_css="max-width:600px;margin:0 auto;"][bw_column_inner overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_heading title="Integer elit est, porta non dui eget, elementum consequat leo" h_tag="h3" text_alignment="inherit" font_size_heading="40" font_size_content="15" font_size_top="15" bold_text="1" max_width="600" speed="450" delay="100"]<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis porta eros. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget ligula quam. Nunc a dolor vitae enim semper auctor eu vitae neque. Cras pharetra semper ex id ornare. Integer elit est, porta non dui eget, elementum consequat leo.</p>[/bw_heading][/bw_column_inner][/bw_row_inner][bw_row_inner text_alignment="inherit" static_height="30" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100" inline_css="max-width:600px;margin:0 auto;"][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="pl-7s-rocket" text="Smarter, stronger, faster" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="pl-7s-monitor" text="Build a website in minutes" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][/bw_row_inner][bw_row_inner text_alignment="inherit" static_height="30" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100" inline_css="max-width:600px;margin:0 auto;"][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="pl-7s-timer" text="Optimized for speed" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="pl-7s-magic-wand" text="Pick any Google Font" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][/bw_row_inner][bw_row_inner text_alignment="inherit" static_height="30" padding_bottom="10" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100" inline_css="max-width:600px;margin:0 auto;"][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="pl-7s-help2" text="Badass support" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_icon icon="pl-7s-help2" text="Badass support" font_size="42" direction="bottom right"][/bw_icon][/bw_column_inner][/bw_row_inner][bw_row_inner text_alignment="inherit" static_height="30" padding_top="25" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100" inline_css="max-width:600px;margin:0 auto;"][bw_column_inner overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_button label="Learn more button" link="#" style="large" direction="bottom right" border_radius="60" transform_top="1" shadow="1"][/bw_button][/bw_column_inner][/bw_row_inner][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_About_2;



class Playouts_Layout_Heading_video extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_heading_video';
        $this->name = esc_html__( 'Heading Text with Video Modal', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/heading_video.png';

    }

    static function output() {
        return '[bw_row row_layout="standard" background="image" bg_image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png" overlay_enable="1" overlay_bg_color="#ffffff" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="90" vertical_alignment="center" text_alignment="center" enable_static_height="1" static_height="100" text_color="#0c0c0c" padding_right="50" padding_left="50"][bw_column overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_heading title="Awesome WordPress<br>Plugin for Free" h_tag="h2" text_alignment="inherit" font_size_heading="62" font_size_content="15" font_size_top="15" bold_text="1" max_width="600" speed="450" delay="100"]<p>Mauris auctor sapien a quam consectetur rutrum. Nullam bibendum enim et nisi pretium venenatis vitae egestas urna.<br>Vestibulum nec accumsan nulla, id tristique diam</p>[/bw_heading][bw_video_modal size="medium" size_button="medium" autoplay="1" color="#0a0a0a" bg_color="#ffffff" text="Watch the video" inline_css="margin-top:20px;"]https://www.youtube.com/watch?v=bSfshpw3MRo[/bw_video_modal][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Heading_video;



class Playouts_Layout_Split extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_split';
        $this->name = esc_html__( 'Split Page', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/split.png';

    }

    static function output() {
        return '[bw_row row_layout="full" background="color" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" enable_static_height="1" static_height="100"][bw_column col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_row_inner text_alignment="inherit" enable_static_height="1" static_height="50" vertical_alignment="center" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100"][bw_column_inner col_width="50" text_alignment="inherit" background="image" bg_color="#e0e0e0" bg_image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_divider height="300"][/bw_divider][bw_divider height="100"][/bw_divider][/bw_column_inner][bw_column_inner col_width="50" text_alignment="inherit" padding_top="50" padding_right="10%" padding_bottom="50" padding_left="10%" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_icon icon="pl-7s-umbrella" font_size="60" direction="bottom right"][/bw_icon][bw_text inline_css="margin-top:30px;"]<p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed.</p>[/bw_text][bw_button label="Learn more" style="medium" direction="bottom right" border_radius="3" transform_top="1" shadow="1" margin_top="35"][/bw_button][/bw_column_inner][/bw_row_inner][bw_row_inner text_color="#ffffff" text_alignment="inherit" enable_static_height="1" static_height="50" vertical_alignment="center" background="color" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100"][bw_column_inner col_width="50" text_alignment="inherit" padding_top="50" padding_right="10%" padding_bottom="50" padding_left="10%" background="color" bg_color="#6925e8" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_icon icon="pl-7s-science" font_size="60" direction="bottom right"][/bw_icon][bw_text inline_css="margin-top:30px;"]<p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed.</p>[/bw_text][bw_button label="Learn more" style="medium" direction="bottom right" border_radius="3" transform_top="1" shadow="1" margin_top="35"][/bw_button][/bw_column_inner][bw_column_inner col_width="50" text_alignment="inherit" background="image" bg_image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-gray.png" bg_image_position="center center" bg_image_size="cover" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_divider height="300"][/bw_divider][bw_divider height="100"][/bw_divider][/bw_column_inner][/bw_row_inner][/bw_column][bw_column col_width="50" background="image" bg_image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_divider height="300"][/bw_divider][bw_divider height="300"][/bw_divider][bw_divider height="100"][/bw_divider][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Split;



class Playouts_Layout_Welcome extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_welcome';
        $this->name = esc_html__( 'Full-Height Welcome Text', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/welcome.png';

    }

    static function output() {
        return '[bw_row row_layout="standard" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" text_alignment="center" enable_static_height="1" static_height="100" vertical_alignment="center"][bw_column overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_row_inner text_alignment="inherit" padding_bottom="10" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="bottom" animation_speed="400"][bw_column_inner overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_text]<p>Welcome</p>[/bw_text][/bw_column_inner][/bw_row_inner][bw_animated_text h_tag="h4" text_alignment="inherit" font_size="50" line_height="160" bold_text="1" speed="450" delay="100"][bw_animated_text_item text="Lorem ipsum dolor sit amet"][/bw_animated_text_item][bw_animated_text_item text="Sed imperdiet elementum nisivel"][/bw_animated_text_item][/bw_animated_text][bw_row_inner text_alignment="inherit" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="bottom" animation_speed="400" animation_delay="50"][bw_column_inner overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_button label="Show me more please" link="#" style="large" bg_color_second="#ea6035" direction="bottom right" border_radius="60" transform_top="1" shadow="1" margin_top="30"][/bw_button][/bw_column_inner][/bw_row_inner][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Welcome;



class Playouts_Layout_Welcome_Auto_Type extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_welcome_auto_type';
        $this->name = esc_html__( 'Auto-Type Welcome Text', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/welcome_auto_type.png';

    }

    static function output() {
        return '[bw_row row_layout="full" background="parallax" bg_image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png" bg_image_position="center center" bg_parallax_speed="300" overlay_enable="1" overlay_bg_color="#030e46" overlay_bg_second="rgba(255,255,255,0.01)" overlay_direction="bottom left" overlay_opacity="30" padding_right="5%" padding_bottom="50" padding_left="5%"][bw_column overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_row_inner text_alignment="inherit" enable_static_height="1" static_height="100" vertical_alignment="center" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100"][bw_column_inner overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_auto_type static_heading="Lorem ipsum dolor sit amet" h_tag="h2" font_size="90" text_color="#ffffff" bold_text="1"][bw_auto_type_item text="Curabitur viverra felis in pharetra"][/bw_auto_type_item][bw_auto_type_item text="Etiam at dolor"][/bw_auto_type_item][bw_auto_type_item text="Fusce congue, tortor sit amet"][/bw_auto_type_item][/bw_auto_type][bw_button label="Show me more please" link="#" style="large" bg_color="#f93d66" direction="bottom right" border_radius="60" transform_top="1" shadow="1" margin_top="20"][/bw_button][/bw_column_inner][/bw_row_inner][bw_row_inner text_color="#ffffff" text_alignment="inherit" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100"][bw_column_inner col_width="33.3" text_alignment="inherit" padding_top="50" padding_right="50" padding_bottom="50" padding_left="50" margin_right="30" background="color" bg_color="rgba(249,61,102,0.92)" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" inline_css="border-radius:5px;overflow:hidden;box-shadow:0 15px 30px -5px rgba(0,0,0,0.20);"][bw_text]<p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p><p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p>[/bw_text][/bw_column_inner][bw_column_inner col_width="33.3" text_alignment="inherit" padding_top="50" padding_right="50" padding_bottom="50" padding_left="50" margin_right="30" margin_left="30" background="color" bg_color="rgba(3,10,155,0.75)" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" inline_css="border-radius:5px;overflow:hidden;box-shadow:0 15px 30px -5px rgba(0,0,0,0.20);"][bw_text]<p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p><p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p>[/bw_text][/bw_column_inner][bw_column_inner col_width="33.3" text_alignment="inherit" padding_top="50" padding_right="50" padding_bottom="50" padding_left="50" margin_left="30" background="color" bg_color="rgba(3,14,70,0.92)" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" inline_css="border-radius:5px;overflow:hidden;box-shadow:0 15px 30px -5px rgba(0,0,0,0.20);"][bw_text]<p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p><p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p>[/bw_text][/bw_column_inner][/bw_row_inner][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Welcome_Auto_Type;



class Playouts_Layout_Pricing_Table extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_pricing_table';
        $this->name = esc_html__( 'Pricing Table', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/pricing_table.png';

    }

    static function output() {
        return '[bw_row row_layout="standard" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_top="100" margin_bottom="100"][bw_column overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_pricing_tables vertical_alignment="stretch"][bw_pricing_column top_title="Basic Plan" second_top_title="Economic" price="$9" bottom_title="Monthly" button_label="Subscribe" button_link="#" main_color="#fa3867" direction="top right"]<ul><li>First row goes here</li><li>Another row mate</li><li>Last one</li><li>-</li><li>-</li><li>-</li><li>-</li></ul>[/bw_pricing_column][bw_pricing_column top_title="Medium Plan" second_top_title="Best price" price="$19" bottom_title="Monthly" button_label="Subscribe" button_link="#" main_color="#fa3867" direction="top right" focus="1"]<ul><li>First row goes here</li><li>Another row</li><li>This is cool</li><li>Fast and easy to use</li><li>24/7 support</li><li>-</li><li>-</li></ul>[/bw_pricing_column][bw_pricing_column top_title="Advanced Plan" second_top_title="Development" price="$49" bottom_title="Monthly" button_label="Subscribe" button_link="#" main_color="#fa3867" direction="top right"]<ul><li>First row goes here</li><li>Another row</li><li>This is cool</li><li>Fast and easy to use</li><li>24/7 support</li><li>That is enough</li><li>Last one</li></ul>[/bw_pricing_column][/bw_pricing_tables][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Pricing_Table;



class Playouts_Layout_Slider_Full extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_slider_full';
        $this->name = esc_html__( 'Full-Width Slider', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/slider_full.png';

    }

    static function output() {
        return '[bw_row row_layout="full" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_top="100" margin_bottom="100"][bw_column overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_image_slider slide_width="50" spacing="25" thumbnail_size="large" adaptive_height="1" group_slides="2" autoplay_enable="1" autoplay="5000" stop_autoplay_hover="1" pagination_enable="1" infinite="1" attraction="0.025" friction="0.28"][bw_image_slider_item image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png"][/bw_image_slider_item][bw_image_slider_item image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png"][/bw_image_slider_item][bw_image_slider_item image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png"][/bw_image_slider_item][/bw_image_slider][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Slider_Full;



class Playouts_Layout_Text_Slider extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_text_slider';
        $this->name = esc_html__( 'Text Slider', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/text_slider.png';

    }

    static function output() {
        return '[bw_row row_layout="full" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_top="100" margin_bottom="100"][bw_column overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_testimonials layout="box" slide_width="20" adaptive_height="1" group_slides_enable="1" group_slides="2" autoplay_enable="1" autoplay="4000" stop_autoplay_hover="1" pagination_enable="1" infinite="1" bg_color="#ffffff" animation_speed="1" fine_text="1" attraction="0.035" friction="0.57"][bw_testimonial_item name="Proin eleifend arcu nec felis imperdiet" star_rating="4.5"]Sed quis tortor vehicula, eleifend diam in, vestibulum purus. Etiam a est massa. Sed gravida consequat enim, nec interdum eros tempus non.[/bw_testimonial_item][bw_testimonial_item name="Sed gravida consequat enim, nec interdum eros tempus non." star_rating="3.5"]Duis semper nulla vel neque dictum, sit amet ullamcorper justo tempus. Curabitur eget luctus augue. Curabitur nec ex a arcu consectetur sagittis non sed libero.[/bw_testimonial_item][bw_testimonial_item name="Sed gravida consequat enim, nec interdum eros tempus non." star_rating="3.5"]urabitur ullamcorper, nunc sed bibendum pellentesque, nunc elit aliquam odio, et feugiat lacus nulla sed purus.[/bw_testimonial_item][bw_testimonial_item name="Sed gravida consequat enim, nec interdum eros tempus non." star_rating="3.5"]Quisque mauris orci, congue id porta eu, aliquet et risus. Curabitur nec ligula vel nibh lobortis cursus ac imperdiet erat.[/bw_testimonial_item][bw_testimonial_item name="Sed gravida consequat enim, nec interdum eros tempus non." star_rating="3.5"]Phasellus eget auctor libero, vitae luctus elit. Nam at placerat massa. Donec eleifend diam a purus elementum pulvinar.[/bw_testimonial_item][bw_testimonial_item name="Sed gravida consequat enim, nec interdum eros tempus non." star_rating="3.5"]Maecenas id enim placerat, posuere orci et, pretium libero. Nunc dignissim orci quam. Cras ut erat sagittis, varius felis sed, tincidunt leo.[/bw_testimonial_item][bw_testimonial_item name="Sed gravida consequat enim, nec interdum eros tempus non." star_rating="3.5"]Integer quis vulputate lorem, non condimentum massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer finibus neque purus.[/bw_testimonial_item][/bw_testimonials][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Text_Slider;



class Playouts_Layout_Text_Gradient extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_text_gradient';
        $this->name = esc_html__( 'Text with Gradient', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/text_gradient.png';

    }

    static function output() {
        return '[bw_row row_layout="standard" background="color" bg_color="#f5f5f5" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" text_alignment="center" padding_top="100" padding_bottom="100"][bw_column overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_gradient_text text="Nam tristique dictum ex, vitae molestie quam." h_tag="h3" text_color_from="#8927f9" text_color_to="#f73d81" text_alignment="inherit" direction="bottom right" font_size="82" bold_text="1"][/bw_gradient_text][bw_button label="Read more" link="#" style="extra_large" bg_color="#4b28fc" direction="bottom right" border_radius="60" transform_top="1" shadow="1" margin_top="60"][/bw_button][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Text_Gradient;



class Playouts_Layout_Icon_Box extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_icon_box';
        $this->name = esc_html__( 'Boxed Texts with Icon', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/icon_box.png';

    }

    static function output() {
        return '[bw_row row_layout="full" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" text_color="#ffffff"][bw_column overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_row_inner text_alignment="inherit" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100"][bw_column_inner col_width="25" text_alignment="inherit" padding_top="50" padding_right="50" padding_bottom="50" padding_left="50" background="color" bg_color="#a138f7" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_icon icon="pl-7s-leaf" text="We are Eco and Bio" font_size="60" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text]Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.<p>Aenean pretium tellus diam, vel venenatis turpis accumsan ac.[/bw_text][/bw_column_inner][bw_column_inner col_width="25" text_alignment="inherit" padding_top="50" padding_right="50" padding_bottom="50" padding_left="50" background="color" bg_color="#f4388d" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_icon icon="pl-7s-headphones" text="We can help you" font_size="60" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text]Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui. Aenean pretium tellus diam, vel venenatis turpis accumsan ac.[/bw_text][/bw_column_inner][bw_column_inner col_width="25" text_alignment="inherit" padding_top="50" padding_right="50" padding_bottom="50" padding_left="50" background="color" bg_color="#21cec8" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_icon icon="pl-7s-umbrella" text="Umbrella effect" font_size="60" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text]<p>Aenean pretium tellus diam, vel venenatis turpis accumsan ac.</p><p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p>[/bw_text][/bw_column_inner][bw_column_inner col_width="25" text_alignment="inherit" padding_top="50" padding_right="50" padding_bottom="50" padding_left="50" background="color" bg_color="#1d48c1" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_icon icon="pl-7s-coffee" text="You can become more productive" font_size="60" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text]Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui. Aenean pretium tellus diam, vel venenatis turpis accumsan ac.[/bw_text][/bw_column_inner][/bw_row_inner][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Icon_Box;



class Playouts_Layout_Icon_Text extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_icon_text';
        $this->name = esc_html__( 'Gradient Icon with Text', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/icon_text.png';

    }

    static function output() {
        return '[bw_row row_layout="standard" background="color" bg_color="#f4f4f4" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" static_height="30" padding_top="100" padding_bottom="100"][bw_column col_width="33.3" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_right="25" padding_right="25" padding_bottom="50" padding_left="25"][bw_icon icon="pl-7s-paint" text="Customize in a minutes" font_size="60" color_main="#f9276d" color_secondary="#2539ea" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text]<p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p>[/bw_text][/bw_column][bw_column col_width="33.3" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_right="25" margin_left="25" padding_right="25" padding_bottom="50" padding_left="25"][bw_icon icon="pl-7s-gift" text="It is free for use" font_size="60" color_main="#f9276d" color_secondary="#2539ea" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text]<p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p>[/bw_text][/bw_column][bw_column col_width="33.3" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_left="25" padding_right="25" padding_bottom="50" padding_left="25"][bw_icon icon="pl-7s-plane" text="Boost your website" font_size="60" color_main="#f9276d" color_secondary="#2539ea" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text]<p>Text element. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ante dolor, ultrices quis arcu sed, consectetur fermentum dui.</p>[/bw_text][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Icon_Text;



class Playouts_Layout_Welcome_Text_Video extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_welcome_text_video';
        $this->name = esc_html__( 'Welcome Text with Full-Screen Video', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/welcome_videot.png';

    }

    static function output() {
        return '[bw_row row_layout="standard" background="video" bg_video_mp4="http://localhost/pp/demo/wp-content/uploads/2017/06/home_video3.mp4" overlay_enable="1" overlay_bg_color="#ee4f89" overlay_bg_second="#423a8f" overlay_direction="left top" overlay_opacity="37" vertical_alignment="center" text_alignment="center" enable_static_height="1" static_height="100" text_color="#ffffff"][bw_column overlay_bg_second="#f5f5f5" overlay_opacity="50"][bw_animated_text h_tag="h4" text_alignment="inherit" font_size="60" line_height="150" text_color="#ffffff" bold_text="1" speed="450" delay="100"][bw_animated_text_item text="Duis volutpat felis at scelerisque"][/bw_animated_text_item][bw_animated_text_item text="Sollicitudin in laoreet in"][/bw_animated_text_item][/bw_animated_text][bw_row_inner text_alignment="inherit" static_height="30" vertical_alignment="center" padding_top="50" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100"][bw_column_inner col_width="32.3" text_alignment="inherit" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" res_hide_tablet="1"][/bw_column_inner][bw_column_inner col_width="12.5" text_alignment="inherit" margin_bottom="25" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_video_modal size="medium" size_button="medium" color="#ffffff" bg_color="#ed4f89"]https://www.youtube.com/watch?v=WX8D8xuvrKc[/bw_video_modal][/bw_column_inner][bw_column_inner col_width="23.9" text_alignment="inherit" margin_bottom="25" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_button label="Show me more please" link="#" style="large" bg_color="#ed4f89" bg_color_second="#f74b38" direction="bottom right" border_radius="60" transform_top="1" shadow="1"][/bw_button][/bw_column_inner][bw_column_inner col_width="31.3" text_alignment="inherit" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][/bw_column_inner][/bw_row_inner][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Welcome_Text_Video;



class Playouts_Layout_Notion_Box extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_notion_box';
        $this->name = esc_html__( 'Notion Boxes', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/notion_boxes.png';

    }

    static function output() {
        return '[bw_row row_layout="standard" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" static_height="30" margin_top="100" margin_bottom="100"][bw_column col_width="50" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_bottom="30" padding_right="30" padding_left="30"][bw_notion_box top_text="Category" text="Suspendisse non arcu nec leo libero" sub_text="Vestibulum id lorem et mauris pharetra ultricies. Donec suscipit justo eget ante tristique volutpat." h_tag="h3" text_alignment="inherit" font_size="32" text_color="#ffffff" enable_link="1" link="#" bg_color="#f5f5f5" image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png" scale="1" overlay="1" overlay_bg="#db9b2e"][/bw_notion_box][/bw_column][bw_column col_width="50" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_bottom="30" padding_right="30" padding_left="30"][bw_notion_box top_text="Category" text="Suspendisse non arcu nec leo libero" sub_text="Vestibulum id lorem et mauris pharetra ultricies. Donec suscipit justo eget ante tristique volutpat." h_tag="h3" text_alignment="inherit" font_size="32" text_color="#ffffff" enable_link="1" link="#" bg_color="#f5f5f5" image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-black.png" scale="1" overlay="1" overlay_bg="#db9b2e"][/bw_notion_box][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Notion_Box;



class Playouts_Layout_Numbers_Counter extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_numbers_counter';
        $this->name = esc_html__( 'Numbers Counter', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/numbers_counter.png';

    }

    static function output() {
        return '[bw_row row_layout="standard" background="color" bg_color="#f1f1f1" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" vertical_alignment="center" text_alignment="center" static_height="30" padding_top="150" padding_bottom="150"][bw_column col_width="25" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_bottom="30"][bw_icon icon="pl-7s-medal" font_size="100" color_main="#7e74e1" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text inline_css="margin-bottom:70px;"]<p>Awards taken</p>[/bw_text][bw_number_counter number="9" font_size="75" duration="2" color="#7e74e1"][/bw_number_counter][/bw_column][bw_column col_width="25" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_bottom="30"][bw_icon icon="pl-7s-smile" font_size="100" color_main="#7e74e1" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text inline_css="margin-bottom:70px;"]<p>Happy customers</p>[/bw_text][bw_number_counter number="1250" font_size="75" duration="2" color="#7e74e1"][/bw_number_counter][/bw_column][bw_column col_width="25" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_bottom="30"][bw_icon icon="pl-7s-moon" font_size="100" color_main="#7e74e1" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text inline_css="margin-bottom:70px;"]<p>Nights of hard work</p>[/bw_text][bw_number_counter number="765" font_size="75" duration="2" color="#7e74e1"][/bw_number_counter][/bw_column][bw_column col_width="25" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_bottom="30"][bw_icon icon="pl-7s-alarm" font_size="100" color_main="#7e74e1" direction="bottom right" margin_bottom="40"][/bw_icon][bw_text inline_css="margin-bottom:70px;"]<p>Extra hours</p>[/bw_text][bw_number_counter number="7950" font_size="75" duration="2" color="#7e74e1"][/bw_number_counter][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Numbers_Counter;



class Playouts_Layout_Welcome_Frame_Bg extends Playouts_Admin_Layout {

    function init() {

        $this->id = 'bw_layout_welcome_frame_bg';
        $this->name = esc_html__( 'Full-Width Framed Welcome Text with Background Image', 'peenapo-layouts-txd' );
        $this->layout_view = 'row';
        $this->image = PLAYOUTS_ASSEST . 'admin/images/__layouts/welcome_frame_bg.png';

    }

    static function output() {
        return '[bw_row row_layout="full" background="color" bg_color="#5752ff" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" vertical_alignment="center" enable_static_height="1" static_height="100"][bw_column col_width="100" background="image" bg_color="#ffffff" bg_image="%PLAYOUTS_PATH_ASSETS%images/placeholder-1920_1080-white.png" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" margin_top="20" margin_right="20" margin_bottom="20" margin_left="20"][bw_row_inner text_alignment="inherit" static_height="30" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50" animation="none" animation_speed="200" animation_delay="100"][bw_column_inner col_width="50" overlay_bg_second="#f5f5f5" overlay_opacity="50"][/bw_column_inner][bw_column_inner col_width="50" text_alignment="inherit" padding_left="50" padding_right="50" overlay_bg_second="#f5f5f5" overlay_direction="top right" overlay_opacity="50"][bw_text]This could be a category[/bw_text][bw_heading title="Aenean velit enim, malesuada facilisis pulvinar nec" h_tag="h3" text_alignment="inherit" font_size_heading="60" font_size_content="15" font_size_top="15" bold_text="1" max_width="600" speed="450" delay="100" inline_css="margin-top:15px;"][/bw_heading][bw_button label="This is a button" link="#" style="medium" direction="bottom right" border_radius="3" transform_top="1" shadow="1" margin_bottom="30"][/bw_button][/bw_column_inner][/bw_row_inner][/bw_column][/bw_row]';
    }
}
new Playouts_Layout_Welcome_Frame_Bg;
