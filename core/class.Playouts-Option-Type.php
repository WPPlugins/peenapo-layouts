<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * declare option types
 *
 *
 */
class Playouts_Option_Type {

    public $id;

    public $name;

    public $class_name;

    private static $index = 0;
    private static $option_types = array();

    function __construct() {

        self::$index++;

        $this->init();

        $this->class_name = get_class( $this );

        self::$option_types[ $this->id ] = $this;

    }

    /*
     * the html template of the option type
     * input, select, etc..
     *
     */
    static function template( $values ) {
        return '';
    }

    /*
     * get all the option types
     * returns array
     *
     */
    static function get_otypes() {
        return self::$option_types;
    }

    static function get_otype( $type ) {
        if( array_key_exists( $type, self::$option_types ) ) {
            return self::$option_types[ $type ];
        }
        return;
    }

    static function get_otypes_raw() {
        $otypes = array();
        foreach( self::get_otypes() as $otype ) {
            $otypes[] = $otype->id;
        }
        return $otypes;
    }

    static function get_option_heading( $label, $description ) {

        $info = $description ? '<i class="pl-icon-info pl-no-select"></i>' : '';

        $__out = "<h5>{$label}{$info}</h5>";
        if( $description ) {
            $__out .= "<div class='pl-header-info'><p>{$description}</p></div>";
        }
        return $__out;
    }

    static function get_option_template( $class_name, $values ) {

        $output = '';

        if( is_callable( $class_name . '::template' ) ) {

            if( ! isset( $values->description ) ) { $values->description = ''; }

            // ui_remove: won't appear in the options panel and won't be queried as parameter
            if( isset( $values->ui_remove ) and $values->ui_remove == true ) {
                // .. do nothing
            }
            // ui_hide: won't be visible in the options panel, but still will be queried as parameter
            elseif( isset( $values->ui_hide ) and $values->ui_hide == true ) {

                $output .= '<div class="pl-panel-row pl-panel-row-hidden">';
                $output .= call_user_func_array( $class_name . '::template', array( $values ) );
                $output .= '</div>';

            }
            // ui standard
            else{

                if( isset( $values->no_wrap ) and $values->no_wrap == true ) {

                    $output .= call_user_func_array( $class_name . '::template', array( $values ) );

                }else{

                    $styles = '';
                    $depends = isset( $values->depends );

                    $class  = 'pl-panel-row';
                    $class .= ' pl-row-option-' . $values->type;
                    $class .= isset( $values->tab ) ? ' pl-row-tab-' . array_shift( array_keys( (array) $values->tab ) ) : ' pl-row-tab-general';
                    if( $depends ) {
                        $class .= ' pl-row-depends';
                        $styles .= 'height:0;';
                    }
                    if( isset( $values->width ) ) {
                        $width = (int) $values->width;
                        $styles .= "width:{$width}%;";
                    }

                    $attr  = " data-type='{$values->type}'";
                    $attr .= " data-id='{$values->name}'";
                    if( $depends ) {
                        if( is_array( $values->depends ) ) { $values->depends = (object) $values->depends; }
                        if( is_array( $values->depends->value ) ) {
                            $attr .= " data-depends-on='{$values->depends->element}' data-depends-value='" . implode( ',', $values->depends->value ) . "'";
                        }else{
                            $attr .= " data-depends-on='{$values->depends->element}' data-depends-value='{$values->depends->value}'";
                        }
                    }

                    $output .= "<div class='{$class}' style='{$styles}'{$attr}><div class='pl-panel-row-inner'>";
                    $output .= call_user_func_array( $class_name . '::template', array( $values ) );
                    $output .= '</div></div>';

                }
            }

        }else{

            $output .= '<div class="pl-panel-row' . ( isset( $values->tab ) ? ' pl-row-tab-' . array_shift( array_keys( (array) $values->tab ) ) : ' pl-row-tab-general' ) . '"><div class="pl-panel-row-inner">';
            $output .= '<div class="pl-panel-no-option">' . sprintf( esc_html__( 'Option type "%1$s" doesn\'t exists.', 'peenapo-layouts-txd' ), $values->type ) . '</div>';
            $output .= '</div></div>';

        }

        return $output;

    }

