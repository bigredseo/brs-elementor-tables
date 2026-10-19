<?php

defined( 'ABSPATH' ) || exit;

class BRS_Elementor_Table_Widget extends \Elementor\Widget_Base {
    public function get_name(): string { return 'brs_table'; }
    public function get_title(): string { return __( 'BRS Table', 'brs-elementor-tables' ); }
    public function get_icon(): string { return 'eicon-table'; }
    public function get_categories(): array { return [ 'general' ]; }
    public function get_style_depends(): array { return [ 'brs-elementor-tables' ]; }

    protected function register_controls(): void {
        $c = \Elementor\Controls_Manager::class;
        $this->start_controls_section( 'table_setup', [ 'label' => 'Table Setup', 'tab' => $c::TAB_CONTENT ] );
        $this->add_control( 'column_count', [
            'label' => 'Number of Columns', 'type' => $c::SELECT,
            'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' ], 'default' => '3',
            'description' => 'Choose 1–6 columns. Unused cell values are retained if you reduce the number.',
        ] );
        $this->add_control( 'caption', [ 'label' => 'Table Caption (optional)', 'type' => $c::TEXT, 'label_block' => true ] );
        $this->add_control( 'first_col_header', [ 'label' => 'First column is a row heading', 'type' => $c::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'mobile', [ 'label' => 'Small Screens', 'type' => $c::SELECT, 'options' => [ 'scroll' => 'Horizontal scroll', 'wrap' => 'Wrap content' ], 'default' => 'scroll' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'header_section', [ 'label' => 'Header Row', 'tab' => $c::TAB_CONTENT ] );
        for ( $i = 1; $i <= 6; $i++ ) {
            $this->add_control( 'header_' . $i, [
                'label' => 'Column ' . $i, 'type' => $c::TEXT, 'label_block' => true,
                'default' => 'Column ' . $i,
                'condition' => [ 'column_count' => array_map( 'strval', range( $i, 6 ) ) ],
            ] );
        }
        $this->end_controls_section();

        $row = new \Elementor\Repeater();
        $row->add_control( 'row_label', [
            'label' => 'Row Name (editor only)', 'type' => $c::TEXT,
            'default' => 'Row', 'label_block' => true,
        ] );
        for ( $i = 1; $i <= 6; $i++ ) {
            $row->add_control( 'cell_' . $i, [
                'label' => 'Item ' . $i, 'type' => $c::TEXT, 'label_block' => true,
                'default' => '',
            ] );
        }
        $this->start_controls_section( 'body_section', [ 'label' => 'Table Rows', 'tab' => $c::TAB_CONTENT ] );
        $this->add_control( 'body_rows', [
            'label' => 'Rows', 'type' => $c::REPEATER,
            'fields' => $row->get_controls(),
            'title_field' => '{{{ row_label }}}',
            'button_text' => 'Add Row',
            'prevent_empty' => false,
            'default' => [
                [ 'row_label' => 'Row 1', 'cell_1' => '', 'cell_2' => '', 'cell_3' => '' ],
                [ 'row_label' => 'Row 2', 'cell_1' => '', 'cell_2' => '', 'cell_3' => '' ],
            ],
            'description' => 'Each collapsed item represents a table row. Drag to reorder, duplicate, or delete rows.',
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'layout_style', [ 'label' => 'Layout & Columns', 'tab' => $c::TAB_STYLE ] );
        $this->add_control( 'width_help', [ 'type' => $c::RAW_HTML, 'raw' => 'Set column widths below. Leave all values at 0 for automatic sizing. When specifying widths, use percentages totaling 100.', 'content_classes' => 'elementor-descriptor' ] );
        for ( $i = 1; $i <= 6; $i++ ) {
            $this->add_control( 'width_' . $i, [
                'label' => 'Column ' . $i . ' Width (%)', 'type' => $c::NUMBER,
                'min' => 0, 'max' => 100, 'step' => 0.5, 'default' => 0,
                'condition' => [ 'column_count' => array_map( 'strval', range( $i, 6 ) ) ],
            ] );
        }
        $this->end_controls_section();

        $this->start_controls_section( 'appearance', [ 'label' => 'Table Appearance', 'tab' => $c::TAB_STYLE ] );
        $this->add_control( 'header_background', [ 'label' => 'Header Background', 'type' => $c::COLOR, 'default' => '#f2f4f7', 'selectors' => [ '{{WRAPPER}} .brs-table thead th' => 'background-color: {{VALUE}};' ] ] );
        $this->add_control( 'header_color', [ 'label' => 'Header Text Color', 'type' => $c::COLOR, 'selectors' => [ '{{WRAPPER}} .brs-table thead th' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'cell_color', [ 'label' => 'Body Text Color', 'type' => $c::COLOR, 'selectors' => [ '{{WRAPPER}} .brs-table tbody' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'stripe_color', [ 'label' => 'Alternating Row Color', 'type' => $c::COLOR, 'default' => '#fafafa', 'selectors' => [ '{{WRAPPER}} .brs-table tbody tr:nth-child(even)' => 'background-color: {{VALUE}};' ] ] );
        $this->add_control( 'border_color', [ 'label' => 'Border Color', 'type' => $c::COLOR, 'default' => '#dddddd', 'selectors' => [ '{{WRAPPER}} .brs-table th, {{WRAPPER}} .brs-table td' => 'border-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'padding', [ 'label' => 'Cell Padding', 'type' => $c::DIMENSIONS, 'size_units' => [ 'px', 'em', 'rem' ], 'selectors' => [ '{{WRAPPER}} .brs-table th, {{WRAPPER}} .brs-table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_control( 'text_align', [ 'label' => 'Cell Alignment', 'type' => $c::CHOOSE, 'options' => [ 'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ], 'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ], 'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ] ], 'default' => 'left', 'selectors' => [ '{{WRAPPER}} .brs-table th, {{WRAPPER}} .brs-table td' => 'text-align: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'body_typography', 'label' => 'Body Typography', 'selector' => '{{WRAPPER}} .brs-table tbody' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'header_typography', 'label' => 'Header Typography', 'selector' => '{{WRAPPER}} .brs-table thead' ] );
        $this->end_controls_section();
    }

    private function widths( array $settings, int $count ): array {
        $widths = [];
        for ( $i = 1; $i <= $count; $i++ ) {
            $value = (float) ( $settings[ 'width_' . $i ] ?? 0 );
            if ( ! is_finite( $value ) || $value <= 0 || $value > 100 ) { return []; }
            $widths[] = $value;
        }
        return abs( array_sum( $widths ) - 100 ) < 0.05 ? $widths : [];
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();
        $count = max( 1, min( 6, (int) ( $settings['column_count'] ?? 3 ) ) );
        $widths = $this->widths( $settings, $count );
        $mode = ( $settings['mobile'] ?? 'scroll' ) === 'wrap' ? 'wrap' : 'scroll';
        $caption = (string) ( $settings['caption'] ?? '' );
        echo '<div class="brs-table-container brs-table-' . esc_attr( $mode ) . '" role="region" aria-label="' . esc_attr( $caption ?: 'Data table' ) . '" tabindex="0">';
        echo '<table class="brs-table' . ( $widths ? ' brs-table-fixed-columns' : '' ) . '">';
        if ( $widths ) {
            echo '<colgroup>';
            foreach ( $widths as $width ) { echo '<col style="width:' . esc_attr( (string) $width ) . '%">'; }
            echo '</colgroup>';
        }
        if ( $caption !== '' ) { echo '<caption>' . esc_html( $caption ) . '</caption>'; }
        echo '<thead><tr>';
        for ( $i = 1; $i <= $count; $i++ ) { echo '<th scope="col">' . esc_html( (string) ( $settings[ 'header_' . $i ] ?? '' ) ) . '</th>'; }
        echo '</tr></thead><tbody>';
        foreach ( array_slice( (array) ( $settings['body_rows'] ?? [] ), 0, 250 ) as $row ) {
            if ( ! is_array( $row ) ) { continue; }
            echo '<tr>';
            for ( $i = 1; $i <= $count; $i++ ) {
                $is_row_header = $i === 1 && ( $settings['first_col_header'] ?? '' ) === 'yes';
                echo $is_row_header ? '<th scope="row">' : '<td>';
                echo esc_html( (string) ( $row[ 'cell_' . $i ] ?? '' ) );
                echo $is_row_header ? '</th>' : '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }
}
