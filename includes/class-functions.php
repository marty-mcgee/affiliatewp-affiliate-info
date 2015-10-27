<?php

class AffiliateWP_Affiliate_Info_Functions {

	public function __construct() {}

	/**
	 * Get the affiliate ID
	 */
	public function get_affiliate_id() {

		global $wp_query;

		// credit last referrer enabled
		$credit_last_referrer = affiliate_wp()->settings->get( 'referral_credit_last' );

		// get referral variable (eg ref)
		$referral_var = affiliate_wp()->tracking->get_referral_var();

		// if credit last referrer is enabled it needs to get the affiliate ID from the URL straight away
		if ( $credit_last_referrer ) {

			if ( isset( $wp_query->query[$referral_var] ) ) {
				// get affiliate ID from query vars (pretty affiliate URLs)
				$affiliate_id = $wp_query->query[$referral_var];
			} elseif ( affiliate_wp()->tracking->get_affiliate_id() ) {
				// get affiliate ID from cookies
				$affiliate_id = affiliate_wp()->tracking->get_affiliate_id();
			} else {
				// no affiliate ID
				$affiliate_id = '';
			}

		} else {

			// get affiliate from cookie first
			if ( affiliate_wp()->tracking->get_affiliate_id() ) {
				$affiliate_id = affiliate_wp()->tracking->get_affiliate_id();
			} elseif ( isset( $wp_query->query[$referral_var] ) ) {
				// get affiliate ID from query vars (pretty and non-pretty affiliate URLs)
				$affiliate_id = $wp_query->query[$referral_var];
			} else {
				// no affiliate ID
				$affiliate_id = '';
			}

		}

		// finally, check if they are a valid affiliate
		if ( $affiliate_id && affwp_is_affiliate( affwp_get_affiliate_user_id( $affiliate_id ) ) && affwp_is_active_affiliate( $affiliate_id ) ) {
			return $affiliate_id;
		}

		return false;

	}

	/**
	 * Get the affiliate's bio
	 *
	 * @since 1.0.0
	 */
	public function get_affiliate_bio() {

		$user_id = affwp_get_affiliate_user_id( $this->get_affiliate_id() );

		if ( $user_id ) {
			$bio = get_user_meta( $user_id, 'description', true );
			return $bio;
		}

		return false;

	}

	/**
	 * Get the affiliate's display name
	 *
	 * @since 1.0.0
	 */
	public function get_affiliate_name() {

		$affiliate_id = $this->get_affiliate_id();

		if ( $affiliate_id ) {
			return affiliate_wp()->affiliates->get_affiliate_name( $affiliate_id );
		}

		return false;

	}

	/**
	 * Get the affiliate's username
	 *
	 * @since 1.0.0
	 */
	public function get_affiliate_username() {

		$affiliate_id = $this->get_affiliate_id();

		if ( $affiliate_id ) {

			$user_info = get_userdata( affwp_get_affiliate_user_id( $affiliate_id ) );

			if ( $user_info ) {
				$username  = esc_html( $user_info->user_login );
				return esc_html( $username );
			}

		}

		return false;

	}

	/**
	 * Get the affiliate's website
	 *
	 * @since 1.0.0
	 */
	public function get_affiliate_website() {

		$affiliate_id = $this->get_affiliate_id();

		if ( $affiliate_id ) {
			return get_the_author_meta( 'user_url', affwp_get_affiliate_user_id( $affiliate_id ) );
		}

		return false;

	}

	/**
	 * Get the affiliate's email
	 *
	 * @since 1.0.0
	 */
	public function get_affiliate_email() {

		$affiliate_id = $this->get_affiliate_id();

		if ( $affiliate_id ) {
			return affwp_get_affiliate_email( $affiliate_id );
		}

		return false;

	}

	/**
	* Get the affiliate's gravatar
	*
	* @since 1.0.0
	*/
   public function get_affiliate_gravatar() {

	   $affiliate_id = $this->get_affiliate_id();

	   if ( $affiliate_id ) {

		   $args = apply_filters( 'affwp_affiliate_info_gravatar_defaults', array(
			   'size'    => 96,
			   'default' => '',
			   'alt'     => $this->get_affiliate_name()
		   ) );

		   $email = affwp_get_affiliate_email( $affiliate_id );

		   return get_avatar( affwp_get_affiliate_user_id( $affiliate_id ), $args['size'], $args['default'], $args['alt'] );

	   }

	   return false;

   }

}
