<div id="pl-guide" class="pl-guide">

    <?php do_action( 'playouts_get_template_settings_header' ); ?>

    <div class="plg-content">

        <div class="plg-tabs">

            <ol class="plg-tabs-list">
                <?php $class_active = true; ?>
                <?php foreach( Playouts_Admin_Settings::$support_layouts_settings as $support => $attr ): ?>
                    <li <?php echo $class_active ? 'class="plg-active"' : ''; $class_active = false; ?> data-hash="<?php echo $support; ?>">
                        <?php echo $attr['label']; ?>
                    </li>
                <?php endforeach; ?>
            </ol>

            <ul class="plg-tabs-content">
                <?php $class_active = true; ?>
                <?php foreach( Playouts_Admin_Settings::$support_layouts_settings as $support => $attr ): ?>
                    <li <?php echo $class_active ? 'class="plg-active"' : ''; $class_active = false; ?>>
                        <?php do_action( "playouts_support_{$support}" ); ?>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
    </div>
</div>
