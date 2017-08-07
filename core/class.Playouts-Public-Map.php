<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * mapping, load elements and required info to render the public part
 *
 *
 */
class Playouts_Public_Map {

    static $strings;

    static function init() {

        self::set_strings();

        include PLAYOUTS_DIR . 'core/class.Playouts-Element.php';

        add_action( 'wp_footer', array( 'Playouts_Public_Map', 'front_params' ) );

    }

    static function front_params() {

        wp_localize_script( 'playouts-front', 'playouts_params', array(

            'is_mobile' => wp_is_mobile(),
            'i18n' => Playouts_Public_Map::$strings,

        ));

    }

    static function set_strings() {

        self::$strings = array(

            'days' => __( 'Days', 'peenapo-layouts-txd' ),
            'hours' => __( 'Hours', 'peenapo-layouts-txd' ),
            'minutes' => __( 'Minutes', 'peenapo-layouts-txd' ),
            'seconds' => __( 'Seconds', 'peenapo-layouts-txd' ),

        );

    }

}

# define and map all the elements
Playouts_Public_Map::init();
