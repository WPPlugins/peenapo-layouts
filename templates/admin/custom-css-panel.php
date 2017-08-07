<!-- TODO: move this on the bottom of the layout -->
<!-- custom css -->
<div id="pl-custom-css" class="pl-panel pl-panel-form">
    <div class="pl-panel-header">
        <h4 class="pl-panel-title"><?php _e( 'Custom CSS', 'peenapo-layouts-txd' ); ?></h4>
        <span class="pl-button-close"><em><?php _e( 'Close', 'peenapo-layouts-txd' ); ?></em><i class="pl-plus pl-close"><span></span></i></span>
    </div>
    <div class="pl-panel-tabs pl-no-select"></div>
    <div class="pl-panel-content">
        <div class="pl-panel-row pl-row-option-textarea">
            <div class="pl-panel-row-inner">
                <p><?php _e( 'Add additional CSS code, for the current post.', 'peenapo-layouts-txd' ); ?></p>
                <textarea class="pl-custom-css-textarea" name="bw_custom_css"><?php echo strip_tags( Playouts_Admin::get_custom_css() ); ?></textarea>
            </div>
        </div>
    </div>
    <div class="pl-panel-footer">
        <span class="pl-button-round pl-button-close"><?php _e( 'Close', 'peenapo-layouts-txd' ); ?></span>
        <span class="pl-button-round pl-button-save pl-button-primary"><?php _e( 'Save Custom CSS', 'peenapo-layouts-txd' ); ?></span>
    </div>
</div> <!-- end custom css -->
