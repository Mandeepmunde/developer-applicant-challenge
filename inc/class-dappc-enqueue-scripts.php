<?php
/**
 * Enqueue Scripts
 *
 * @package Developer Applicant Challenge
 */

/**
 * Class DAPPC_Enqueue_Scripts class.
 */
class DAPPC_Enqueue_Scripts {


	/**
	 * DAPPC_Enqueue_Scripts constructor.
	 */
	public function __construct() {

		// Enqueue frontend scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

	}

	/**
	 * Enqueue frontend scripts and styles.
	 */
	public function enqueue_assets() {

		wp_register_script( 'dappc-main', DAPPC_ASSETS_URI . '/js/main.js', array( 'jquery', 'underscore' ), '1.0.0', true );

		wp_enqueue_script( 'dappc-main' );

		wp_localize_script(
			'dappc-main',
			'dappcObj',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'ajaxNonce' => wp_create_nonce( 'dappc_fetch_data_nonce' ),
			)
		);
	}

}

new DAPPC_Enqueue_Scripts();
