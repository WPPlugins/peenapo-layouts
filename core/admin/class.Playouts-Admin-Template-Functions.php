<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * All template functions
 *
 *
 */
class Playouts_Admin_Template_Functions {

    /*static function get_template_switch_button() {
        self::get_template( 'admin/switch-button' );
    }*/

    static function get_template_main() {
        self::get_template( 'admin/main' );
    }

    static function get_template_switch() {
        self::get_template( 'admin/switch' );
    }

    static function get_template_editor_not_supported() {
        self::get_template( 'admin/editor-not-supported' );
    }

    static function get_template_header() {
        self::get_template( 'admin/header' );
    }

    static function get_template_welcome() {
        self::get_template( 'admin/welcome' );
    }

    static function get_template_mosaic() {
        self::get_template( 'admin/mosaic' );
    }

    static function get_template_elements() {
        self::get_template( 'admin/modules' );
    }

    static function get_template_settings_panel() {
        self::get_template( 'admin/settings-panel' );
    }

    static function get_template_modal() {
        self::get_template( 'admin/modal' );
    }

    static function get_template_modal_favorites() {
        self::get_template( 'admin/modal-favorites' );
    }

    static function get_template_custom_css_panel() {
        self::get_template( 'admin/custom-css-panel' );
    }

    static function get_template_overlay() {
        self::get_template( 'admin/overlay' );
    }

    static function get_template_footer() {
        self::get_template( 'admin/footer' );
    }

    static function get_template_icons() {
        self::get_template( 'admin/icons' );
    }

    static function get_template_guide() {
        self::get_template( 'admin/settings/main' );
    }

    static function get_template_guide_header() {
        self::get_template( 'admin/settings/header' );
    }

    static function get_template_guide_theme_options() {
        self::get_template( 'admin/settings/theme-options' );
    }

    static function get_template_partials() {
        self::get_template( 'admin/partials' );
    }

    static function get_template_save_layout() {
        self::get_template( 'admin/save-layout' );
    }

    static function get_template_confirm() {
        self::get_template( 'admin/confirm' );
    }

    static function get_template( $template ) {
        include PLAYOUTS_DIR . 'templates/' . $template . '.php';
    }

}