    /*
     * echo the option html
     *
     */
    static function render_option( $option_name, $atts, $parent = false ) {

        if( isset( $atts['type'] ) and ! empty( $atts['type'] ) ) {

            $otypes = self::get_otypes();
            $values = get_option('playouts_layouts_options');

            $atts['name'] = 'playouts_options' . ( $parent ? '[' . $parent . ']' : '' ) . '[' . $option_name . ']';

            if( $parent ) {
                $atts['value'] = ( isset( $values[ $parent ] ) and isset( $values[ $parent ][ $option_name ] ) ) ? $values[ $parent ][ $option_name ] : '';
            }else{
                $atts['value'] = isset( $values[ $option_name ] ) ? $values[ $option_name ] : '';
            }

            echo self::get_option_template( $otypes[ $atts['type'] ]->class_name, (object) $atts );

        }
        // array option
        elseif( is_array( $atts ) ) {

            foreach( $atts as $key => $attr ) {
                self::render_option( $key, $attr, $option_name );
            }

        }

    }

}

class Playouts_Option_Type_Dummy extends Playouts_Option_Type {

    function init() {

        $this->id = 'dummy';
        $this->name = esc_html__( 'Dummy', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        return '';

    }
}
new Playouts_Option_Type_Dummy;

class Playouts_Option_Type_Heading extends Playouts_Option_Type {

    function init() {

        $this->id = 'heading';
        $this->name = esc_html__( 'Heading', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = "<h5>{$label}</h5>";
        if( $description ) {
            $__out .= "<p>{$description}</p>";
        }
        return $__out;

    }
}
new Playouts_Option_Type_Heading;

class Playouts_Option_Type_Textfield extends Playouts_Option_Type {

    function init() {

        $this->id = 'textfield';
        $this->name = esc_html__( 'Textfield', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $placeholder = isset( $placeholder ) ? "placeholder='{$placeholder}'" : '';

        $__out = self::get_option_heading( $label, $description );
        $__out .= "<input type='text' name='{$name}' value='{$value}'{$placeholder}>";

        return $__out;

    }
}
new Playouts_Option_Type_Textfield;

class Playouts_Option_Type_Textarea extends Playouts_Option_Type {

    function init() {

        $this->id = 'textarea';
        $this->name = esc_html__( 'Textarea', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );
        $__out .= "<textarea name='{$name}' rows='6'>{$value}</textarea>";

        return $__out;

    }
}
new Playouts_Option_Type_Textarea;

class Playouts_Option_Type_Editor extends Playouts_Option_Type {

    function init() {

        $this->id = 'editor';
        $this->name = esc_html__( 'Editor', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );

        $__out .= "<div class='pl-tinymce-container tmce-active' data-editor-id='playouts_tinymce_{$name}'>";
        $__out .= "<div id='wp-playouts_tinymce_{$name}-editor-tools' class='wp-editor-tools hide-if-no-js'>";

        $media = '';
        if ( current_user_can( 'upload_files' ) ) {
            ob_start();
            do_action( 'media_buttons', "playouts_tinymce_{$name}" );
            $media = ob_get_clean();
        }

        $__out .= "<div id='wp-playouts_tinymce_{$name}-media-buttons' class='wp-media-buttons'>{$media}</div>";

        $__out .= "<div class='wp-editor-tabs'>
                    <button type='button' data-switch='tmce' class='wp-switch-editor switch-tmce' onclick='Playouts_Option_Type.option_types.editor.switch_editor(this);'>" . __( 'Visual', 'peenapo-layouts-txd' ) . "</button>
                    <button type='button' data-switch='html' class='wp-switch-editor switch-html' onclick='Playouts_Option_Type.option_types.editor.switch_editor(this);'>" . __( 'Text', 'peenapo-layouts-txd' ) . "</button>
                </div>
            </div>
            <textarea id='playouts_tinymce_{$name}' class='pl-tinymce-textarea' name= '{$name}'>{$value}</textarea>
        </div>";

        return $__out;

    }
}
new Playouts_Option_Type_Editor;

class Playouts_Option_Type_Base64 extends Playouts_Option_Type {

    function init() {

        $this->id = 'base64';
        $this->name = esc_html__( 'Base 64', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );
        $__out .= "<input name='{$name}' value='{$value}'>";
        $__out .= '<textarea rows="6">' . base64_decode( $value ) . '</textarea>';

        return $__out;

    }
}
new Playouts_Option_Type_Base64;

class Playouts_Option_Type_Colorpicker extends Playouts_Option_Type {

