<div id="pl-favorites" class="pl-favorites">
    <div class="pl-favorites-header">
        <h4><?php _e( 'My Favorites', 'peenapo-layouts-txd' ); ?></h4>
        <p><?php _e( 'Add your favorite modules here for fast access', 'peenapo-layouts-txd' ); ?></p>
        <a href="#" class="pl-button-add"
            data-label-manage="<?php _e( 'Manage', 'peenapo-layouts-txd' ); ?>"
            data-label-save="<?php _e( 'Save', 'peenapo-layouts-txd' ); ?>">
                <?php _e( 'Manage', 'peenapo-layouts-txd' ); ?>
        </a>
    </div>
    <?php $list_html = Playouts_Admin_Modal::get_favorites_list(); ?>
    <ul class="pl-favorite-list pl-no-select<?php if( empty( $list_html ) ) { echo ' pl-empty'; } ?>"><?php echo $list_html; ?></ul>
</div>
