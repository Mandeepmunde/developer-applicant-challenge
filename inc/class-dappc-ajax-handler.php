<?php
/**
 * Ajax Request Handler
 *
 * @package Developer Applicant Challenge
 */

/**
 * Class DAPPC_Ajax_Request_Handler class.
 */
class DAPPC_Ajax_Request_Handler {

	/**
	 * DAPPC_Api_Handler Class Object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $api_handler;


	/**
	 * DAPPC_Ajax_Request_Handler constructor.
	 */
	public function __construct() {

		/**
		 * AJAX handlers to fecth Data from the API.
		 * Action name: dappc_fetch_data.
		 */
		add_action( 'wp_ajax_nopriv_dappc_fetch_data', array( $this, 'ajax_handler' ) );
		add_action( 'wp_ajax_dappc_fetch_data', array( $this, 'ajax_handler' ) );

		$this->api_handler = new DAPPC_Api_Handler();

	}

	/**
	 * AJAX handler to fetch data from the API
	 */
	public function ajax_handler() {

		// If nonce verification fails die.
		check_ajax_referer( 'dappc_fetch_data_nonce', 'security' );

		$api_data = $this->api_handler->fetch_api_data();

		wp_send_json_success( $api_data );

	}

}

new DAPPC_Ajax_Request_Handler();
