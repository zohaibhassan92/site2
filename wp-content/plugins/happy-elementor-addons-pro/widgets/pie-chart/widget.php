<?php
/**
 * Chart widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons_Pro\Widget;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Happy_Addons_Pro\Widget\Pie_Chart\Data_Map;

defined( 'ABSPATH' ) || die();

class Pie_Chart extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Pie & Doughnut Chart', 'happy-addons-pro' );
	}

//	public function get_custom_help_url() {
//		return '';
//	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'hm hm-graph-pie';
	}

	public function get_keywords() {
		return [ 'chart', 'pie', 'doughnut', 'statistic' ];
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_chart',
			[
				'label' => __( 'Pie Chart', 'happy-addons-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'pie_doughnut',
			[
				'label'   => __( 'Chart Type', 'happy-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'pie',
				'options' => [
					'pie' => __( 'Pie Chart', 'happy-addons-pro' ),
					'doughnut' => __( 'Doughnut Chart', 'happy-addons-pro' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'bar_tabs' );

		$repeater->start_controls_tab(
			'bar_tab_content',
			[
				'label' => __( 'Content', 'happy-addons-pro' ),
			]
		);

		$repeater->add_control(
			'label',
			[
				'label'   => __( 'Label', 'happy-addons-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'data',
			[
				'label'       => __( 'Data', 'happy-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Write data value. Example: 40', 'happy-addons-pro' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'bar_tab_style',
			[
				'label' => __( 'Style', 'happy-addons-pro' ),
			]
		);

		$repeater->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'happy-addons-pro' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$repeater->add_control(
			'background_hover_color',
			[
				'label' => __( 'Background Hover Color', 'happy-addons-pro' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$repeater->end_controls_tab();

		$this->add_control(
			'chart_data',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => [
					[
						'label'              => 'Red',
						'data'               => '54',
						'background_color'       => '#FF4444',
						'background_hover_color' => '#DE1717',
						'border_color'       => '#fff',
						'border_hover_color' => '#fff',
					],
					[
						'label'              => 'Orange',
						'data'               => '85',
						'background_color'       => '#FF8214',
						'background_hover_color' => '#DD6F0E',
						'border_color'       => '#fff',
						'border_hover_color' => '#fff',
					],
					[
						'label'              => 'Yellow',
						'data'               => '88',
						'background_color'       => '#FDD247',
						'background_hover_color' => '#EEBF26',
						'border_color'       => '#fff',
						'border_hover_color' => '#fff',
					],
					[
						'label'              => 'Green',
						'data'               => '70',
						'background_color'       => '#03C17B',
						'background_hover_color' => '#058C5B',
						'border_color'       => '#fff',
						'border_hover_color' => '#fff',
					],
					[
						'label'              => 'Blue',
						'data'               => '15',
						'background_color'       => '#3098F4',
						'background_hover_color' => '#086FCA',
						'border_color'       => '#fff',
						'border_hover_color' => '#fff',
					]
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Settings', 'happy-addons-pro' ),
			]
		);

		$this->add_responsive_control(
			'chart_height',
			[
				'label'       => __( 'Chart Height', 'happy-addons-pro' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min' => 50,
						'max' => 1500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 350,
				],
				'selectors'   => [
					'{{WRAPPER}} .ha-pie-chart-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tooltip_display',
			[
				'label'        => __( 'Show Tooltips', 'happy-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'title_display',
			[
				'label'        => __( 'Show Title', 'happy-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'chart_title',
			[
				'label'       => __( 'Title', 'happy-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Happy Addons Rocks', 'happy-addons-pro' ),
				'condition' => [
					'title_display' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_heading',
			[
				'label'     => __( 'Legend', 'happy-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'legend_display',
			[
				'label'        => __( 'Show Legend', 'happy-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'legend_position',
			[
				'label'     => __( 'Position', 'happy-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'right',
				'options'   => [
					'top'    => __( 'Top', 'happy-addons-pro' ),
					'left'   => __( 'Left', 'happy-addons-pro' ),
					'bottom' => __( 'Bottom', 'happy-addons-pro' ),
					'right'  => __( 'Right', 'happy-addons-pro' ),
				],
				'condition' => [
					'legend_display' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_reverse',
			[
				'label'        => __( 'Reverse', 'happy-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'condition' => [
					'legend_display' => 'yes'
				]
			]
		);

		$this->add_control(
			'animation_heading',
			[
				'label'     => __( 'Animation', 'happy-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_animation_duration',
			[
				'label' => __( 'Duration', 'happy-addons-pro' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10000,
				'step' => 1,
				'default' => 1000,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'animation_options',
			[
				'label'     => __( 'Easing', 'happy-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'linear',
				'options'   => [
					'linear'    => __( 'Linear', 'happy-addons-pro' ),
					'easeInCubic'   => __( 'Ease In Cubic', 'happy-addons-pro' ),
					'easeInCirc' => __( 'Ease In Circ', 'happy-addons-pro' ),
					'easeInBounce' => __( 'Ease In Bounce', 'happy-addons-pro' ),
				]
			]
		);

		$this->add_control(
			'animate_scale',
			[
				'label'        => __( 'Animate Scale', 'happy-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_style_common',
			[
				'label' => __( 'Common', 'happy-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'layout_padding',
			[
				'label' => __( 'Padding', 'happy-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border', 'happy-addons-pro' ),
				'type'  => Controls_Manager::SLIDER,
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'happy-addons-pro' ),
				'type' => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'border_hover_color',
			[
				'label' => __( 'Border Hover Color', 'happy-addons-pro' ),
				'type' => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'title_typography_toggle',
			[
				'label' => __( 'Title Typography', 'happy-addons-pro' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'happy-addons-pro' ),
				'label_on' => __( 'Custom', 'happy-addons-pro' ),
				'return_value' => 'yes',
				'condition' => [
					'title_display' => 'yes'
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'title_font_size',
			[
				'label' => __( 'Font Size', 'happy-addons-pro' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'condition' => [
					'title_display' => 'yes',
					'title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'title_font_family',
			[
				'label' => __( 'Font Family', 'happy-addons-pro' ),
				'type' => Controls_Manager::FONT,
				'default' => '',
				'condition' => [
					'title_display' => 'yes',
					'title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'title_font_weight',
			[
				'label'   => esc_html__( 'Font Weight', 'happy-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'happy-addons-pro' ),
					'normal' => __( 'Normal', 'happy-addons-pro' ),
					'bold'   => __( 'Bold', 'happy-addons-pro' ),
					'300'    => __( '300', 'happy-addons-pro' ),
					'400'    => __( '400', 'happy-addons-pro' ),
					'600'    => __( '600', 'happy-addons-pro' ),
					'700'    => __( '700', 'happy-addons-pro' )
				],
				'condition' => [
					'title_display' => 'yes',
					'title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'title_font_style',
			[
				'label'   => esc_html__( 'Font Style', 'happy-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''        => __( 'Default', 'happy-addons-pro' ),
					'normal'  => __( 'Normal', 'happy-addons-pro' ),
					'italic'  => __( 'Italic', 'happy-addons-pro' ),
					'oblique' => __( 'Oblique', 'happy-addons-pro' ),
				],
				'condition' => [
					'title_display' => 'yes',
					'title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'title_font_color',
			[
				'label' => __( 'Color', 'happy-addons-pro' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'title_display' => 'yes',
					'title_typography_toggle' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_legend',
			[
				'label' => __( 'Legend', 'happy-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'lagend_note',
			[
				'label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Lagend is Switched off from Content > Settings.', 'happy-addons-pro' ),
				'condition' => [
					'legend_display!' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_box_width',
			[
				'label' => __( 'Box Width', 'happy-addons-pro' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 70,
					],
				],
				'condition' => [
					'legend_display' => 'yes'
				]
			]
		);

		$this->add_control(
            'legend_typography_toggle',
            [
                'label' => __( 'Typography', 'happy-addons-pro' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'happy-addons-pro' ),
                'label_on' => __( 'Custom', 'happy-addons-pro' ),
				'return_value' => 'yes',
				'condition' => [
					'legend_display' => 'yes'
				]
            ]
		);

		$this->start_popover();

		$this->add_control(
			'legend_font_size',
			[
				'label' => __( 'Font Size', 'happy-addons-pro' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'condition' => [
					'legend_display' => 'yes',
					'legend_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_font_family',
			[
				'label' => __( 'Font Family', 'happy-addons-pro' ),
				'type' => Controls_Manager::FONT,
				'default' => '',
				'condition' => [
					'legend_display' => 'yes',
					'legend_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_font_weight',
			[
				'label'   => esc_html__( 'Font Weight', 'happy-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'happy-addons-pro' ),
					'normal' => __( 'Normal', 'happy-addons-pro' ),
					'bold'   => __( 'Bold', 'happy-addons-pro' ),
					'300'    => __( '300', 'happy-addons-pro' ),
					'400'    => __( '400', 'happy-addons-pro' ),
					'600'    => __( '600', 'happy-addons-pro' ),
					'700'    => __( '700', 'happy-addons-pro' )
				],
				'condition' => [
					'legend_display' => 'yes',
					'legend_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_font_style',
			array(
				'label'   => esc_html__( 'Font Style', 'happy-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => __( 'Default', 'happy-addons-pro' ),
					'normal'  => __( 'Normal', 'happy-addons-pro' ),
					'italic'  => __( 'Italic', 'happy-addons-pro' ),
					'oblique' => __( 'Oblique', 'happy-addons-pro' ),
				),
				'condition' => [
					'legend_display' => 'yes',
					'legend_typography_toggle' => 'yes'
				]
			)
		);

		$this->add_control(
			'legend_font_color',
			[
				'label' => __( 'Color', 'happy-addons-pro' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'legend_display' => 'yes',
					'legend_typography_toggle' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_tooltip',
			[
				'label' => __( 'Tooltip', 'happy-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tooltip_padding',
			[
				'label' => __( 'Padding', 'happy-addons-pro' ),
				'type'  => Controls_Manager::SLIDER,
				'condition' => [
					'tooltip_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'tooltip_border_width',
			[
				'label' => __( 'Border Width', 'happy-addons-pro' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					'tooltip_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'tooltip_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-addons-pro' ),
				'type'  => Controls_Manager::SLIDER,
				'condition' => [
					'tooltip_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'tooltip_caret_size',
			[
				'label' => __( 'Caret Size', 'happy-addons-pro' ),
				'type'  => Controls_Manager::SLIDER,
				'condition' => [
					'tooltip_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'tooltip_mode',
			[
				'label'   => esc_html__( 'Mode', 'happy-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Select Mode', 'happy-addons-pro' ),
					'nearest' => __( 'Nearest', 'happy-addons-pro' ),
					'index' => __( 'Index', 'happy-addons-pro' ),
					'x' => __( 'X', 'happy-addons-pro' ),
					'y' => __( 'Y', 'happy-addons-pro' ),
				],
				'default' => '',
				'condition' => [
					'tooltip_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'tooltip_background_color',
			[
				'label' => esc_html__( 'Background Color', 'happy-addons-pro' ),
				'type'  => Controls_Manager::COLOR,
				'condition' => [
					'tooltip_display' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_border_color',
			[
				'label' => esc_html__( 'Border Color', 'happy-addons-pro' ),
				'type'  => Controls_Manager::COLOR,
				'condition' => [
					'tooltip_display' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_title_typography_toggle',
			[
				'label' => __( 'Title Typography', 'happy-addons-pro' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'happy-addons-pro' ),
				'label_on' => __( 'Custom', 'happy-addons-pro' ),
				'return_value' => 'yes',
				'condition' => [
					'tooltip_display' => 'yes'
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'tooltip_title_font_size',
			[
				'label' => __( 'Font Size', 'happy-addons-pro' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_title_font_family',
			[
				'label' => __( 'Font Family', 'happy-addons-pro' ),
				'type' => Controls_Manager::FONT,
				'default' => '',
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_title_font_weight',
			[
				'label'   => esc_html__( 'Font Weight', 'happy-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'happy-addons-pro' ),
					'normal' => __( 'Normal', 'happy-addons-pro' ),
					'bold'   => __( 'Bold', 'happy-addons-pro' ),
					'300'    => __( '300', 'happy-addons-pro' ),
					'400'    => __( '400', 'happy-addons-pro' ),
					'600'    => __( '600', 'happy-addons-pro' ),
					'700'    => __( '700', 'happy-addons-pro' )
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_title_font_style',
			[
				'label'   => esc_html__( 'Font Style', 'happy-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''        => __( 'Default', 'happy-addons-pro' ),
					'normal'  => __( 'Normal', 'happy-addons-pro' ),
					'italic'  => __( 'Italic', 'happy-addons-pro' ),
					'oblique' => __( 'Oblique', 'happy-addons-pro' ),
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_title_font_color',
			[
				'label' => __( 'Color', 'happy-addons-pro' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_title_typography_toggle' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_control(
			'tooltip_body_typography_toggle',
			[
				'label' => __( 'Body Typography', 'happy-addons-pro' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'happy-addons-pro' ),
				'label_on' => __( 'Custom', 'happy-addons-pro' ),
				'return_value' => 'yes',
				'condition' => [
					'tooltip_display' => 'yes'
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'tooltip_body_font_size',
			[
				'label' => __( 'Font Size', 'happy-addons-pro' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_body_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_body_font_family',
			[
				'label' => __( 'Font Family', 'happy-addons-pro' ),
				'type' => Controls_Manager::FONT,
				'default' => '',
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_body_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_body_font_weight',
			[
				'label'   => esc_html__( 'Font Weight', 'happy-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'happy-addons-pro' ),
					'normal' => __( 'Normal', 'happy-addons-pro' ),
					'bold'   => __( 'Bold', 'happy-addons-pro' ),
					'300'    => __( '300', 'happy-addons-pro' ),
					'400'    => __( '400', 'happy-addons-pro' ),
					'600'    => __( '600', 'happy-addons-pro' ),
					'700'    => __( '700', 'happy-addons-pro' )
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_body_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_body_font_style',
			[
				'label'   => esc_html__( 'Font Style', 'happy-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''        => __( 'Default', 'happy-addons-pro' ),
					'normal'  => __( 'Normal', 'happy-addons-pro' ),
					'italic'  => __( 'Italic', 'happy-addons-pro' ),
					'oblique' => __( 'Oblique', 'happy-addons-pro' ),
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_body_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_body_font_color',
			[
				'label' => __( 'Color', 'happy-addons-pro' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_body_typography_toggle' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->end_controls_section();
	}


	protected function render() {
		$settings = $this->get_settings_for_display();
		include_once HAPPY_ADDONS_PRO_DIR_PATH . "widgets/pie-chart/classes/data-map.php";

		$this->add_render_attribute(
			'container',
			[
				'class'         => 'ha-pie-chart-container',
				'data-settings' => Data_Map::initial($settings)
			]
		);

		$this->add_render_attribute( 'canvas',
			[
				'id' => 'ha-pie-chart',
				'role'  => 'img',
			]
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'container' ); ?>>

			<canvas <?php echo $this->get_render_attribute_string( 'canvas' ); ?>></canvas>

		</div>

	<?php
	}

}
