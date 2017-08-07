<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * all modal functions
 *
 *
 */
class Playouts_Admin_Modal {

    /*
     * contains all tabs
     *
     */
    static $tabs = array();

    /*
     * holds the favorite element ids
     *
     */
    static $favorites = array();

    /*
     * start modal functions
     *
     */
    static function init() {

        self::$favorites = self::get_favorites_arr();

        # set global variables
        add_action( 'init', array( 'Playouts_Admin_Modal', 'set_globals' ) );

        # actions to render tabs html
        add_action( 'playouts_modal_tabs', array( 'Playouts_Admin_Modal', 'get_tabs_html' ) );
        add_action( 'playouts_modal_tabs_content', array( 'Playouts_Admin_Modal', 'get_tabs_content_html' ) );

        # module dependencies
        add_filter( 'playouts_module_dependencies', array( 'Playouts_Admin_Modal', 'get_dependencies' ) );

    }

    /*
     * set global variables
     *
     */
    static function set_globals() {

        self::$tabs = Playouts_Admin_Modal_Tab::get_tabs();

    }

    /*
     * get the html of the tabs
     *
     */
    static function get_tabs_html() {
        ?><ul class="pl-modal-tabs pl-no-select">
            <?php foreach( self::$tabs as $tab ): ?>
                <li data-tab="<?php echo esc_attr( $tab->id ); ?>"><?php echo esc_html( $tab->name ); ?></li>
            <?php endforeach; ?>
        </ul><?php
    }

    /*
     * get the html of the tabs content
     *
     */
    static function get_tabs_content_html() {

        foreach( self::$tabs as $tab ):
            ?><div class="pl-tab-content pl-tab-content-<?php echo esc_attr( $tab->id ); ?>">

                <?php if( is_callable( $tab->class_name . '::output' ) ) {

                    echo call_user_func_array( $tab->class_name . '::output', array( $tab ) );

                } ?>

            </div><?php
        endforeach;

    }

    /*
     * module dependencies by views
     * one view (key) can be placed in multiple views (array value)
     * __solo means that the module will always be a parent element
     *
     */
    static function get_dependencies( $view = 'all' ) {

        $deps = array(
            'row'               => array( '__solo' ),
            'row_inner'         => array( 'column' ),
            'column'            => array( 'row' ),
            'column_inner'      => array( 'row_inner' ),
            'element'           => array( 'column', 'column_inner' ),
            'repeater'          => array( 'column', 'column_inner' ),
        );

        if( $view == 'all' ) {
            return $deps;
        }else{
            if( isset( $deps[ $view ] ) ) {
                return $deps[ $view ];
            }
        }

        return;
    }

    static function get_dependencies_inverted() {

        $deps = apply_filters( 'playouts_module_dependencies', 'all' );
        $deps_invert = array();

        foreach( $deps as $view => $can_views ) {
            foreach( $can_views as $can_view ) {
                $deps_invert[ $can_view ][] = $view;
            }
        }

        return $deps_invert;

    }

    static function get_favorites_arr() {

        $output = array();
        $favorites = json_decode( html_entity_decode( get_option( 'playouts_favorites' ) ) );

        if( is_array( $favorites ) ) {
            foreach( $favorites as $favorite ) {
                $output[] = $favorite->id;
            }
        }

        return $output;

    }

    static function get_favorites_list() {

        $output = '';
        $favorites = json_decode( html_entity_decode( get_option( 'playouts_favorites' ) ) );

        if( is_array( $favorites ) ) {
            foreach( $favorites as $favorite ) {
                $output .= "<li data-id='{$favorite->id}'>{$favorite->label}</li>";
            }
        }

        return $output;

    }

    /*static function get_view_by_module( $module ) {
        d( self::get_dependencies() );
        d( $module );
        return '__solo';
    }*/
}
Playouts_Admin_Modal::init();

/*
 * out main tab class to extend
 *
 */
class Playouts_Admin_Modal_Tab {

    public $id;

    public $name;

    public $class_name;

    private static $tabs = array();

    function __construct() {

        $this->init();

        $this->class_name = get_class( $this );

        self::$tabs[ $this->id ] = $this;

    }

    static function output() {
        return '';
    }

    static function get_tabs() {
        return self::$tabs;
    }

}

/*
 * MODULES tab
 *
 */
class Playouts_Admin_Modal_Tab_Modules extends Playouts_Admin_Modal_Tab {

    function init() {

        $this->id = 'modules';
        $this->name = esc_html__( 'Modules', 'peenapo-layouts-txd' );

    }

    static function output() {

        ob_start(); ?>

        <div class="pl-modal-bar">

            <span><?php _e( 'Sort by', 'peenapo-layouts-txd' ); ?></span>

            <ul class="pl-modal-categories pl-no-select">
                <li data-category="*"><?php esc_html_e( 'All', 'peenapo-layouts-txd' ); ?></li>
                <?php foreach( Playouts_Element::get_modules_categories() as $id => $label ): ?>
                    <li data-category="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></li>
                <?php endforeach; ?>
            </ul>

        </div>

        <ul class="pl-modal-elements pl-modal-modules pl-no-select">
            <?php foreach( Playouts_Element::get_modules_arr() as $id => $module ): ?>
                <?php if( $module['public'] ): ?>
                    <li data-view="<?php echo esc_attr( $module['view'] ); ?>"
                        data-module="<?php echo esc_attr( $id ); ?>"
                        data-category="<?php echo esc_attr( $module['category'] ); ?>"
                        data-id="module-<?php echo esc_attr( $id ); ?>"
                        <?php if( in_array( 'module-' . $id, Playouts_Admin_Modal::$favorites ) ) { echo 'class="pl-is-favorite"'; } ?>>
                            <div class="pl-element">
                                <span><?php echo esc_html( $module['name'] ); ?></span>
                            </div>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul><?php

        return ob_get_clean();

    }
}
new Playouts_Admin_Modal_Tab_Modules;

