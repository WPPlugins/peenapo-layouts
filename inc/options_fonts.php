<?php
// TODO: use one file for all the options
return array(

    'font_container' => array(
        'label'             => esc_html__( 'Container Font', 'peenapo-layouts-txd' ),
        'type'              => 'google_font',
        'description'       => esc_html__( 'Select the main font of the container. The rest of elements will inherit it.', 'peenapo-layouts-txd' ),
        'value'             => '',
        'preview'           => esc_html__( 'It was going to be a lonely trip back. Almost before we knew it, we had left the ground.', 'peenapo-layouts-txd' ),
        'font_size'         => '15px'
    ),

    'font_headings' => array(
        'label'             => esc_html__( 'Headings Font', 'peenapo-layouts-txd' ),
        'type'              => 'google_font',
        'description'       => esc_html__( 'Select the font for all the titles.', 'peenapo-layouts-txd' ),
        'value'             => '',
        'preview'           => esc_html__( 'I watched the storm, so beautiful yet terrific.', 'peenapo-layouts-txd' )
    ),

);
