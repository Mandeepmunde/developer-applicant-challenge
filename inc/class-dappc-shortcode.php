<?php
/**
 * Register Shortcode
 *
 * @package Developer Applicant Challenge
 */

/**
 * Class DAPPC_Shortcode class.
 */
class DAPPC_Shortcode {


	/**
	 * DAPPC_Shortcode constructor.
	 */
	public function __construct() {

		// Add Shortcode.
		add_shortcode( 'dappc', array( $this, 'shortcode_function' ) );

	}

	/**
	 * Shortcode function.
	 */
	public function shortcode_function() {

		return '<div class="dappc-container"></div>';

	}

}

new DAPPC_Shortcode();
