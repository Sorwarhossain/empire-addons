<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor List Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Webinar_Start extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name() {
        return 'webinar-start';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Webinar Start', 'empire-addons');
    }

    /**
     * Get widget icon.
     * 
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-video-playlist';
    }

    /**
     * Get custom help URL.
     *
     * Retrieve a URL where the user can get more information about the widget.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget help URL.
     */
    public function get_custom_help_url() {
        return 'https://bootstrappedempire.com/';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the list widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['empire-addons'];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the list widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return ['Empire', 'Custom', 'Bootstrapped', 'Webinar', 'Start'];
    }


    public function get_script_depends() {
        return ['empire_simple_timer', 'empire_scripts'];
    }

    public function get_style_depends() {
        return ['empire_styles'];
    }


    /**
     * Register list widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Webinar Start', 'empire-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Webinar Title', 'empire-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title', 'empire-addons'),
                'default' => esc_html__('Webinar Start In:', 'empire-addons'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'api_key',
            [
                'label' => esc_html__('Webinar API Key', 'empire-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter API Key from WebinarJam', 'empire-addons'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'webinar_id',
            [
                'label' => esc_html__('Webinar ID', 'empire-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter Webinar ID', 'empire-addons'),
                'label_block' => true,
            ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
            'webinar_layout_section',
            [
                'label' => esc_html__('Layout Settings', 'empire-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'webinar_layout',
            [
                'label' => esc_html__('Select Layout', 'empire-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'layout-1',
                'options' => [
                    'layout-1'  => esc_html__('Layout One', 'empire-addons'),
                    'layout-2' => esc_html__('Layout Two', 'empire-addons'),
                ],
            ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
            'definition_list_styles',
            [
                'label'  => __('Webinar Start Styles', 'empire-addons'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'empire-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#28303d',
                'selectors' => [
                    '{{WRAPPER}} .empire_timer div' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'empire-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#28303d',
                'selectors' => [
                    '{{WRAPPER}} h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Title Typography', 'empire-addons'),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} h3',
            ]
        );

        $this->add_control(
            'box_border_color',
            [
                'label' => esc_html__('Box Border Color', 'empire-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d2d2d2',
                'selectors' => [
                    '{{WRAPPER}} .empire_timer div' => 'border-color: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Render list widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();
        $api_key = $settings['api_key'];
        $webinar_id = $settings['webinar_id'];

        $seconds = 120;

        if (!empty($api_key) && !empty($webinar_id)) {

            try {

                $body = array(
                    'api_key' => $api_key,
                    'webinar_id' => $webinar_id
                );

                $body = wp_json_encode($body);

                $result = wp_remote_post('https://api.webinarjam.com/webinarjam/webinar', array(
                    'method' => 'POST',
                    'headers' => array('Content-Type' => 'multipart/form-data'),
                    'body'        => $body,
                    'headers'     => [
                        'Content-Type' => 'application/json',
                    ],
                ));

                if (!is_wp_error($result) && isset($result['body'])) {
                    $response_body = json_decode($result['body']);

                    if ($response_body->status == 'success') {

                        $timezone = $response_body->webinar->timezone;
                        $schedule = $response_body->webinar->schedules[0];
                        $datetime = new DateTime("now", new DateTimeZone($timezone));
                        $currentTime = $datetime->format('Y-m-d H:i:s');
                        $seconds = strtotime($schedule->comment) - strtotime($currentTime);

                        // Get the seconds from teh result.

                    }
                }
                // echo var_dump($result);
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }

?>

        <div class="webinar-start-wrap">
            <h3><?php echo esc_html($settings['title']); ?></h3>
            <div class="empire_timer" data-seconds-left="<?php echo $seconds; ?>"></div>
        </div>

<?php
    }
}
