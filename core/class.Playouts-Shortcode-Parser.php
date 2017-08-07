<?php

// TODO: remove this file, it is not used

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * converts shortcode string to object
 *
 *
 */
class Playouts_Shortcode_Parser {

    /*
     * i am unique id
     *
     */
    static $unique_id = 0;

    /*
     * shortcode regex containing plugin's modules
     *
     */
    static $regex;

    /*
     * generate a unique id
     *
     */
    static function get_unique_id() {
        return rand( 1111111, 9999999 ) . '-' . uniqid();
    }

    /*
     * get the regex
     *
     */
    static function get_shortcode_regex() {
		if( ! self::$regex ) {
            $__all = implode( '|', Playouts_Element::get_modules_raw() ) . '|' . implode( '|', Playouts_Repeater_Element::get_modules_repeater_raw() ) . '|' . implode( '|', Playouts_Repeater_Item_Element::get_modules_repeater_item_raw() );
            self::$regex = '\[(\[?)(' . $__all . ')(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
		}
        return self::$regex;
    }

    static function get_pattern( $text ) {

        $pattern = self::get_shortcode_regex();
        preg_match_all( "/$pattern/s", $text, $c );

        return $c;
    }

    /*
     * extract the attributes from shortcode
     *
     */
    static function parse_attributes( $attributes ) {

        preg_match_all( '/([^ ]*)=(\'([^\']*)\'|\"([^\"]*)\"|([^ ]*))/', trim( $attributes ), $attribute );

        list( $dummy, $keys, $values ) = array_values( $attribute );

        $attribute = array();
        foreach ( $keys as $key => $value ) {

            $value = trim( $values[ $key ], "\"'" );
            $attribute[ $keys[ $key ] ] = Playouts_Functions::quote_decode( $value );

        }
        return $attribute;
    }

    /*
     * initiate the parsing
     *
     */
    static function parse( &$output, $string, $is_children = false, $level = 0, $parent_id = 0 ) {

        $patts_all = self::get_pattern( $string );

        $patts = array_filter( $patts_all );

        if ( ! empty( $patts ) ) {

            list( $dummy, $dummy, $shortcodes, $atts, $dummy, $contents ) = $patts_all;

            $n = 0;
            $level++;
            $parent_id = $level > 1 ? self::get_unique_id() : 0;

            foreach( $shortcodes as $k => $parent ) {

                $parse_next = array();
                $patts = array_filter( self::get_pattern( $contents[ $k ] ) );
                $next_parse = self::parse( $parse_next, $contents[ $k ], true, $level, $parent_id );
                $unique_id = ( self::$unique_id == 0 or self::$unique_id == $parent_id or ( empty( $patts ) and empty( $next_parse ) ) ) ? self::get_unique_id() : self::$unique_id;
                self::$unique_id = $parent_id;

                $key = $is_children ? 'child-' . $n : $n;

                $output[ $key ] = array( 'module' => $shortcodes[ $k ] );
                $output[ $key ]['params']           = self::parse_attributes( $atts[ $k ] );
                $output[ $key ]['uid']              = $unique_id;
                $output[ $key ]['parent_id']        = $parent_id;
                $output[ $key ]['level']            = $level;

                if( empty( $patts ) and empty( $next_parse ) ) {
                    $output[ $key ]['is_content']   = true;
                    $output[ $key ]['children']     = $contents[ $k ];
                }else{
                    $output[ $key ]['children']     = $next_parse;
                }

                $n++;
            }
        }
        return array_values( $output );
    }
}