    function init() {

        $this->id = 'colorpicker';
        $this->name = esc_html__( 'Color Picker', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );
        $__out .= "<input type='text' name='{$name}' value='{$value}' class='pl-colorpicker' data-default-color=''>";

        return $__out;

    }
}
new Playouts_Option_Type_Colorpicker;

class Playouts_Option_Type_True_False extends Playouts_Option_Type {

    function init() {

        $this->id = 'true_false';
        $this->name = esc_html__( 'True False', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );

        $__active = $value ? ' pl-active' : '';
        $__checked = $value ? ' checked="checked"' : '';

        $__out .= "<label class='pl-true-false{$__active}' for='playouts_true_false_{$name}'>".
            "<input type='checkbox' name='{$name}' id='playouts_true_false_{$name}' value='1'{$__checked}>".
        "</label>";

        return $__out;

    }
}
new Playouts_Option_Type_True_False;

class Playouts_Option_Type_Radio extends Playouts_Option_Type {

    function init() {

        $this->id = 'radio';
        $this->name = esc_html__( 'Radio Button', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );

        $key = 0;
        foreach( $options as $__value => $__label ) {
            $__checked = $value == $__value ? ' checked="checked"' : '';
            $__out .= "<label class='pl-option-radio' for='playouts_radio_{$name}_{$key}'>
                <input id='playouts_radio_{$name}_{$key}' type='radio' name='{$name}' value='{$__value}'{$__checked}>{$__label}
            </label>";
            $key++;
        }

        return $__out;

    }
}
new Playouts_Option_Type_Radio;

class Playouts_Option_Type_Radio_Image extends Playouts_Option_Type {

    function init() {

        $this->id = 'radio_image';
        $this->name = esc_html__( 'Radio Image Button', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );

        $__key = 0;
        foreach( $options as $__value => $__option ) {
            $__option = (object) $__option;
            $__checked = $value == $__value ? ' checked="checked"' : '';
            $__out .= "<label class='pl-option-radio-image' for='playouts_radio_{$name}_{$__key}'>
                <div class='pl-radio-image pl-no-select'>
                    <img src='{$__option->image}' alt=''>
                    <span>{$__option->label}</span>
                </div>
                <input id='playouts_radio_{$name}_{$__key}' type='radio' name='{$name}' value='{$__value}'{$__checked}>
            </label>";
            $__key++;
        }

        return $__out;

    }
}
new Playouts_Option_Type_Radio_Image;

class Playouts_Option_Type_Select extends Playouts_Option_Type {

    function init() {

        $this->id = 'select';
        $this->name = esc_html__( 'Select', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );
        $__out .= "<select name='{$name}'>";

        foreach( $options as $__id => $__label ) {
            $__checked = $__id == $value ? ' selected="selected"' : '';
            $__out .= "<option value='{$__id}'{$__checked}>{$__label}</option>";
        }

        $__out .= "</select>";

        return $__out;

    }
}
new Playouts_Option_Type_Select;

class Playouts_Option_Type_Image extends Playouts_Option_Type {

    function init() {

        $this->id = 'image';
        $this->name = esc_html__( 'Image', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );
        $__has_image = ! empty( $value ) ? ' pl-has-image' : '';
        $__out .= "<div class='pl-option-image{$__has_image}'>
                <div class='pl-upload-field'>
                    <input type='text' name='{$name}' value='{$value}' />
                    <a href='#' class='pl-button-round pl-upload-button'>" . __( 'Upload Image', 'peenapo-layouts-txd' ) . "</a>
                </div>
                <div class='pl-image-preview'>
                    <img src='{$value}' alt=''>
                    <span class='pl-image-remove'><i class='pl-plus'><span></span></i></span>
                </div>
            </div>";

        return $__out;

    }
}
new Playouts_Option_Type_Image;

class Playouts_Option_Type_File extends Playouts_Option_Type {

    function init() {

        $this->id = 'file';
        $this->name = esc_html__( 'File', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );
        $__out .= "<div class='pl-option-file'>
            <div class='pl-upload-field'>
                <input type='text' name='{$name}' value='{$value}' />
                <div class='pl-button-round pl-upload-button'>" . __( 'Select File', 'peenapo-layouts-txd' ) . "</div>
            </div>
        </div>";

        return $__out;

    }
}
new Playouts_Option_Type_File;

class Playouts_Option_Type_Number_Slider extends Playouts_Option_Type {

