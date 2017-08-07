<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * manage dynamic google fonts
 *
 *
 */
class Playouts_Public_Fonts {

    /*
     * define the default google fonts
     *
     */
    static $default_google_fonts = array();
    static $font_declarations = '';

    /*
     * Fire the public scrap
     *
     */
	static function init() {

        # font declaration
        self::font_declaration();

	}

    static function font_declaration() {

        self::$default_google_fonts = array(

            'font_container' => array(
                'default' => array( 'family' => 'Nunito' ),
                'selectors' => '.pl-outer, .pl-pricing-column .pl-pricing-title'
            ),
            'font_headings' => array(
                'default' => array( 'family' => 'Roboto', 'variants' => '500' ),
                'selectors' => '.pl-outer h1, .pl-outer h2, .pl-outer h3, .pl-outer h4, .pl-outer h5, .pl-outer h6, .pl-not-finer .pl-testimonial-content p, .pl-testimonial-name, .pl-video-modal, .pl-hotspot-box strong, .pl-number-counter'
            )

        );

    }

    static function output_google_font() {

        $font_url = $family_variants = '';
        $subsets_final = array();
        $options = get_option( 'playouts_layouts_options' );

        foreach( self::$default_google_fonts as $font_key => $opts ) {

            $font = isset( $options[ $font_key ] ) ? json_decode( stripcslashes( $options[ $font_key ] ) ) : '';

            if( isset( $font_key['default']['self'] ) ) {
                self::collect_font_declaration( $opts['selectors'], $opts['default'] );
                continue;
            }

            if( ! isset( $font->family ) or empty( $font->family ) ) {
                $font = (object) $opts['default'];
            }

            if( ! ( isset( $font->self ) and $font->self == true ) ) {

                $family_variants .= '|' . $font->family;

                if( isset( $font->variants ) and ! empty( $font->variants ) ) {
                    $family_variants .= ':' . $font->variants;
                }
            }

            if( isset( $font->subsets ) and ! empty( $font->subsets ) ) {
                $subsets_arr = array_filter( explode( ',', $font->subsets ) );
                foreach( $subsets_arr as $subset ) {
                    if( ! in_array( $subset, $subsets_final ) ) {
                        $subsets_final[] = $subset;
                    }
                }
            }

            if( isset( $opts['selectors'] ) ) {
                self::collect_font_declaration( $opts['selectors'], $font );
            }
        }

        if( empty( $family_variants ) ) { return; }

        $subsets_final = ( count( $subsets_final ) > 0 ? '&' : '' ) . implode( ',', $subsets_final );
        if( ! empty( $family_variants ) ) { $family_variants = ltrim( $family_variants, '|' ); }

        $font_url = add_query_arg( 'family', urlencode( $family_variants ) . $subsets_final, "//fonts.googleapis.com/css" );
        return $font_url;

    }

    static function variants( $variant ) {

        switch( $variant ) {
            case is_numeric( $variant ):
                return 'font-weight:' . $variant . ';'; break;
            case $variant == 'regular':
                return 'font-weight:400;'; break;
            case $variant == 'italic':
                return 'font-weight:400;font-style:italic;'; break;
            case substr( $variant, -1 ) == 'i' or substr( $variant, -6 ) == 'italic':
                return 'font-weight:' . $variant . ';font-style:italic;'; break;
            default:
                return 'font-weight:' . $variant . ';';
        }

    }

    static function collect_font_declaration( $selectors, $font_obj ) {

        if( is_array( $font_obj ) ) { $font_obj = (object) $font_obj; }
        $variants = '';
        if( isset( $font_obj->variants ) ) {
            $variants .= self::variants( $font_obj->variants );
        }
        //self::$font_declarations .= $selectors . '{font-family:"' . esc_html( $font_obj->family ) . '";' . esc_attr( $variants ) . '}';
        self::$font_declarations .= $selectors . '{font-family:"' . esc_html( $font_obj->family ) . '";}';

    }
}
Playouts_Public_Fonts::init();
