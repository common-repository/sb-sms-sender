<?php

defined( 'ABSPATH' ) || exit;

class SB_SMS_Sender_Admin_Tab {

	public function __construct() {

		$this->id    = 'sb_sms_sender_admin_tab';
		$this->label = __( 'SMS Sender', 'sbsmssender' );

		add_filter( 'woocommerce_settings_tabs_array', [ $this, 'add_tab' ], 200 );
		add_action( 'woocommerce_settings_' . $this->id, [ $this, 'output' ] );
		add_action( 'woocommerce_sections_' . $this->id, [ $this, 'output_sections' ] );
		add_action( 'woocommerce_settings_save_' . $this->id, [ $this, 'save' ] );
	}

	public function add_tab( $settings_tabs ) {

		$settings_tabs[ $this->id ] = $this->label;

		return $settings_tabs;
	}

	public function get_sections() {

		$sections = [
			''            => __( 'General', 'sbsmssender' ),
			'sms-cabinet' => __( 'Cabinet', 'sbsmssender' ),
//			'sms-templates' => __( 'Templates', 'sbsmssender' ), // to do if it will be time
		];

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	public function output_sections() {
		global $current_section;

		$sections = $this->get_sections();

		if ( empty( $sections ) || 1 === sizeof( $sections ) ) {
			return;
		}

		echo '<ul class="subsubsub">';

		foreach ( $sections as $id => $label ) : ?>
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) ); ?>"
				   class="<?php echo esc_attr( $current_section == $id ? 'current' : '' ); ?>"><?php echo esc_attr( $label ); ?></a>
			</li>
			<span class="divider">|</span>
		<?php endforeach;

		echo '<li><a target="_blank" href="https://smsclub.mobi/api">' . __( 'API Documentation', 'sbsmssender' ) . '</a></li>';

		echo '</ul><br class="clear" />';
	}

	public function get_settings( $current_section = '' ) {

		$available_image_sizes_adapted = [];
		$available_image_sizes         = get_intermediate_image_sizes();
		foreach ( $available_image_sizes as $image_size ) {
			$available_image_sizes_adapted[ $image_size ] = $image_size;
		}
		$available_image_sizes_adapted['full'] = 'full';

		$pages_select_adapted = [ '-' => '-' ];
		$pages_select         = get_pages();
		foreach ( $pages_select as $page ) {
			$pages_select_adapted[ $page->ID ] = $page->post_title;
		}

		if ( 'sms-cabinet' == $current_section ) {

			$settings = apply_filters( 'sb_sms_admin_tab_settings', [
				'section_title' => [
					'name' => __( 'Client data', 'sbsmssender' ),
					'type' => 'title',
					'desc' => __( 'Click <a href="https://smsclub.mobi/">here</a> to login/register and use your credentials below', 'sbsmssender'),
					'id'   => 'sb_sms_admin_tab_section_title'
				],
				'login'         => [
					'name' => __( 'Login', 'sbsmssender' ),
					'type' => 'text',
					'desc' => __( 'Your login in SMS Club', 'sbsmssender' ),
					'id'   => 'sb_sms_admin_tab_login'
				],
				'token'          => [
					'name' => __( 'Token', 'sbsmssender' ),
					'type' => 'password',
					'desc' => __( 'Your token in SMS Club', 'sbsmssender' ),
					'id'   => 'sb_sms_admin_tab_token'
				],
				'alfa_name'     => [
					'name' => __( 'Alfa name', 'sbsmssender' ),
					'type' => 'text',
					'desc' => __( 'Alfa name of your campaign', 'sbsmssender' ),
					'id'   => 'sb_sms_admin_tab_alfa_name'
				],

				'section_end' => [
					'type' => 'sectionend',
					'id'   => 'sb_sms_admin_tab_section_title'
				]
			] );
		} else {

			$settings = apply_filters( 'sb_sms_admin_tab_product_settings', [
				'section_title'  => [
					'name' => __( 'General', 'sbsmssender' ),
					'type' => 'title',
					'desc' => __( 'Enable plugin and its options', 'sbsmssender'),
					'id'   => 'sb_sms_enable_plugin_section'
				],
				'enable_sending' => [
					'name'    => __( 'Enable', 'sbsmssender' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => __( 'Enable SMS sending', 'sbsmssender' ),
					'id'      => 'sb_sms_enable_plugin',
				],
				'section_end' => [
					'type' => 'sectionend',
					'id'   => 'sb_sms_enable_plugin_section'
				],

				'section_title2'  => [
					'name' => __( 'Tune SMS sending', 'sbsmssender' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'sb_sms_enable_options_section'
				],
				'enable_new_order' => [
					'name'    => __( 'New order', 'sbsmssender' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => __( 'Enable SMS sending for new order', 'sbsmssender' ),
					'desc_tip'=> __( 'SMS will be sent to client after visiting thank-you page', 'sbsmssender' ),
					'id'      => 'sb_sms_new_order',
				],
				'enable_status_completed' => [
					'name'    => __( 'Order is done', 'sbsmssender' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => __( 'Enable SMS sending when order status changed to completed', 'sbsmssender' ),
					'desc_tip'=> __( 'SMS will be sent to client each time the order status switch to closed', 'sbsmssender' ),
					'id'      => 'sb_sms_status_completed',
				],

				'section_end2' => [
					'type' => 'sectionend',
					'id'   => 'sb_sms_enable_options_section'
				]
			] );
		}

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}

	public function output() {

		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );
	}

	public function save() {

		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );
	}
}

return new SB_SMS_Sender_Admin_Tab();