    function init() {

        $this->id = 'number_slider';
        $this->name = esc_html__( 'Number Slider', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        if( empty( $value ) ) {
            if( $min ) {
                $value = $min;
            }else{
                $value = 0;
            }
        }

        $__out = self::get_option_heading( $label, $description );
        $__out .= "<div class='pl-option-number-slider'>
            <span class='pl-nslider-heading'>" . ( isset( $append_before ) ? $append_before . ' ' : '' ) . "<i>{$value}</i>" . ( isset( $append_after ) ? ' ' . $append_after : '' ) . "</span>
            <div class='pl-number-slider' data-min='{$min}' data-max='{$max}' data-step='{$step}' data-value='{$value}'>
                <input type='hidden' name='{$name}' value='{$value}'>
            </div>
        </div>";



        return $__out;

    }
}
new Playouts_Option_Type_Number_Slider;

class Playouts_Option_Type_Sidebar extends Playouts_Option_Type {

    function init() {

        $this->id = 'sidebars';
        $this->name = esc_html__( 'Sidebars', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        global $wp_registered_sidebars;
        $sidebars = $wp_registered_sidebars;

        $__out = self::get_option_heading( $label, $description );
        $__out .= "<select name='{$name}'>";
        $__out .= "<option value='0'>" . esc_html__( 'Select Sidebar', 'peenapo-layouts-txd' ) . "</option>";
        foreach( $wp_registered_sidebars as $sidebar ) {
            $__checked = $value == $sidebar['id'] ? ' selected="selected"' : '';
            $__out .= "<option value='{$sidebar['id']}'{$__checked}>{$sidebar['name']}</option>";
        }

        $__out .= "</select>";

        return $__out;

    }
}
new Playouts_Option_Type_Sidebar;

class Playouts_Option_Type_Icon extends Playouts_Option_Type {

    function init() {

        $this->id = 'icon';
        $this->name = esc_html__( 'Icon', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out = self::get_option_heading( $label, $description );
        $__out .= "<div class='pl-option-icon'>
            <div class='pl-icon-buttons'>
                <div class='pl-icon-label'><i class='{$value}'></i></div>
                <div class='pl-icon-expand'><i></i></div>
            </div>
            <input type='hidden' name='{$name}' value='{$value}'>
            <ul class='pl-icon-container' data-font='{$value}' data-icon='{$value}'></ul>
        </div>";

        return $__out;

    }
}
new Playouts_Option_Type_Icon;

class Playouts_Option_Type_Taxonomy extends Playouts_Option_Type {

    function init() {

        $this->id = 'taxonomy';
        $this->name = esc_html__( 'Taxonomy', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

		$__multi_values = strpos( $value, ',' ) !== false ? true : false;
		if( $__multi_values ) {
			$__values_arr = array_filter( explode( ',', $value ) );
		}

		$__out_select = '';
		$post_type = isset( $post_type ) ? $post_type : 'category';

		$__tax_query = get_terms( $post_type, array( 'hide_empty' => false ) );

		if( ! is_wp_error( $__tax_query ) ) {

			foreach( $__tax_query as $__tx ) {
				if( $__multi_values ) {
					$__checked = in_array( $__tx->term_id, $__values_arr ) ? ' selected="selected"' : '';
				}else{
					$__checked = $__tx->term_id == $value ? ' selected="selected"' : '';
				}
				$__out_select .= '<option value="' . $__tx->term_id . '"' . $__checked . '>' . $__tx->name . '</option>';
			}

		}

        $__multiple = isset( $multiple ) ? ' multiple' : '';

        $__out = self::get_option_heading( $label, $description );
        $__out .= "<div class='pl-option-taxonomy pl-ajaxing'>";
        $__out .= "<select name='{$name}'{$__multiple}>{$__out_select}</select>";
        $__out .= "</div>";

        return $__out;

    }
}
new Playouts_Option_Type_Taxonomy;

class Playouts_Option_Type_Repeater extends Playouts_Option_Type {

    function init() {

        $this->id = 'repeater';
        $this->name = esc_html__( 'Repeater', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out  = self::get_option_heading( $label, $description );
        $__out .= '<div class="pl-option-repeater" data-repeater-module="">';
        $__out .= '<div class="pl-repeater-content"></div>';
        $__out .= '<div class="pl-repeater-plus"><i class="pl-plus"><span></span></i></div>';
        $__out .= '</div>';

        return $__out;

    }
}
new Playouts_Option_Type_Repeater;

class Playouts_Option_Type_Columns extends Playouts_Option_Type {

