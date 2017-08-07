<?php if( get_post_type() !== 'playouts_layout' ): ?>
    <div id="pl-switch-button" class="<?php echo join( ' ', apply_filters( 'playouts_switch_class', array( 'pl-switch-button' ) ) ); ?>">
        <span class="pl-switch-mode pl-switch-mode-pb"><?php _e( 'Disable Peenapo Layouts', 'peenapo-layouts-txd' ); ?></span>
        <span class="pl-switch-mode pl-switch-mode-classic"><?php _e( 'Enable Peenapo Layouts', 'peenapo-layouts-txd' ); ?></span>
    </div>
<?php endif; ?>
