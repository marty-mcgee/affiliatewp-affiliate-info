<?php
/**
 * Plugin Name: AffiliateWP - Affiliate Landing Pages
 * Plugin URI: http://affiliatewp.com/
 * Description:
 * Author: Pippin Williamson and Andrew Munro
 * Author URI: http://affiliatewp.com
 * Version: 1.0
 * Text Domain: affiliatewp-plugin-template
 * Domain Path: languages
 *
 * AffiliateWP is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * AffiliateWP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AffiliateWP. If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AffiliateWP_Restrict_To_Affiliates' ) ) {

	final class AffiliateWP_Restrict_To_Affiliates {

		/**
		 * Holds the instance
		 *
		 * Ensures that only one instance of AffiliateWP_Restrict_To_Affiliates exists in memory at any one
		 * time and it also prevents needing to define globals all over the place.
		 *
		 * TL;DR This is a static property property that holds the singleton instance.
		 *
		 * @var object
		 * @static
		 * @since 1.0
		 */
		private static $instance;


		/**
		 * The version number of AffiliateWP
		 *
		 * @since 1.0
		 */
		private $version = '1.0';

		/**
		 * Functions
		 *
		 * @since 1.0
		 */
		public $functions;

		/**
		 * Main AffiliateWP_Restrict_To_Affiliates Instance
		 *
		 * Insures that only one instance of AffiliateWP_Restrict_To_Affiliates exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @static var array $instance
		 * @return The one true AffiliateWP_Restrict_To_Affiliates
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_Restrict_To_Affiliates ) ) {

				self::$instance = new AffiliateWP_Restrict_To_Affiliates;
				self::$instance->setup_constants();
				self::$instance->load_textdomain();
				self::$instance->includes();
				self::$instance->hooks();

				self::$instance->functions 	= new AffiliateWP_Affiliate_Landing_Pages_Functions;

			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-plugin-template' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-plugin-template' ), '1.0' );
		}

		/**
		 * Constructor Function
		 *
		 * @since 1.0
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Reset the instance of the class
		 *
		 * @since 1.0
		 * @access public
		 * @static
		 */
		public static function reset() {
			self::$instance = null;
		}

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function setup_constants() {
			// Plugin version
			if ( ! defined( 'AFFWP_PT_VERSION' ) ) {
				define( 'AFFWP_PT_VERSION', $this->version );
			}

			// Plugin Folder Path
			if ( ! defined( 'AFFWP_PT_PLUGIN_DIR' ) ) {
				define( 'AFFWP_PT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'AFFWP_PT_PLUGIN_URL' ) ) {
				define( 'AFFWP_PT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'AFFWP_PT_PLUGIN_FILE' ) ) {
				define( 'AFFWP_PT_PLUGIN_FILE', __FILE__ );
			}
		}

		/**
		 * Loads the plugin language files
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function load_textdomain() {

			// Set filter for plugin's languages directory
			$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			$lang_dir = apply_filters( 'AffiliateWP_Restrict_To_Affiliates_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale   = apply_filters( 'plugin_locale',  get_locale(), 'affiliatewp-plugin-template' );
			$mofile   = sprintf( '%1$s-%2$s.mo', 'affiliatewp-plugin-template', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/affiliatewp-plugin-template/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/affiliatewp-plugin-template/ folder
				load_textdomain( 'affiliatewp-plugin-template', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/affiliatewp-plugin-template/languages/ folder
				load_textdomain( 'affiliatewp-plugin-template', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'affiliatewp-plugin-template', false, $lang_dir );
			}
		}

		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {
		//	require_once self::$plugin_dir . 'includes/file-name.php';
			require_once AFFWP_PT_PLUGIN_DIR . 'includes/class-shortcodes.php';

			require_once AFFWP_PT_PLUGIN_DIR . 'includes/class-functions.php';
		}



		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function hooks() {

			add_action( 'affwp_register_fields_before_tos', array( $this, 'add_bio_field' ) );

			add_action( 'personal_options_update', array( $this, 'save_bio_field' ) );
			add_action( 'edit_user_profile_update', array( $this, 'save_bio_field' ) );

			add_action( 'user_register', array( $this, 'update_bio_field' ) );
			add_action( 'affwp_process_register_form', array( $this, 'update_bio_field' ) );

			add_action( 'affwp_process_register_form', array( $this, 'process_register_form' ) );

			// plugin meta
			add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), null, 2 );

		}

		/**
		 * Modify plugin metalinks
		 *
		 * @access      public
		 * @since       1.0.0
		 * @param       array $links The current links array
		 * @param       string $file A specific plugin table entry
		 * @return      array $links The modified links array
		 */
		public function plugin_meta( $links, $file ) {
		    if ( $file == plugin_basename( __FILE__ ) ) {
		        $plugins_link = array(
		            '<a title="' . __( 'Get more add-ons for AffiliateWP', 'affiliatewp-plugin-template' ) . '" href="http://affiliatewp.com/addons/" target="_blank">' . __( 'More add-ons', 'affiliatewp-plugin-template' ) . '</a>'
		        );

		        $links = array_merge( $links, $plugins_link );
		    }

		    return $links;
		}

		/**
		 * Add bio field to registration form
		 */
		public function add_bio_field() {

			$bio = sanitize_text_field( $_POST['affwp_bio'] );

			?>
			<p>
				<label for="affwp-bio"><?php _e( 'Bio', 'affiliate-wp' ); ?></label>
				<textarea id="affwp-bio" name="affwp_bio" rows="5" cols="30"><?php if ( ! empty( $bio ) ) { echo esc_textarea( $bio ); } ?></textarea>
			</p>
			<?php
		}

		/**
		 * Save the fields when the values are changed on the profile page
		*/
		public function save_bio_field( $user_id ) {

			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				return false;
			}

			update_user_meta( $user_id, 'description', $_POST['affwp_bio'] );

		}

		/**
		 * Update the user's profile with the new bio value on affiliate registration
		*/
		public function update_bio_field( $user_id ) {

		    $user_id = $user_id ? $user_id : get_current_user_id();

		    if ( isset( $_POST['affwp_bio'] ) ) {
				// update the WordPress bio
		        update_user_meta( $user_id, 'description', $_POST['affwp_bio'] );
		    }

		}


		/**
		 * Make the bio field required and show an error message when not filled in
		 */
		public function process_register_form() {

			$affiliate_wp = affsiliate_wp();

			if ( empty( $_POST['affwp_bio'] ) ) {
				$affiliate_wp->register->add_error( 'bio_invalid', 'Please enter a bio' );
			}

		}

	}

	/**
	 * The main function responsible for returning the one true AffiliateWP_Restrict_To_Affiliates
	 * Instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $AffiliateWP_Restrict_To_Affiliates = AffiliateWP_Restrict_To_Affiliates_load(); ?>
	 *
	 * @since 1.0
	 * @return object The one true AffiliateWP_Restrict_To_Affiliates Instance
	 */
	function affiliatewp_landing_pages() {

	    if ( ! class_exists( 'Affiliate_WP' ) ) {

	        if ( ! class_exists( 'AffiliateWP_Activation' ) ) {
	            require_once 'includes/class-activation.php';
	        }

	        $activation = new AffiliateWP_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
	        $activation = $activation->run();

	    } else {

	        return AffiliateWP_Restrict_To_Affiliates::instance();

	    }
	}
	add_action( 'plugins_loaded', 'affiliatewp_landing_pages', 100 );

}
