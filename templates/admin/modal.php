<div id="pl-modal" class="pl-modal pl-modal-has-favorites">

    <div class="pl-modal-header">

        <div class="pl-table">
            <div class="pl-cell">
                <h4><?php _e( 'Add Modules', 'peenapo-layouts-txd' ); ?></h4>
            </div>
            <div class="pl-cell">
                <?php do_action( 'playouts_modal_tabs' ); ?>
            </div>
            <div class="pl-cell pl-align-right">
                <span class="pl-button-close"><em><?php _e( 'Close', 'peenapo-layouts-txd' ); ?></em><i class="pl-plus pl-close"><span></span></i></span>
            </div>
        </div>

    </div>

    <div class="pl-modal-content">

        <?php do_action( 'playouts_modal_tabs_content' ); ?>

    </div>

    <div class="pl-modal-favorites">

        <?php do_action( 'playouts_get_template_modal_favorites' ); ?>

    </div>

</div>
