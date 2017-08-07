<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * general plugin functions
 * usable in both admin and public
 *
 */
class Playouts_Functions {

    /*
	 * fix paragraphs
	 *
	 */
    static function autop( $content ) {
        $output = preg_replace( '/^<\/p><p>/', '<p>', $content );
        $output = preg_replace( '/^<\/p>/', '', $output );
        $output = preg_replace( '/<\/p><p>$/', '</p>', $output );
        $output = preg_replace( '/<p>$/', '', $output );
        return $output;
    }

	/*
	 * de-escape quotes from shortcode params
	 *
	 */
    static function quote_decode( $text ) {
        return str_replace( '`', "'", str_replace( '``', '"', $text ) );
    }

	/*
	 * retrieves the attachment id from url
	 *
	 */
    static function get_image_id_from_url( $url ) {

		$attachment_id = 0;
        $file = basename( $url );
        $query = new WP_Query( array(
            'post_type'   => 'attachment',
            'post_status' => 'inherit',
            'fields'      => 'ids',
            'meta_query'  => array(
                array(
                    'value'   => $file,
                    'compare' => 'LIKE',
                    'key'     => '_wp_attachment_metadata',
                ),
            )
        ));
        if ( $query->have_posts() ) {
            foreach ( $query->posts as $post_id ) {
                $meta = wp_get_attachment_metadata( $post_id );
                $original_file = basename( $meta['file'] );
                $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
                if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
                    $attachment_id = $post_id;
                    break;
                }
            }
        }
        return $attachment_id;
    }

    /*
     * get image thumbnail by attachment id
     *
     */
    static function get_size_by_attachment_id( $attachment_id, $size = 'thumbnail' ) {
        $attachment = wp_get_attachment_image_src( $attachment_id, $size );
        return isset( $attachment[0] ) ? $attachment[0] : '';
    }

	/*
	 * encode base64 into parameter friendly base 64 code
	 *
	 */
	static function base64_to_param_encode( $string ) {
		return str_replace( '=', '_', base64_encode( $string ) );
	}

	/*
	 * decode base64 from parameter friendly base 64 code
	 *
	 */
	static function base64_from_param_decode( $string ) {
		return base64_decode( str_replace( '=', '_', $string ) );
	}

    /*
     * set a class for a column
     * depending on the width
     *
     */
    static function set_column_class( $width ) {

        $width = (int) $width;

        switch( true ) {
            case ( $width >= 75 and $width < 100 ) :
                $column_class = 'pl-col-range-75-100'; break;
            case ( $width >= 50 and $width < 75 ) :
                $column_class = 'pl-col-range-50-75'; break;
            case ( $width >= 26 and $width < 50 ) :
                $column_class = 'pl-col-range-25-50'; break;
            case ( $width > 0 and $width < 26 ) :
                $column_class = 'pl-col-range-0-25'; break;
            default:
                $column_class = 'pl-col-range-100';
        }

        return ' ' . $column_class;

    }

    /*
    * set a flex width for a column
    * depending on the margins
     *
     */
    static function set_column_width( $width, $margin_left, $margin_right ) {

        $width = (int) $width;

        $_width = '1';

        if( ! empty( $margin_left ) or ! empty( $margin_right ) ) {
            $_margins = '';
            if( ! empty( $margin_left ) ) {
                $_margins .= ' - ' . $margin_left . ( is_numeric( $margin_left ) ? 'px' : '' );
            }
            if( ! empty( $margin_right ) ) {
                $_margins .= ' - ' . $margin_right . ( is_numeric( $margin_right ) ? 'px' : '' );
            }
            $_width = '1 calc(' . $width . '%' . $_margins . ')';
        }else{
            $_width = '1 ' . $width . '%';
        }

        return $_width;

    }

    /*
     * escape using wp_kses
     *
     */
    static function kses( $text ) {

        return wp_kses( $text, array(
            'a' => array(
                'href' => array(),
                'title' => array(),
                'class' => array(),
                'target' => array()
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
        ));

    }

}
