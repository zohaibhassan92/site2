<?php
/**
 * Accordion
 *
 * @package Happy_Addons_Pro
 */
namespace Happy_Addons_Pro\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Happy_Addons\Elementor\Controls\Group_Control_Foreground;
use Happy_Addons_Pro\Helpers;

defined( 'ABSPATH' ) || die();

class Accordion extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Advanced Accordion', 'happy-addons-pro' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'hm hm-accordion-vertical';
    }

    public function get_keywords() {
        return [ 'accordion', 'toggle', 'collapsible', 'tabs', 'switch' ];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_accordion',
            [
                'label' => __( 'Accordion', 'happy-addons-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'type' => Controls_Manager::TEXT,
                'label' => __( 'Title', 'happy-addons-pro' ),
                'default' => __( 'Accordion Title', 'happy-addons-pro' ),
                'placeholder' => __( 'Type Accordion Title', 'happy-addons-pro' ),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'type' => Controls_Manager::ICONS,
                'label' => __( 'Icon', 'happy-addons-pro' ),
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'source',
            [
                'type' => Controls_Manager::SELECT,
                'label' => __( 'Content Source', 'happy-addons-pro' ),
                'default' => 'editor',
                'separator' => 'before',
                'options' => [
                    'editor' => __( 'Editor', 'happy-addons-pro' ),
                    'template' => __( 'Template', 'happy-addons-pro' ),
                ]
            ]
        );

        $repeater->add_control(
            'editor',
            [
                'label' => __( 'Content Editor', 'happy-addons-pro' ),
                'show_label' => false,
                'type' => Controls_Manager::WYSIWYG,
                'condition' => [
                    'source' => 'editor',
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'template',
            [
                'label' => __( 'Section Template', 'happy-addons-pro' ),
                'placeholder' => __( 'Select a section template for as tab content', 'happy-addons-pro' ),
                'description' => sprintf( __( 'Wondering what is section template or need to create one? Please click %1$shere%2$s ', 'happy-addons-pro' ),
                    '<a target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=section' ) ) . '">',
                    '</a>'
				),
				'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'options' => hapro_get_section_templates(),
                'condition' => [
                    'source' => 'template',
                ]
            ]
        );

        $this->add_control(
            'tabs',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{title}}',
                'default' => [
                    [
                        'title' => 'Accordion Item 1',
                        'source' => 'editor',
                        'editor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore <br><br>et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                    ],
                    [
                        'title' => 'Accordion Item 2',
                        'source' => 'editor',
                        'editor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore <br><br>et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                    ]
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_options',
            [
                'label' => __( 'Options', 'happy-addons-pro' ),
            ]
        );

        $this->add_control(
            'closed_icon',
            [
                'type' => Controls_Manager::ICONS,
                'label' => __( 'Closed Icon', 'happy-addons-pro' ),
                'default' => [
                    'library' => 'solid',
                    'value' => 'fas fa-plus'
                ],
            ]
        );

        $this->add_control(
            'opened_icon',
            [
                'type' => Controls_Manager::ICONS,
                'label' => __( 'Opened Icon', 'happy-addons-pro' ),
                'default' => [
                    'library' => 'solid',
                    'value' => 'fas fa-minus'
                ],
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'type' => Controls_Manager::CHOOSE,
                'label' => __( 'Position', 'happy-addons-pro' ),
                'default' => 'left',
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' =>  __( 'Left', 'happy-addons-pro' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' =>  __( 'Right', 'happy-addons-pro' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'ha-accordion--icon-',
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_item',
            [
                'label' => __( 'Item', 'happy-addons-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_spacing',
            [
                'label' => __( 'Vertical Spacing (px)', 'happy-addons-pro' ),
                'type' => Controls_Manager::NUMBER,
                'step' => 'any',
                'default' => -1,
                'selectors' => [
                    '{{WRAPPER}} .ha-accordion__item:not(:first-child)' => 'margin-top: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => __( 'Box Border', 'happy-addons-pro' ),
                'selector' => '{{WRAPPER}} .ha-accordion__item',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-addons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-accordion__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'label' => __( 'Box Shadow', 'happy-addons-pro' ),
                'selector' => '{{WRAPPER}} .ha-accordion__item',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_title',
            [
                'label' => __( 'Title', 'happy-addons-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => __( 'Padding', 'happy-addons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-accordion__item-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .ha-accordion__item-title',
                'scheme' => Typography::TYPOGRAPHY_2,
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'label' => __( 'Text Shadow', 'happy-addons-pro' ),
                'selector' => '{{WRAPPER}} .ha-accordion__item-title',
            ]
        );

        $this->start_controls_tabs( '_tab_tab_status' );
        $this->start_controls_tab(
            '_tab_tab_normal',
            [
                'label' => __( 'Normal', 'happy-addons-pro' ),
            ]
        );

        $this->add_control(
            'title_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-addons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-accordion__item-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Foreground::get_type(),
            [
                'name' => 'title_text_gradient',
                'selector' => '{{WRAPPER}} .ha-accordion__item-title-text, {{WRAPPER}} .ha-accordion__item-title-icon i:before, {{WRAPPER}} .ha-accordion__item-title-icon svg, {{WRAPPER}} .ha-accordion__icon i:before, {{WRAPPER}} .ha-accordion__icon svg',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title_bg',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .ha-accordion__item-title',
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            '_tab_tab_active',
            [
                'label' => __( 'Active', 'happy-addons-pro' ),
            ]
        );

        $this->add_control(
            'title_active_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-addons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-accordion__item-title.ha-accordion__item--active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Foreground::get_type(),
            [
                'name' => 'title_active_text_gradient',
                'selector' => '{{WRAPPER}} .ha-accordion__item-title.ha-accordion__item--active .ha-accordion__item-title-text, {{WRAPPER}} .ha-accordion__item-title.ha-accordion__item--active .ha-accordion__item-title-icon i:before, {{WRAPPER}} .ha-accordion__item-title.ha-accordion__item--active .ha-accordion__item-title-icon svg, {{WRAPPER}} .ha-accordion__item-title.ha-accordion__item--active .ha-accordion__icon i:before, {{WRAPPER}} .ha-accordion__item-title.ha-accordion__item--active .ha-accordion__icon svg',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title_active_bg',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .ha-accordion__item-title.ha-accordion__item--active',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        /**
         * End title
         */

        $this->start_controls_section(
            '_section_title_icon',
            [
                'label' => __( 'Title Icon', 'happy-addons-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_icon_spacing',
            [
                'label' => __( 'Spacing', 'happy-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-accordion__item-title-icon' => 'margin-right: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Title icon end
         */

        $this->start_controls_section(
            '_section_content',
            [
                'label' => __( 'Content', 'happy-addons-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'happy-addons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-accordion__item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'label' => __( 'Border', 'happy-addons-pro' ),
                'selector' => '{{WRAPPER}} .ha-accordion__item-content',
            ]
        );

        $this->add_control(
            'content_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-addons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-accordion__item-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .ha-accordion__item-content',
                'scheme' => Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-accordion__item-content' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_bg',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ha-accordion__item-content',
            ]
        );

        $this->end_controls_section();
        /**
         * End content
         */

        $this->start_controls_section(
            '_section_icon',
            [
                'label' => __( 'Open / Close Icon', 'happy-addons-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_icon_spacing',
            [
                'label' => __( 'Spacing', 'happy-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}}.ha-accordion--icon-left .ha-accordion__icon > span' => 'margin-right: {{SIZE}}px;',
                    '{{WRAPPER}}.ha-accordion--icon-right .ha-accordion__icon > span' => 'margin-left: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * End open close icon
         */
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! is_array( $settings['tabs'] ) || empty( $settings['tabs'] ) ) {
            return;
        }

        $has_closed_icon = ( ! empty( $settings['closed_icon'] ) && ! empty( $settings['closed_icon']['value'] ) );
        $has_opened_icon = ( ! empty( $settings['opened_icon'] ) && ! empty( $settings['opened_icon']['value'] ) );

        $id_int = substr( $this->get_id_int(), 0, 3 );
        ?>
        <div class="ha-accordion__wrapper" role="tablist">
            <?php foreach ( $settings['tabs'] as $index => $item ) :
                $count = $index + 1;

                $title_setting_key = $this->get_repeater_setting_key( 'title', 'tabs', $index );
                $has_title_icon = ( ! empty( $item['icon'] ) && ! empty( $item['icon']['value'] ) );

                if ( $item['source'] === 'editor' ) {
                    $content_setting_key = $this->get_repeater_setting_key( 'editor', 'tabs', $index );
                    $this->add_inline_editing_attributes( $content_setting_key, 'advanced' );
                } else {
                    $content_setting_key = $this->get_repeater_setting_key( 'section', 'tabs', $index );
                }

                $this->add_render_attribute( $title_setting_key, [
                    'id' => 'ha-accordion__item-title-' . $id_int . $count,
                    'class' => [ 'ha-accordion__item-title' ],
                    'data-tab' => $count,
                    'role' => 'tab',
                    'aria-controls' => 'ha-accordion__item-content-' . $id_int . $count,
                ] );

                $this->add_render_attribute( $content_setting_key, [
                    'id' => 'ha-accordion__item-content-' . $id_int . $count,
                    'class' => [ 'ha-accordion__item-content' ],
                    'data-tab' => $count,
                    'role' => 'tabpanel',
                    'aria-labelledby' => 'ha-accordion__item-title-' . $id_int . $count,
                ] );

                ?>
                <div class="ha-accordion__item">
                    <div <?php echo $this->get_render_attribute_string( $title_setting_key ); ?>>
                        <?php if ( $has_opened_icon || $has_closed_icon ) : ?>
                            <span class="ha-accordion__item-icon ha-accordion__icon" aria-hidden="true">
                                <?php if ( $has_opened_icon ) : ?>
                                    <span class="ha-accordion__icon--closed"><?php ha_render_icon( $settings, false, 'closed_icon' ); ?></span>
                                <?php endif; ?>
                                <?php if ( $has_opened_icon ) : ?>
                                    <span class="ha-accordion__icon--opened"><?php ha_render_icon( $settings, false, 'opened_icon' ); ?></span>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                        <div class="ha-accordion__item-title-inner">
                            <?php if ( $has_title_icon ) : ?>
                                <span class="ha-accordion__item-title-icon"><?php ha_render_icon( $item, false, 'icon' ); ?></span>
                            <?php endif; ?>
                            <span class="ha-accordion__item-title-text"><?php echo ha_kses_basic( $item['title'] ); ?></span>
                        </div>
                    </div>
                    <div <?php echo $this->get_render_attribute_string( $content_setting_key ); ?>>
                        <?php
                        if ( $item['source'] === 'editor' ) :
                            echo $this->parse_text_editor( $item['editor'] );
                        elseif ( $item['source'] === 'template' && $item['template'] ) :
                            echo ha_elementor()->frontend->get_builder_content_for_display( $item['template'] );
                        endif;
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
