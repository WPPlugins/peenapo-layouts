<!-- post status -->
<input type="hidden" id="playouts_status" name="playouts_status" value="<?php echo Playouts_Admin::$status ? '1' : ''; ?>">

<div class="pl-controls-section pl-controls-top">
    <div class="pl-button pl-button-primary pl-open-modal pl-open-modal-top pl-trigger-tooltip"data-tooltip="<?php esc_html_e( 'Add New Module', 'peenapo-layouts-txd' ); ?>" data-view="__solo" data-placement="before"><i class="pl-icon-grid"></i></div>
    <?php if( get_post_type() !== 'playouts_layout' ): ?>
        <div class="pl-button pl-open-custom-css-panel pl-trigger-tooltip" data-tooltip="<?php esc_html_e( 'Add Custom CSS Code', 'peenapo-layouts-txd' ); ?>"><i class="pl-icon-page"></i></div>
    <?php endif; ?>
    <div class="pl-button pl-open-prompt pl-button-save-custom-layout pl-trigger-tooltip" data-tooltip="<?php esc_html_e( 'Save Content as Custom Layout', 'peenapo-layouts-txd' ); ?>" data-save-layout="content" data-prompt="save-layout"><i class="pl-icon-import"></i></div>
    <div class="pl-button pl-empty-content pl-trigger-tooltip" data-tooltip="<?php esc_html_e( 'Empty Content', 'peenapo-layouts-txd' ); ?>"><i class="pl-icon-trash-2"></i></div>
</div>