    function init() {

        $this->id = 'columns';
        $this->name = esc_html__( 'Columns', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $__out  = self::get_option_heading( $label, $description );
        $__out .= '<div class="pl-option-columns"></div>';
        $__out .= '<div class="pl-column-actions">';
        $__out .= '<a href="#" class="pl-button-round pl-option-remove-column">' . __( 'Remove Columns', 'peenapo-layouts-txd' ) . '</a>';
        $__out .= '<a href="#" class="pl-button-round pl-button-primary pl-option-add-column">' . __( 'Add New Columns', 'peenapo-layouts-txd' ) . '</a>';
        $__out .= '</div>';

        return $__out;

    }
}
new Playouts_Option_Type_Columns;

class Playouts_Option_Type_Thumbnail_Sizes extends Playouts_Option_Type {

    function init() {

        $this->id = 'thumbnail_sizes';
        $this->name = esc_html__( 'Thumbnail Sizes', 'peenapo-layouts-txd' );

    }

    static function template( $values ) {

        extract( (array) $values );

        $sizes = get_intermediate_image_sizes();

        $__out  = self::get_option_heading( $label, $description );
        $__out .= "<select name='{$name}'>";

        foreach( $sizes as $size ) {
            $__checked = $size == $value ? ' selected="selected"' : '';
            $__out .= "<option value='{$size}'{$__checked}>{$size}</option>";
        }

        $__out .= "</select>";

        return $__out;

        return $__out;

    }
}
new Playouts_Option_Type_Thumbnail_Sizes;

class Playouts_Option_Type_Google_Font extends Playouts_Option_Type {

    static $google_fonts;

    function init() {

        $this->id = 'google_font';
        $this->name = esc_html__( 'Google Font', 'peenapo-layouts-txd' );

		self::$google_fonts = require PLAYOUTS_DIR . 'inc/google_fonts.php';

    }

    static function get_font_option( $font, $key, $value ) {

        $data_variants = '';
        if( isset( $font['variants'] ) and ! empty( $font['variants'] ) and is_array( $font['variants'] ) ) {
            $data_variants = ' data-variants="' . implode(',', $font['variants']) . '"';
        }
        $data_subsets = '';
        if( isset( $font['subsets'] ) ) {
            $data_subsets = ' data-subsets="' . implode(',', $font['subsets']) . '"';
        }
        $selected = '';
        if( isset( $value['family'] ) and $value['family'] == $font['family'] ) {
            $selected = ' selected="selected"';
        }
        echo "<option value='{$font['family']}'{$data_variants}{$data_subsets}{$selected}>{$font['family']}</option>";
    }

    static function template( $values ) {

        extract( (array) $values );

        $current_value = $value;
        $current_value_json = $current_value;

        ob_start();

        echo self::get_option_heading( $label, $description ); ?>

        <!-- font value -->
        <input type="hidden" class="bwpc-font-value" value='<?php echo (string) stripslashes( $current_value_json ); ?>' name="<?php echo $name; ?>" data-id="<?php echo $name; ?>">
        <!-- font family -->
        <select class="bwpc-font-family">
            <option value=""><?php esc_html_e( 'Select Font', 'peenapo-layouts-txd' ); ?></option>
            <?php foreach ( self::$google_fonts as $key => $font ) {
                self::get_font_option( $font, $key, $current_value );
            } ?>
        </select>

        <!-- variants -->
        <select class="bwpc-font-variants" multiple>
            <?php if( isset( $current_value->variants ) && ! empty( $current_value->variants ) && is_array( $current_value->variants ) ): ?>
                <?php //echo "<option value=''>" . esc_html__( 'Select font variant', 'peenapo-layouts-txd' ) . "</option>"; ?>
                <?php foreach ( $current_value->variants as $key => $variant ): ?>
                    <?php echo "<option value='{$variant}'>{$variant}</option>"; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <!-- subsets -->
        <select class="bwpc-font-subsets" multiple>
            <?php if( isset( $current_value->subsets ) && ! empty( $current_value->subsets ) && is_array( $current_value->subsets ) ): ?>
                <?php foreach ( $current_value->subsets as $key => $subset ): ?>
                    <?php echo "<option value='{$subset}'>{$subset}</option>"; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <?php if( isset( $preview ) and ! empty( $preview ) ): ?>
            <span class="pl-demo-google-font"<?php if( isset( $font_size ) and ! empty( $font_size ) ) { echo ' style="font-size:' . $font_size . ';"'; } ?>><?php echo $preview; ?></span><?php
        endif;

        return ob_get_clean();

    }
}
new Playouts_Option_Type_Google_Font;
