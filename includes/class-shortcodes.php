<?php

class AffiliateWP_Affiliate_Info_Shortcodes {

	public function __construct() {

        add_shortcode( 'affiliate_info_bio', array( $this, 'shortcode_bio' ) );
		add_shortcode( 'affiliate_info_name', array( $this, 'shortcode_affiliate_name' ) );
		add_shortcode( 'affiliate_info_username', array( $this, 'shortcode_affiliate_username' ) );
		add_shortcode( 'affiliate_info_website', array( $this, 'shortcode_affiliate_website' ) );
		add_shortcode( 'affiliate_info_gravatar', array( $this, 'shortcode_affiliate_gravatar' ) );

	}

    /**
    * [affiliate_info_bio] shortcode
    *
    * @since  1.0.0
    */
    public function shortcode_bio( $atts, $content = null ) {

    	$content = affiliatewp_affiliate_info()->functions->get_affiliate_bio();

    	return do_shortcode( $content );
    }

	/**
    * [affiliate_info_name] shortcode
    *
    * @since  1.0.0
    */
    public function shortcode_affiliate_name( $atts, $content = null ) {

    	$content = affiliatewp_affiliate_info()->functions->get_affiliate_name();

    	return do_shortcode( $content );
    }

	/**
	 * [affiliate_info_username] shortcode
	 *
	 * @since  1.0.0
	 */
	public function shortcode_affiliate_username( $atts, $content = null ) {

		$content = affiliatewp_affiliate_info()->functions->get_affiliate_username();

		return do_shortcode( $content );
	}

	/**
    * [affiliate_info_website] shortcode
    *
    * @since  1.0.0
    */
    public function shortcode_affiliate_website( $atts, $content = null ) {

    	$content = affiliatewp_affiliate_info()->functions->get_affiliate_website();

    	return do_shortcode( $content );
    }

	/**
    * [affiliate_info_gravatar] shortcode
    *
    * @since  1.0.0
    */
    public function shortcode_affiliate_gravatar( $atts, $content = null ) {

    	$content = affiliatewp_affiliate_info()->functions->get_affiliate_gravatar();

    	return do_shortcode( $content );
    }

}
new AffiliateWP_Affiliate_Info_Shortcodes;
