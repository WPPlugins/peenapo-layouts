<div class="plg-tab-content">
    <div class="pl-panel-form">

        <h3><?php esc_html_e( 'General', 'peenapo-layouts-txd' ); ?></h3>

        <form class="plg-layouts-options pl-panel-content" id="plg-layouts-options-general">

            <?php

                $layouts_options_arr = require PLAYOUTS_DIR . 'inc/options_general.php';
                $layouts_options = apply_filters( 'playouts_layouts_options', $layouts_options_arr );

                $layouts_options_new = array();

                foreach( $layouts_options as $option_name => $attr ) {

                    Playouts_Option_Type::render_option( $option_name, $attr );

                }

            ?>

        </form>

        <div class="plg-panel-footer">

            <a href="#" id="plg-do-layouts-settings-save" class="pl-button-round pl-button-save pl-button-primary"><?php esc_html_e('Save Settings', 'peenapo-layouts-txd'); ?></a>

        </div>

    </div>
</div>
