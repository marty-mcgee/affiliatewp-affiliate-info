<?php

class AffiliateWP_Affiliate_Landing_Pages_Admin {

	public function __construct() {
		add_filter( 'affwp_settings_integrations', array( $this, 'settings' ) );
	}

	/**
	 * Option to globally allow all affiliates to access order details
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function settings( $fields ) {

		$fields['alp_header'] = array(
			'name' => __( 'Affiliate Landing Pages', 'affiliatewp-affiliate-landing-pages' ),
			'type' => 'header'
		);

		$fields['alp_bio'] = array(
			'name' => __( 'Show bio field', 'affiliatewp-affiliate-landing-pages' ),
			'desc' => __( 'Add the biographical info field to the affiliate registration form.', 'affiliatewp-order-details-for-affiliates' ),
			'type' => 'checkbox'
		);

		return $fields;
	}

}
$affiliatewp_menu = new AffiliateWP_Affiliate_Landing_Pages_Admin;
