<?php

defined( 'ABSPATH' ) || exit;

class BRS_Elementor_Table_Control extends \Elementor\Base_Data_Control {
    public function get_type() { return 'brs_table_structure'; }

    public function get_default_value() {
        return [
            'columns' => [ 'Column 1', 'Column 2' ],
            'rows' => [
                [ 'Item 1', 'Item 2' ],
                [ 'Item 1', 'Item 2' ],
            ],
        ];
    }

    public function content_template() {
        ?>
        <div class="elementor-control-field">
            <div class="brs-table-editor-root"></div>
            <input type="hidden" data-setting="{{ data.name }}" />
        </div>
        <?php
    }
}
