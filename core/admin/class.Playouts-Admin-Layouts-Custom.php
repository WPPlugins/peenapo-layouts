<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * helper functions for custom layouts
 *
 *
 */
class Playouts_Admin_Layout_Custom {

    /*
     * extract first module from string
     * note: that does not contains dummy parameter
     *
     */
    static $regex_extract_first_module = '/\[(\w+)\s(?!.*?dummy="yes").*\](.*)/';

    /*
     *
     *
     */
    static function get_layouts_output() {

        $query = new WP_Query( array(
            'post_type'         => 'playouts_layout',
            'posts_per_page'    => -1,
            'post_status'       => 'publish'
        ));

        $output = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) { $query->the_post();
                $layout_id = get_the_ID();
                $output[ $layout_id ] = array(
                    'name'      => get_the_title(),
                    'view'      => self::get_layout_view( $layout_id ),
                    'category'  => self::get_categories_by_id( $layout_id ),
                    'content'   => get_the_content(),
                );
            }
            wp_reset_postdata();
        }

        # reset the global query
        $post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : false;
        if( $post_id ) {
            $global_query = new WP_Query( array( 'p' => $post_id, 'post_type' => 'any' ));
            if ( $global_query->have_posts() ) {
                while ( $global_query->have_posts() ) { $global_query->the_post(); }
                wp_reset_postdata();
            }
        }

        return $output;

    }

    static function get_categories() {

        $layout_categories = get_terms( 'playouts_layout_category', array(
            'hide_empty' => false,
        ));

        $categiries_arr = array();
        foreach( $layout_categories as $category ) {
            $categiries_arr[ $category->term_id ] = $category->name;
        }

        return $categiries_arr;

    }

    static function get_categories_by_id( $layout_id ) {

        $categories = wp_get_post_terms( $layout_id, 'playouts_layout_category' );
        $output = array();
        foreach( $categories as $category ) {
            $output[] = $category->term_id;
        }
        return '%' . implode( '%', $output ) . '%';

    }

    static function get_layout_view( $layout_id ) {

        $view = wp_get_post_terms( $layout_id, 'layout_view' );

        if( isset( $view[0] ) ) {
            return $view[0]->name;
        }

        return '';

    }

    static function extract_first_module( $content, $default = '' ) {

        preg_match( self::$regex_extract_first_module, $content, $match );

        if( isset( $match[1] ) and ! empty( $match[1] ) ) {
            return $match[1];
        }

        return $default;

    }

}
