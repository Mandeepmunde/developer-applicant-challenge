<?php
/**
 * Api Handler
 *
 * @package Developer Applicant Challenge
 */

/**
 * Class DAPPC_Api_Handler class.
 */
class DAPPC_Api_Handler {


	/**
	 * The api url we are calling.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $api_url = 'https://miusage.com/v1/challenge/1/';

	/**
	 * The transient name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $transient_name = 'dappc_api_data';

	/**
	 * Call Api to fetch data.
	 */
	public function fetch_api_data() {

		// Check API data is available in transients.
		$transient = get_transient( $this->transient_name );

		if ( false !== $transient && !empty( (array) $transient ) ) {

			$success = true;
			$results = $transient;

		} else {

			$response = wp_remote_get( $this->api_url );

			if ( is_wp_error( $response ) ) {

				$success = false;
				$results = $response->get_error_message();

			} else {

				$results = wp_remote_retrieve_body( $response );
				$results = json_decode( $results );

				if ( empty( $results->error ) ) {

					$success = true;
					set_transient( $this->transient_name, $results, HOUR_IN_SECONDS );

				} else {

					$success = false;

				}
			}
		}

		return array(
			'success' => $success,
			'results' => $results,
		);

	}

	/**
	 * Clear transients.
	 */
	public function clear_transient() {

		return delete_transient( $this->transient_name );

	}

}

new DAPPC_Api_Handler();
