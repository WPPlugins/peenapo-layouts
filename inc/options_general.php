<?php

$playouts_options = array(

    'google_map_api_key' => array(
        'type'              => 'textfield',
        'label'             => esc_html__( 'Google Map Api Key', 'peenapo-layouts-txd' ),
        'description'       => sprintf( esc_html__( 'Add Google Map Api Key to display your maps correctly. You can get the key from %s.', 'peenapo-layouts-txd' ), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" rel="nofollow">' . esc_attr__( 'here', 'peenapo-layouts-txd' ) . '</a>' ),
    )

    ,'show_editor' => array(
        'type'              => 'true_false',
        'label'             => esc_html__( 'Show Content Editor', 'peenapo-layouts-txd' ),
        'description'       => esc_html__( 'Show the WordPress Content Editor while Peenapo Layouts is active', 'peenapo-layouts-txd' ),
    )

    ,'container_width' => array(
        'label'             => esc_html__( 'Container Width', 'peenapo-layouts-txd' ),
        'description'       => esc_html__( 'The maximum width of the row container.', 'peenapo-layouts-txd' ),
        'type'              => 'number_slider',
        'append_after'      => 'pixels.',
        'min'               => 500,
        'max'               => 1600,
        'step'              => 1,
        'value'             => 1100,
    )

    ,'post_types_heading' => array(
        'type'              => 'heading',
        'label'             => esc_html__( 'Select Post Types', 'peenapo-layouts-txd' ),
        'description'       => esc_html__( 'Select the post types where to render the plugin', 'peenapo-layouts-txd' ),
    )

);

foreach( get_post_types( array( 'public'   => true ), 'objects' ) as $post_type ) {

    if( $post_type->name == 'attachment' ) { continue; }

    $playouts_options['post_types'][ $post_type->name ] = array(
        'type'              => 'true_false',
        'label'             => $post_type->label,
    );

}

return $playouts_options;
