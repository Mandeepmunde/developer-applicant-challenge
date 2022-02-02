<?php
/**
 * WP CLI
 *
 * @package Developer Applicant Challenge
 */

/**
 * Class DAPPC_CLI class.
 */
class DAPPC_CLI extends WP_CLI_Command {

	/**
	 * Force Refresh API Data before next AJAX call.
	 *
	 * ## EXAMPLES
	 *
	 * wp dappc
	 */
	public function __invoke() {

		$api_handler = new DAPPC_Api_Handler();
		$transient   = $api_handler->clear_transient();

		if ( $transient ) {

			WP_CLI::success( 'API data is cleared successfully.' );

		} else {

			WP_CLI::error( 'API data is not exists or already cleared.' );

		}

	}

}

WP_CLI::add_command( 'dappc', 'DAPPC_CLI' );

