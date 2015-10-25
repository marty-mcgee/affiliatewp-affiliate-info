<?php

class AffiliateWP_Affiliate_Landing_Pages_Functions {

	public function __construct() {}

	/**
	 * Get the affiliate ID
	 */
	public function get_affiliate_id() {

		$get_referral_var = affiliate_wp()->tracking->get_referral_var();

		// get the affiliate ID from query string
		$ref_value = isset( $_GET[$get_referral_var] ) ? $_GET[$get_referral_var] : '';

		if ( isset( $ref_value ) && affwp_is_affiliate( affwp_get_affiliate_user_id( $ref_value ) ) ) {
			// if affiliate ID is set in query string make sure they're actually an affiliate
			$affiliate_id = $ref_value;
		} elseif ( affiliate_wp()->tracking->get_affiliate_id() && affwp_is_affiliate( affwp_get_affiliate_user_id( affiliate_wp()->tracking->get_affiliate_id() ) ) ) {
			// get the affiliate ID from the cookie
			$affiliate_id = affiliate_wp()->tracking->get_affiliate_id();
		} else {
			// no affiliate ID
			$affiliate_id = '';
		}

		if ( $affiliate_id ) {
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
new AffiliateWP_Affiliate_Landing_Pages_Functions;
