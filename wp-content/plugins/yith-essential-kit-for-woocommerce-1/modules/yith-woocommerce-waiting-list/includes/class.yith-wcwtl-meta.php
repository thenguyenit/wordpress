<?php
/**
 * Meta class
 *
 * @author Yithemes
 * @package YITH WooCommerce Waiting List
 * @version 1.0.0
 */


if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Meta' ) ) {
	/**
	 * Product metabox class.
	 * The class manage the products metabox for waitlist.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCWTL_Meta {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WCWTL_Meta
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WCWTL_VERSION;


		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WCWTL_Meta
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function __construct() {

			// enqueue script
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'add_meta_boxes', array ( $this, 'add_meta_box' ) );

			// ajax send mail
			add_action( 'wp_ajax_yith_waitlist_send_mail', array( $this, 'yith_waitlist_send_mail_ajax' ) );
			add_action( 'wp_ajax_nopriv_yith_waitlist_send_mail', array( $this, 'yith_waitlist_send_mail_ajax' ) );
		}

		/**
		 * Enqueue scripts
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function enqueue_scripts(){
			wp_enqueue_script( 'yith-waitlist-metabox', YITH_WCWTL_ASSETS_URL . '/js/metabox.js', array( 'jquery' ), YITH_WCWTL_VERSION, true );

			wp_localize_script( 'yith-waitlist-metabox', 'yith_wcwtl_meta', array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' )
			));
		}

		/**
		 * Check product and call add_meta function
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function add_meta_box() {

			global $post;

			if( get_post_type( $post ) !== 'product' ) {
				return;
			}

			$title = __( 'Waiting list', 'yith-woocommerce-waiting-list' );
			// get product
			$product = wc_get_product( $post->ID );

			if( $product->product_type == 'simple' && ! $product->is_in_stock() ) {
				// add metabox
				$this->add_meta( $product->id, $title );
			}
			elseif( $product->product_type == 'variable' ) {
				// get variation
				$variations = $product->get_available_variations();

				foreach ( $variations as $variation ) {

					if( $variation['is_in_stock'] ){
						continue;
					}

					$title = sprintf( __( 'Waiting list for the variation: #%s', 'yith-woocommerce-waiting-list' ), $variation['variation_id'] );
					$this->add_meta( $variation['variation_id'], $title );
				}
			}
		}

		/**
		 * Add waitlist metabox on edit product page
		 *
		 * @access public
		 * @since 1.0.0
		 * @param string $id Product or Variation id
		 * @param string $title The meta title
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function add_meta( $id, $title ) {

			$title = apply_filters( 'yith_wcwtl_metabox_waitlist_title', $title );

			add_meta_box(
				YITH_WCWTL_META . $id,
				$title,
				array( $this, 'build_meta_box' ),
				'product',
				'side',
				'default',
				$id
			);
		}

		/**
		 * Callback function to output metabox in product edit page
		 *
		 * @access public
		 * @since 1.0.0
		 * @param $product
		 * @param $args
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function build_meta_box( $product, $args ){
			// get product id
			$id = $args['args'];
			// get users
			$users = yith_waitlist_get_registered_users( $id );

			if ( ! empty( $users ) ) {
				echo '<p class="users-on-waitlist">';
				echo sprintf( _n( 'There is %s user in the waiting list for this product', 'There are %s users in the waiting list for this product', count( $users ), 'yith-woocommerce-waiting-list' ), count( $users ) );
				echo '</p>';
			}
			else {
				echo __( 'There are no users in this waiting list', 'yith-woocommerce-waiting-list' );
			}

			do_action( 'yith-wcwtl-before-send-button', $users, $id );

			if( ! empty( $users ) ) {
				$this->button_to_send_mail( $id );
			}

			echo '<p class="response-message"></p>';
		}

		/**
		 * Add button for send mail in metabox on product edit page
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function button_to_send_mail( $id ) {
			?>
			<input type="button" class="button yith-waitlist-send-mail" data-product_id="<?php echo $id ?>" value="<?php echo apply_filters( 'yith_wcwtl_button_send_mail_label', __( 'Send the email to the users', 'yith-woocommerce-waiting-list' ) ); ?>" />
			<?php
		}

		/**
		 * Ajax action for send mail to waitlist users
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_waitlist_send_mail_ajax(){

			if( ! isset( $_REQUEST['product'] ) )
				die();


			$product_id = intval( $_REQUEST['product'] );

			// get waitlist users for product
			$users = yith_waitlist_get_registered_users( $product_id );

			if( ! empty( $users ) ) {
				// send mail
				do_action( 'send_yith_waitlist_mailout', $users, $product_id );
			}

			$response = apply_filters( 'yith_wcwtl_send_mail_response', false );

			// check response
			if( $response ) {
				$msg    = apply_filters( 'yith_wcwtl_send_mail_success', __( 'Email sent correctly.', 'yith-woocommerce-waiting-list' ) );
				$send   = true;
				// empty waitlist
				yith_waitlist_empty( $product_id );
			}
			else {
				$msg    = apply_filters( 'yith_wcwtl_send_mail_error', __( 'An error has occurred, please try again.', 'yith-woocommerce-waiting-list' ) );
				$send   = false;
			}

			// pass param to js
			echo json_encode( array (
				'msg'   => $msg,
				'send'  => $send
			));

			die();
		}
	}
}

/**
 * Unique access to instance of YITH_WCWTL_Meta class
 *
 * @return \YITH_WCWTL_Meta
 * @since 1.0.0
 */
function YITH_WCWTL_Meta(){
	return YITH_WCWTL_Meta::get_instance();
}