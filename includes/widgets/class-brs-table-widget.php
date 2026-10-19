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
        $this->start_controls_section( 'table_content', [ 'label' => 'Table Content', 'tab' => $c::TAB_CONTENT ] );
        $this->add_control( 'structure', [
            'label' => 'Header and Rows',
            'type' => 'brs_table_structure',
            'default' => [
                'columns' => [ 'Column 1', 'Column 2' ],
                'rows' => [ [ 'Item 1', 'Item 2' ], [ 'Item 1', 'Item 2' ] ],
            ],
        ] );
        $this->add_control( 'caption', [ 'label' => 'Table Caption', 'type' => $c::TEXT, 'label_block' => true ] );
        $this->add_control( 'first_col_header', [ 'label' => 'First column is row heading', 'type' => $c::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'columns_style', [ 'label' => 'Column Widths', 'tab' => $c::TAB_STYLE ] );
        $this->add_control( 'column_help', [ 'type' => $c::RAW_HTML, 'raw' => 'Use the device icon to set different widths for desktop, tablet and mobile. Leave widths empty for automatic sizing. For example, enter 25 and 75 for two columns.', 'content_classes' => 'elementor-descriptor' ] );
        for ( $i = 1; $i <= 12; $i++ ) {
            $this->add_responsive_control( 'col_width_' . $i, [
                'label' => 'Column ' . $i . ' (%)', 'type' => $c::NUMBER,
                'min' => 1, 'max' => 100, 'step' => 0.5,
                'selectors' => [ '{{WRAPPER}} .brs-table col:nth-child(' . $i . ')' => 'width: {{VALUE}}%;' ],
            ] );
        }
        $this->end_controls_section();

        $this->start_controls_section( 'responsive_style', [ 'label' => 'Responsive Layout', 'tab' => $c::TAB_STYLE ] );
        $this->add_control( 'tablet_layout', [ 'label' => 'Tablet Display', 'type' => $c::SELECT, 'options' => [ 'scroll' => 'Table with horizontal scrolling', 'stack' => 'Stack cells vertically' ], 'default' => 'scroll' ] );
        $this->add_control( 'mobile_layout', [ 'label' => 'Mobile Display', 'type' => $c::SELECT, 'options' => [ 'scroll' => 'Table with horizontal scrolling', 'stack' => 'Stack cells vertically' ], 'default' => 'stack' ] );
        $this->add_responsive_control( 'cell_padding', [
            'label' => 'Cell Padding', 'type' => $c::DIMENSIONS, 'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [ '{{WRAPPER}} .brs-table th, {{WRAPPER}} .brs-table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'appearance', [ 'label' => 'Appearance', 'tab' => $c::TAB_STYLE ] );
        $this->add_control( 'header_background', [ 'label' => 'Header Background', 'type' => $c::COLOR, 'default' => '#f2f4f7', 'selectors' => [ '{{WRAPPER}} .brs-table thead th' => 'background-color: {{VALUE}};' ] ] );
        $this->add_control( 'header_color', [ 'label' => 'Header Text', 'type' => $c::COLOR, 'selectors' => [ '{{WRAPPER}} .brs-table thead th' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'cell_color', [ 'label' => 'Body Text', 'type' => $c::COLOR, 'selectors' => [ '{{WRAPPER}} .brs-table tbody' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'stripe_color', [ 'label' => 'Alternating Row Color', 'type' => $c::COLOR, 'default' => '#fafafa', 'selectors' => [ '{{WRAPPER}} .brs-table tbody tr:nth-child(even)' => 'background-color: {{VALUE}};' ] ] );
        $this->add_control( 'border_color', [ 'label' => 'Border Color', 'type' => $c::COLOR, 'default' => '#dddddd', 'selectors' => [ '{{WRAPPER}} .brs-table th, {{WRAPPER}} .brs-table td' => 'border-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'text_align', [ 'label' => 'Alignment', 'type' => $c::CHOOSE, 'options' => [ 'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ], 'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ], 'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ] ], 'selectors' => [ '{{WRAPPER}} .brs-table th, {{WRAPPER}} .brs-table td' => 'text-align: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'body_typography', 'label' => 'Body Typography', 'selector' => '{{WRAPPER}} .brs-table tbody' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'header_typography', 'label' => 'Header Typography', 'selector' => '{{WRAPPER}} .brs-table thead' ] );
        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();
        $structure = isset( $settings['structure'] ) && is_array( $settings['structure'] ) ? $settings['structure'] : [];
        $columns = isset( $structure['columns'] ) && is_array( $structure['columns'] ) ? array_slice( $structure['columns'], 0, 12 ) : [ 'Column 1', 'Column 2' ];
        if ( ! $columns ) { $columns = [ 'Column 1' ]; }
        $count = count( $columns );
        $rows = isset( $structure['rows'] ) && is_array( $structure['rows'] ) ? array_slice( $structure['rows'], 0, 250 ) : [];
        $caption = (string) ( $settings['caption'] ?? '' );
        $tablet = ( $settings['tablet_layout'] ?? 'scroll' ) === 'stack' ? 'stack' : 'scroll';
        $mobile = ( $settings['mobile_layout'] ?? 'stack' ) === 'stack' ? 'stack' : 'scroll';
        $css_id = 'brs-table-' . sanitize_html_class( $this->get_id() );
        echo '<div id="' . esc_attr( $css_id ) . '" class="brs-table-container brs-table-tablet-' . esc_attr( $tablet ) . ' brs-table-mobile-' . esc_attr( $mobile ) . '" role="region" aria-label="' . esc_attr( $caption ?: 'Data table' ) . '" tabindex="0">';
        echo '<table class="brs-table"><colgroup>';
        for ( $i = 0; $i < $count; $i++ ) { echo '<col>'; }
        echo '</colgroup>';
        if ( $caption !== '' ) { echo '<caption>' . esc_html( $caption ) . '</caption>'; }
        echo '<thead><tr>';
        foreach ( $columns as $column ) { echo '<th scope="col">' . esc_html( is_scalar( $column ) ? (string) $column : '' ) . '</th>'; }
        echo '</tr></thead><tbody>';
        foreach ( $rows as $row ) {
            if ( ! is_array( $row ) ) { continue; }
            echo '<tr>';
            for ( $i = 0; $i < $count; $i++ ) {
                $is_header = $i === 0 && ( $settings['first_col_header'] ?? '' ) === 'yes';
                $value = isset( $row[$i] ) && is_scalar( $row[$i] ) ? (string) $row[$i] : '';
                echo $is_header ? '<th scope="row" data-label="' . esc_attr( (string) $columns[$i] ) . '">' : '<td data-label="' . esc_attr( (string) $columns[$i] ) . '">';
                echo esc_html( $value );
                echo $is_header ? '</th>' : '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }
}
