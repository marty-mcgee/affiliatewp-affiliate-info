<?php

class AffiliateWP_Affiliate_Landing_Pages_Functions {

	public function __construct() {
	}

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
	 * Get the affiliate's details
	 *
	 * @since 1.0.0
	 */
	public function get_affiliate_details( $field_id = '' ) {

		if ( ! $field_id ) {
			return;
		}

		// get the currently logged in affiliate's details
		$meta = affwp_get_affiliate_meta( affwp_get_affiliate_id(), 'affiliate_details', true );

		if ( $meta ) {
			return $meta[$field_id]['value'];
		}

		return false;

	}


	/**
	 * Get the affiliate's name
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
	* Get the affiliate's gravatar
	*
	* @since 1.0.0
	*/
   public function get_affiliate_gravatar() {

	   $affiliate_id = $this->get_affiliate_id();

	   if ( $affiliate_id ) {

		   $args = apply_filters( 'affwp_lp_gravatar_defaults', array(
			   'size'    => 96,
			   'default' => '',
			   'alt'     => $this->get_affiliate_name()
		   ) );

		   $email = affwp_get_affiliate_email( $affiliate_id );

		   return get_avatar( affwp_get_affiliate_user_id( $affiliate_id ), $args['size'], $args['default'], $args['alt'] );

	   }

	   return false;

   }

	/**
	 * Display the affiliates bio
	 *
	 * @since 1.0.0
	 */
	 public function show_affiliate_bio( $atts = array() ) {

		ob_start();

		if ( $this->get_affiliate_bio() ) {

			if ( $atts['title'] ) {
				echo '<h2>' . $atts['title'] . '</h2>';
			}

			echo '<p>' . $this->get_affiliate_bio() . '</p>';

		}

		$html =  ob_get_clean();

		return apply_filters( 'affwp_lp_affiliate_bio', $html );

	 }

	 /**
 	 * Display the affiliates details
	 *
	 * @since 1.0.0
 	 */
 	 public function show_affiliate_details( $atts = array() ) {

 		ob_start();

 		//if ( $this->get_affiliate_bio() ) {

 			if ( $atts['title'] ) {
 				echo '<h2>' . $atts['title'] . '</h2>';
 			}

 			echo '<p>' . $this->get_affiliate_details( $atts['field'] ) . '</p>';

 		//}

 		$html =  ob_get_clean();

 		return apply_filters( 'affwp_lp_affiliate_details', $html );

 	 }


	 /**
	  * Display the affiliate's name
	  *
	  * @since 1.0.0
	  */
	 public function show_affiliate_name( $atts = array() ) {

		ob_start();

		if ( $this->get_affiliate_name() ) {

			if ( $atts['title'] ) {
	 			echo '<h2>' . $atts['title'] . '</h2>';
	 		}

			echo '<p>' . $this->get_affiliate_name() . '</p>';

		}

 		$html =  ob_get_clean();

 		return apply_filters( 'affwp_lp_affiliate_name', $html );

	 }

	 /**
	  * Display the affiliate's website
	  *
	  * @since 1.0.0
	  */
	 public function show_affiliate_website( $atts = array() ) {

		ob_start();

		if ( $this->get_affiliate_website() ) {

			if ( $atts['title'] ) {
	 			echo '<h2>' . $atts['title'] . '</h2>';
	 		}

			echo '<p>' . $this->get_affiliate_website() . '</p>';

		}

 		$html =  ob_get_clean();

 		return apply_filters( 'affwp_lp_affiliate_website', $html );

	 }

	 /**
	  * Display the affiliate's gravatar
	  *
	  * @since 1.0.0
	  */
	 public function show_affiliate_gravatar( $atts = array() ) {

		ob_start();

		if ( $this->get_affiliate_gravatar() ) {

			if ( $atts['title'] ) {
	 			echo '<h2>' . $atts['title'] . '</h2>';
	 		}

			echo '<p>' . $this->get_affiliate_gravatar() . '</p>';

		}

 		$html =  ob_get_clean();

 		return apply_filters( 'affwp_lp_affiliate_gravatar', $html );

	 }

}
