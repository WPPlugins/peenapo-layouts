<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * All template hooks
 *
 *
 */
class Playouts_Admin_Template_Hooks {

    static function init() {

        //add_action( 'playouts_get_template_switch_button',          array( 'Playouts_Admin_Template_Functions', 'get_template_switch_button' ), 10 );
        add_action( 'playouts_get_template_main',                   array( 'Playouts_Admin_Template_Functions', 'get_template_main' ), 10 );
        add_action( 'playouts_get_template_switch',                 array( 'Playouts_Admin_Template_Functions', 'get_template_switch' ), 10 );
        add_action( 'playouts_get_template_editor_not_supported',   array( 'Playouts_Admin_Template_Functions', 'get_template_editor_not_supported' ), 10 );
        add_action( 'playouts_get_template_header',                 array( 'Playouts_Admin_Template_Functions', 'get_template_header' ), 10 );
        add_action( 'playouts_get_template_welcome',                array( 'Playouts_Admin_Template_Functions', 'get_template_welcome' ), 10 );
        add_action( 'playouts_get_template_mosaic',                 array( 'Playouts_Admin_Template_Functions', 'get_template_mosaic' ), 10 );
        add_action( 'playouts_get_template_elements',               array( 'Playouts_Admin_Template_Functions', 'get_template_elements' ), 10 );
        add_action( 'playouts_get_template_partials',               array( 'Playouts_Admin_Template_Functions', 'get_template_partials' ), 10 );
        add_action( 'playouts_get_template_settings_panel',         array( 'Playouts_Admin_Template_Functions', 'get_template_settings_panel' ), 10 );
        add_action( 'playouts_get_template_icons',                  array( 'Playouts_Admin_Template_Functions', 'get_template_icons' ), 10 );
        add_action( 'playouts_get_template_modal',                  array( 'Playouts_Admin_Template_Functions', 'get_template_modal' ), 10 );
        add_action( 'playouts_get_template_modal_favorites',        array( 'Playouts_Admin_Template_Functions', 'get_template_modal_favorites' ), 10 );
        add_action( 'playouts_get_template_custom_css_panel',       array( 'Playouts_Admin_Template_Functions', 'get_template_custom_css_panel' ), 10 );
        add_action( 'playouts_get_template_overlay',                array( 'Playouts_Admin_Template_Functions', 'get_template_overlay' ), 10 );
        add_action( 'playouts_get_template_footer',                 array( 'Playouts_Admin_Template_Functions', 'get_template_footer' ), 10 );
        add_action( 'playouts_get_template_settings',               array( 'Playouts_Admin_Template_Functions', 'get_template_guide' ), 10 );
        add_action( 'playouts_get_template_settings_header',        array( 'Playouts_Admin_Template_Functions', 'get_template_guide_header' ), 10 );
        add_action( 'playouts_get_template_settings_theme_options', array( 'Playouts_Admin_Template_Functions', 'get_template_guide_theme_options' ), 10 );
        add_action( 'playouts_get_template_save_layout',            array( 'Playouts_Admin_Template_Functions', 'get_template_save_layout' ), 10 );
        add_action( 'playouts_get_template_confirm',                array( 'Playouts_Admin_Template_Functions', 'get_template_confirm' ), 10 );

    }

}
Playouts_Admin_Template_Hooks::init();
