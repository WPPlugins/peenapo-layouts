<?php

$controls_row = "
<div class='pl-controls'>
    <span class='pl-row-option pl-drag' title='" .  __( 'Move', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-move'></i></span>
    <span class='pl-row-option pl-edit' title='" .  __( 'Edit', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-edit'></i></span>
    <span class='pl-row-option pl-edit-columns' title='" .  __( 'Edit Columns', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-columns'></i></span>
    <span class='pl-row-option pl-cut' title='" .  __( 'Add New Column', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-column-add'></i></span>
    <span class='pl-row-option pl-duplicate' title='" .  __( 'Duplicate', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-duplicate'></i></span>
    <span class='pl-row-option pl-visibility' title='" .  __( 'Visibility', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-lock'></i></span>
    <span class='pl-row-option pl-open-prompt pl-button-save-custom-layout' title='" .  __( 'Save Layout', 'peenapo-layouts-txd' ) . "' data-save-layout='row' data-prompt='save-layout'><i class='pl-icon-import'></i></span>
    <span class='pl-row-option pl-trash' title='" .  __( 'Delete', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-trash'></i></span>
</div>
";

$block_edit_buttons = "
<div class='just-edit'>
    <div class='pl-label pl-no-select'></div>
    <div class='pl-option-holder'>
        <div class='pl-option pl-drag' title='" .  __( 'Move', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-move'></i></div>
        <div class='pl-option pl-edit' title='" .  __( 'Edit', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-edit'></i></div>
        <div class='pl-option pl-duplicate' title='" .  __( 'Duplicate', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-duplicate'></i></div>
        <div class='pl-option pl-open-prompt pl-button-save-custom-layout' title='" .  __( 'Save Layout', 'peenapo-layouts-txd' ) . "' data-save-layout='element' data-prompt='save-layout'><i class='pl-icon-import'></i></div>
        <div class='pl-option pl-trash' title='" .  __( 'Delete', 'peenapo-layouts-txd' ) . "'><i class='pl-icon-trash'></i></div>
    </div>
    <span class='pl-block-plus pl-open-modal' data-view='column_or_column_inner' data-placement='manually_after'><i class='pl-plus'><span></span></i></span>
</div>";

$block_drag = "
<div class='pl-drag-placeholder'>
    <div class='pl-drag-label'></div>
</div>
";
?>

<!-- row -->
<script type="text/template" id="playouts_template-row">
    <div class="pl-block block-row pl-is-empty" data-id="" data-module="">
        <div class="pl-block-container">
            <?php echo $controls_row; ?>
            <div class="pl-content"></div>
        </div>
        <?php echo $block_drag; ?>
        <div class="pl-col-drag-bg"></div>
        <span class="pl-row-plus pl-open-modal" data-view="row" data-placement="after"><i class="pl-plus"><span></span></i></span>
    </div>
</script>

<!-- column -->
<script type="text/template" id="playouts_template-column">
    <div class="pl-block block-column pl-is-empty" data-id="" data-module="" data-col-width="">
        <div class="pl-block-container">
            <span class="pl-col-plus pl-open-modal" data-view="column" data-placement="insert_bottom"><i class="pl-plus"><span></span></i></span>
            <div class="pl-content"></div>
        </div>
        <span class="pl-column-drag"><span class="pl-col-drag-handle"></span></span>
        <div class="pl-column-width">
            <span class="pl-col-width-label"><em>50</em></span>
        </div>
    </div>
</script>

<!-- row inner -->
<script type="text/template" id="playouts_template-row_inner">
    <div class="pl-block block-row block-row-inner pl-is-empty" data-id="" data-module="">
        <div class="pl-block-container">
            <?php echo $controls_row; ?>
            <div class="pl-content"></div>
        </div>
        <?php echo $block_drag; ?>
        <div class="pl-col-drag-bg"></div>
        <span class="pl-row-plus pl-open-modal" data-view="column" data-placement="after"><i class="pl-plus"><span></span></i></span>
    </div>
</script>

<!-- column inner -->
<script type="text/template" id="playouts_template-column_inner">
    <div class="pl-block block-column block-column-inner pl-is-empty" data-id="" data-module="" data-col-width="">
        <div class="pl-block-container">
            <span class="pl-col-plus pl-open-modal" data-view="column_inner" data-placement="insert_bottom"><i class="pl-plus"><span></span></i></span>
            <div class="pl-content"></div>
        </div>
        <span class="pl-column-drag"><span class="pl-col-drag-handle"></span></span>
        <div class="pl-column-width">
            <span class="pl-col-width-label"><em>50</em></span>
        </div>
    </div>
</script>

<!-- block element -->
<script type="text/template" id="playouts_template-element">
    <div class="pl-block pl-block-draggable pl-is-empty" data-id="" data-module="">
        <div class="pl-block-container">
            <?php echo $block_edit_buttons; ?>
        </div>
        <?php echo $block_drag; ?>
    </div>
</script>

<!-- repeater -->
<script type="text/template" id="playouts_template-repeater">
    <div class="pl-block pl-block-draggable pl-block-repeater pl-is-empty" data-id="" data-module="">
        <div class="pl-block-container">
            <?php echo $block_edit_buttons; ?>
            <div class="pl-content"></div>
        </div>
        <?php echo $block_drag; ?>
    </div>
</script>

<!-- repeater item -->
<script type="text/template" id="playouts_template-repeater_item">
    <div class="pl-block pl-block-repeater-item" data-id="" data-module=""></div>
</script>

<!-- panel repeater item -->
<script type="text/html" id="playouts_template-panel_repeater_item">
    <div class="pl-item" data-id="">
        <div class="pl-item-container pl-no-select">
            <div class="pl-repeater-controls">
                <span class="pl-item-drag-handle" title="<?php esc_html_e( 'Move', 'peenapo-layouts-txd' ); ?>"><i class='pl-icon-move'></i></span>
                <span class="pl-item-edit" title="<?php esc_html_e( 'Edit', 'peenapo-layouts-txd' ); ?>"><i class='pl-icon-edit'></i></span>
                <span class="pl-item-duplicate" title="<?php esc_html_e( 'Duplicate', 'peenapo-layouts-txd' ); ?>"><i class='pl-icon-duplicate'></i></span>
                <span class="pl-item-delete" title="<?php esc_html_e( 'Delete', 'peenapo-layouts-txd' ); ?>"><i class='pl-icon-trash'></i></span>
            </div>
        </div>
        <?php echo $block_drag; ?>
    </div>
</script>

<!-- divider -->
<script type="text/template" id="playouts_template-separator">
    <div class="pl-block pl-separator-block" data-id="" data-module="">
        <div class="pl-block-container">
            <?php echo $block_edit_buttons; ?>
        </div>
        <?php echo $block_drag; ?>
    </div>
</script>

<!-- panel column -->
<script type="text/template" id="playouts_template-panel_columns">
    <div class="pl-option-column" data-column-width="" data-id="">
        <div class="pl-option-column-inner">
            <i class='pl-icon-edit'></i>
        </div>
        <span class="pl-option-column-drag"><span class="pl-option-column-dragger"></span></span>
        <span class="pl-column-label"></span>
    </div>
</script>

<!-- save custom layout panel - category item -->
<script type="text/template" id="playouts_template-save_custom_layout_category_item">
    <li>
        <div class="pl-option-checkbox">
            <input type="checkbox" value="" id="" name="playouts_field_layout_category_list">
            <label for="">
                <span></span>
            </label>
        </div>
    </li>
</script>