/*
 * LAYOUTS tab
 *
 */
class Playouts_Admin_Modal_Tab_Layouts extends Playouts_Admin_Modal_Tab {

    function init() {

        $this->id = 'layouts';
        $this->name = esc_html__( 'Layouts', 'peenapo-layouts-txd' );

    }

    static function output() {

        ob_start(); ?>

        <div class="pl-modal-bar">

            <span><?php _e( 'Sort by', 'peenapo-layouts-txd' ); ?></span>

            <ul class="pl-modal-categories pl-no-select">
                <li data-category="*"><?php esc_html_e( 'All', 'peenapo-layouts-txd' ); ?></li>
                <?php foreach( Playouts_Admin_Layout::get_layout_categories() as $id => $label ): ?>
                    <li data-category="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></li>
                <?php endforeach; ?>
            </ul>

        </div>

        <ul class="pl-modal-elements pl-modal-layouts pl-no-select">
            <?php foreach( Playouts_Admin_Layout::get_modules_arr() as $id => $layout ): ?>
                <?php if( $layout['public'] ): ?>
                    <li data-view="<?php echo esc_attr( $layout['layout_view'] ); ?>"
                        data-layout="<?php echo esc_attr( $id ); ?>"
                        data-category="<?php echo esc_attr( $layout['category'] ); ?>"
                        data-id="layout-<?php echo esc_attr( $id ); ?>"
                        <?php if( in_array( 'layout-' . $id, Playouts_Admin_Modal::$favorites ) ) { echo 'class="pl-is-favorite"'; } ?>>
                            <div class="pl-element">
                                <div class="pl-element-image<?php if( basename( $layout['image'] ) == 'default-layout.png' ) { echo ' pl-element-default-image'; } ?>">
                                    <img src="<?php echo esc_url( $layout['image'] ); ?>" alt="">
                                </div>
                                <span><?php echo esc_html( $layout['name'] ); ?></span>
                            </div>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul><?php

        return ob_get_clean();

    }
}
new Playouts_Admin_Modal_Tab_Layouts;

/*
 * CUSTOM LAYOUTS tab
 *
 */
class Playouts_Admin_Modal_Tab_Custom_Layouts extends Playouts_Admin_Modal_Tab {

    function init() {

        $this->id = 'custom_layouts';
        $this->name = esc_html__( 'Custom Layouts', 'peenapo-layouts-txd' );

    }

    static function output() {

        ob_start(); ?>

        <?php $custom_layout_categories = Playouts_Admin_Layout_Custom::get_categories(); ?>

        <div class="pl-modal-bar">

            <span><?php _e( 'Sort by', 'peenapo-layouts-txd' ); ?></span>

            <ul class="pl-modal-categories pl-modal-multiple-categories pl-no-select">
                <li data-category="*"><?php esc_html_e( 'All', 'peenapo-layouts-txd' ); ?></li>
                <?php if( $custom_layout_categories ): ?>
                    <?php foreach( $custom_layout_categories as $id => $name ): ?>
                        <li data-category="<?php echo $id; ?>"><?php echo $name; ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

        </div>

        <?php $custom_layouts = Playouts_Admin_Layout_Custom::get_layouts_output(); ?>
        <?php if( ! empty( $custom_layouts ) ): ?>
            <ul class="pl-modal-elements pl-modal-layouts pl-modal-custom-layouts pl-no-select">
                <?php foreach( $custom_layouts as $custom_layout_id => $custom_layout ) : ?>
                    <li data-layout-id="<?php echo $custom_layout_id; ?>"
                        data-view="<?php echo $custom_layout['view']; ?>"
                        data-category="<?php echo $custom_layout['category']; ?>"
                        data-id="custom-layout-<?php echo $custom_layout_id; ?>"
                        <?php if( in_array( 'custom-layout-' . $custom_layout_id, Playouts_Admin_Modal::$favorites ) ) { echo 'class="pl-is-favorite"'; } ?>>
                            <div class="pl-element">
                                <div class="pl-element-image">
                                    <img src="<?php echo PLAYOUTS_ASSEST; ?>admin/images/default-layout.png" alt="">
                                </div>
                                <span><?php echo esc_html( $custom_layout['name'] ); ?></span>
                            </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <ul class="pl-modal-elements pl-modal-layouts pl-modal-custom-layouts pl-no-select">
                <li class="pl-empty"><?php esc_html_e( 'There are no custom layouts!', 'peenapo-layouts-txd' ); ?></li>
            </ul>
        <?php endif;

        return ob_get_clean();

    }
}
new Playouts_Admin_Modal_Tab_Custom_Layouts;

?>
