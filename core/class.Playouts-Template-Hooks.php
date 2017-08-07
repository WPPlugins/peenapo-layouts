<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * All template hooks
 *
 *
 */
class Playouts_Template_Hooks {

    static function init() {

        add_action( 'playouts_get_public_templates', array( 'Playouts_Template_Functions', 'get_templates' ), 10 );

        add_action( 'playouts_get_template_video_modal', array( 'Playouts_Template_Functions', 'get_template_video_modal' ), 10 );

    }

}
Playouts_Template_Hooks::init();
