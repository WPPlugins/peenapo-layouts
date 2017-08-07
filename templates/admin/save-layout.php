<div class="pl-prompt pl-prompt-save-layout pl-panel pl-panel-size-small pl-panel-form">
    <div class="pl-panel-header">
        <h4 class="pl-panel-title"><?php _e( 'Save Layout', 'peenapo-layouts-txd' ); ?></h4>
        <span class="pl-button-close pl-prompt-close pl-prompt-key-escape"><em><?php _e( 'Close', 'peenapo-layouts-txd' ); ?></em><i class="pl-plus pl-close"><span></span></i></span>
    </div>
    <div class="pl-panel-tabs">
        <p><?php esc_html_e( 'Give it some name and save this layout so you can use it later in your project', 'peenapo-layouts-txd' ); ?></p>
    </div>
    <div class="pl-panel-content">
        <form>
            <div class="pl-panel-row">
                <div class="pl-panel-row-inner">
                    <h5><?php esc_html_e( 'Layout Name', 'peenapo-layouts-txd' ); ?></h5>
                    <input type="text" id="pl-field-layout-name" name="playouts_field_layout_name">
                </div>
            </div>
            <div class="pl-panel-row">
                <div class="pl-panel-row-inner">
                    <h5>
                        <?php esc_html_e( 'Select Category', 'peenapo-layouts-txd' ); ?>
                        <i class="pl-icon-info pl-no-select"></i>
                    </h5>
                    <div class="pl-header-info">
                        <p><?php esc_html_e( 'Select a category ( optional ) for this template so you can find it easier.', 'peenapo-layouts-txd' ); ?></p>
                    </div>
                    <ul class="bw-save-layout-cats"></ul>
                    <input type="text" id="pl-field-layout-category" name="playouts_field_layout_category" placeholder="<?php esc_html_e( 'Add new category', 'peenapo-layouts-txd' ); ?>">
                </div>
            </div>
        </form>
    </div>
    <div class="pl-panel-footer">
        <span class="pl-button-round pl-button-close pl-prompt-close"><?php _e( 'Close', 'peenapo-layouts-txd' ); ?></span>
        <span class="pl-button-round pl-button-primary pl-prompt-button-save-layout pl-prompt-key-enter"><?php _e( 'Save Layout', 'peenapo-layouts-txd' ); ?></span>
    </div>
</div>
