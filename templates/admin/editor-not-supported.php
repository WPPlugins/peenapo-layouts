<?php $post_type_obj = get_post_type_object( get_post_type() ); ?>
<p class="pl-editor-not-supported"><?php echo sprintf( esc_html__( 'Peenapo Layouts required the WordPress Editor Support, and this post type "%s" doesn\'t support it.', 'peenapo-layouts-txd' ), $post_type_obj->label ); ?></p>
<style type="text/css">
    /* editor not supported */
    #peenapo_layouts_editor_not_supported {background-color:transparent;border:0;box-shadow:none;-webkit-box-shadow:none;}
    #peenapo_layouts_editor_not_supported > .handlediv, #peenapo_layouts_editor_not_supported > .hndle {display:none;}
    #peenapo_layouts_editor_not_supported > .inside {padding:0;margin:0;}

    .pl-editor-not-supported {display:inline-block;padding:9px 15px;margin:30px 0;background-color:#f43e66;color:#fff;border-radius:3px;}
</style>
