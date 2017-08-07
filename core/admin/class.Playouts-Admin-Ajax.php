<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } # exit if accessed directly

/*
 * admin ajax callbacks
 *
 *
 */
class Playouts_Admin_Ajax {

	/*
	 * ajax callabcks
	 *
	 */
	static $callbacks = array(
		/*
		 * load and parse the shortcodes from the post editor on post edit
		 *
		 */
		'__parse_shortcode',
		/*
		 * get the options on edit panel settings
		 *
		 */
		'__panel_get_options',
		/*
		 * save custom layouts as post
		 *
		 */
		'__save_layout',
		/*
		 * save layout options
		 *
		 */
		'__save_layout_options',
		/*
		 * save favorites
		 *
		 */
		'__save_favorites',
	);

	/*
	 * loop the callbacks and set the hooks
	 *
	 */
	static function init() {

		foreach( self::$callbacks as $callback ) {

			add_action( 'wp_ajax_nopriv_' . $callback, array( 'Playouts_Admin_Ajax', $callback ) );
			add_action( 'wp_ajax_' . $callback, array( 'Playouts_Admin_Ajax', $callback ) );

		}
	}

	/*
	 * load and parse the shortcodes from the post editor on post edit
	 *
	 */
	static function __parse_shortcode() {

        if( ! isset( $_POST['editor_content'] ) ) { return; }
        $c = stripslashes( $_POST['editor_content'] );

        $output = array();
        $output = Playouts_Shortcode_Parser::parse( $output, $c );

        die( json_encode( $output ) );

	}

	/*
	 * get the options on edit panel settings
	 *
	 */
	static function __panel_get_options() {

		if ( ! wp_verify_nonce( $_POST['security'] , 'playouts-nonce-get-options' ) ) { return; }

		if ( ! current_user_can( 'edit_posts' ) ) { return; }

		if ( ! isset( $_POST['options'] ) or empty( $_POST['options'] ) ) { return; }

		$options = (array) json_decode( stripslashes( $_POST['options'] ) );

		$otypes = Playouts_Option_Type::get_otypes();

		foreach( $options as $name => $values ) {

			$values->name = $name;
		    echo Playouts_Option_Type::get_option_template( $otypes[ $values->type ]->class_name, $values );

		}
		exit;

	}

	/*
	 * save custom layouts as post
	 *
	 */
	static function __save_layout() {

		if ( ! wp_verify_nonce( $_POST['nonce'], 'playouts-nonce-save-layout' ) ) { return; }

		if ( ! current_user_can( 'edit_posts' ) ) { return; }

		if ( empty( $_POST['layout_name'] ) ) { return; }

		//layout_categories
		$layout_categories = array();
		if( is_array( $_POST['layout_categories'] ) ) {
			$layout_categories = array_map( 'intval', $_POST['layout_categories'] );
		}

		$args = array(
			'layout_content' => isset( $_POST['layout_content'] ) ? sanitize_text_field( $_POST['layout_content'] ) : '',
			'layout_name' => isset( $_POST['layout_name'] ) ? sanitize_text_field( $_POST['layout_name'] ) : '',
			'layout_categories' => $layout_categories,
			'layout_new_category' => isset( $_POST['layout_new_category'] ) ? sanitize_text_field( $_POST['layout_new_category'] ) : '',
		);

		$data = array(
			'layout_id' 				=> Playouts_Admin_Ajax::create_layout( $args ),
			'custom_layouts' 			=> Playouts_Admin_Layout_Custom::get_layouts_output(),
			'custom_layout_categories' 	=> Playouts_Admin_Layout_Custom::get_categories(),
		);

		die( json_encode( $data ) );

	}

	static function create_layout( $args ) {

		if ( empty( $args ) ) { return; }

		extract( $args );

		$post_layout = array(
			'post_title'   => sanitize_text_field( $layout_name ),
			'post_content' => $layout_content,
			'post_status'  => 'publish',
			'post_type'    => 'playouts_layout',
		);

		// insert the layout
		$layout_post_id = wp_insert_post( $post_layout );

		// insert new category
		if ( ! empty( $layout_new_category ) ) {
			$new_category = wp_insert_term( $layout_new_category, 'playouts_layout_category' );
			$layout_categories[] = (int) $new_category['term_id'];
		}

		// set layout categories
		if ( ! empty( $layout_categories ) ) {
			wp_set_post_terms( $layout_post_id, $layout_categories, 'playouts_layout_category' );
		}

		return $layout_post_id;

	}

	static function __save_layout_options() {

		if ( ! wp_verify_nonce( $_POST['security'] , 'playouts-nonce-save-layout-options' ) ) { return; }

		if ( ! current_user_can( 'manage_options' ) ) { return; }

		if ( ! isset( $_POST['playouts_options'] ) or empty( $_POST['playouts_options'] ) ) { return; }

		$options_new = array();
		foreach( $_POST['playouts_options'] as $key => $option_value ) {
			if( is_array( $option_value ) ) {
				$options_new[ $key ] = array_map( 'sanitize_text_field', $option_value );
			}else{
				$options_new[ $key ] = sanitize_text_field( $option_value );
			}
		}

		update_option( 'playouts_layouts_options', $options_new );

		wp_send_json_success();

	}

	static function __save_favorites() {

		if ( ! wp_verify_nonce( $_POST['security'] , 'playouts-nonce-save-favorites' ) ) { return; }

		if ( ! current_user_can( 'manage_options' ) ) { return; }

		if ( ! isset( $_POST['favorites'] ) or empty( $_POST['favorites'] ) ) {
			$options = '';
		}else{
			$options = $_POST['favorites'];
			$options = esc_js( json_encode( $options ) );
		}

		update_option( 'playouts_favorites', $options );

		wp_send_json_success();

	}

}

Playouts_Admin_Ajax::init();
