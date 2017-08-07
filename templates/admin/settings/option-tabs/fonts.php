<div class="plg-tab-content">
    <div class="pl-panel-form">

        <h3><?php esc_html_e( 'Fonts', 'peenapo-layouts-txd' ); ?></h3>

        <form class="plg-layouts-options pl-panel-content" id="plg-layouts-options-fonts"> 

            <?php

                $layouts_options_arr = require PLAYOUTS_DIR . 'inc/options_fonts.php';
                $layouts_options = apply_filters( 'playouts_layouts_options', $layouts_options_arr );

                $layouts_options_new = array();

                foreach( $layouts_options as $option_name => $attr ) {

                    $otypes = Playouts_Option_Type::get_otypes();
                    $values = get_option('playouts_layouts_options');

                    $attr['name'] = 'playouts_options[' . $option_name . ']';
                    $attr['value'] = isset( $values[ $option_name ] ) ? $values[ $option_name ] : '';

        		    echo Playouts_Option_Type::get_option_template( $otypes[ $attr['type'] ]->class_name, (object) $attr );

                }

            ?>

        </form>

        <div class="plg-panel-footer">

            <a href="#" id="plg-do-layouts-settings-save" class="pl-button-round pl-button-save pl-button-primary"><?php esc_html_e('Save Settings', 'peenapo-layouts-txd'); ?></a>

        </div>

    </div>
</div>
