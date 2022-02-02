<?php
/**
 * Add Admin Options Pages
 *
 * @package Developer Applicant Challenge
 */

/**
 * Class DAPPC_Aadmin_Pages class.
 */
class DAPPC_Aadmin_Pages {

	/**
	 * Admin options page slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $slug = 'dappc-options';

	/**
	 * DAPPC_Api_Handler Class Object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $api_handler;


	/**
	 * DAPPC_Aadmin_Pages constructor.
	 */
	public function __construct() {

		// Add the options page.
		add_action( 'admin_menu', array( $this, 'create_menu_page' ) );

		// Plugin header in plugin settings page.
		add_action( 'in_admin_header', array( $this, 'render_admin_header' ), 100 );

		// Enqueue scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		// Add Data Template.
		add_action( 'admin_footer', array( $this, 'data_template' ) );

		// Admin Ajax handler.
		add_action( 'wp_ajax_dappc_admin_ajax', array( $this, 'admin_ajax_handler' ) );

		// Initialize Api Handler.
		$this->api_handler = new DAPPC_Api_Handler();

	}

	/**
	 * Add admin menu page.
	 */
	public function create_menu_page() {

		add_menu_page(
			esc_html__( 'App Challenge', 'dev-app-challenge' ),
			esc_html__( 'App Challenge', 'dev-app-challenge' ),
			'manage_options',
			$this->slug,
			array( $this, 'display' ),
			'dashicons-rest-api'
		);

	}

	/**
	 * Enqueue admin scripts and styles.
	 */
	public function enqueue_assets() {

		// Return if not plugin options page.
		if ( ! $this->is_admin_page() ) {
			return;
		}

		wp_enqueue_style( 'dappc-admin', DAPPC_ASSETS_URI . '/css/admin.css', false, '1.0.0' );

		wp_register_script( 'dappc-admin-js', DAPPC_ASSETS_URI . '/js/admin.js', array( 'jquery', 'underscore' ), '1.0.0', true );

		wp_enqueue_script( 'dappc-admin-js' );

		wp_localize_script(
			'dappc-admin-js',
			'dappcObj',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'ajaxNonce' => wp_create_nonce( 'dappc_admin_ajax_nonce' ),
			)
		);
	}

	/**
	 * Output admin header for plugin options page.
	 */
	public function render_admin_header() {

		// Return if not plugin options page.
		if ( ! $this->is_admin_page() ) {
			return;
		}

		?>
		<div class="dappc-header">
			<h1><?php echo esc_html__( 'Developer Applicant Challenge', 'dev-app-challenge' ); ?></h1>
		</div>
		<?php
	}

	/**
	 * Admin Ajax Handler.
	 */
	public function admin_ajax_handler() {

		// Die if current User can't manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		// If nonce verification fails die.
		check_ajax_referer( 'dappc_admin_ajax_nonce', 'security' );

		// Clear Transient.
		$this->api_handler->clear_transient();

		// Fetch Api data.
		$api_data = $this->api_handler->fetch_api_data();

		// Send the results from the Api.
		wp_send_json_success( $api_data );

	}

	/**
	 * Render admin page content.
	 */
	public function display() {

		$api_data = $this->api_handler->fetch_api_data();

		?>
		<div class="dappc-admin-wrapper" id="dappc-options">
			<div class="dappc-title-wrapper">
				<ul class="dappc-title-list">
					<li class="dappc-title-item"><?php echo esc_html__( 'API Data', 'dev-app-challenge' ); ?></li>
				</ul>
			</div>
			<div class="dappc-page-container">
				<?php
					$api_results = ( ! empty( $api_data['results'] ) ) ? wp_unslash( $api_data['results'] ) : array();
					$api_results = (array) $api_results;

				if ( empty( $api_results['error'] ) ) {
					?>
						<div id="daapc-admin-table" class="daapc-admin-table-contaier" >
							<h3><?php echo esc_html( $api_results['title'] ); ?></h3>
							<table>
								<thead>
									<tr>
									<?php
									foreach ( $api_results['data']->headers as $item ) {
										?>
											<th><?php echo esc_html( $item ); ?></th>
										<?php
									}
									?>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ( $api_results['data']->rows as $row_item ) {
										?>
										<tr>
											<td><?php echo esc_html( $row_item->id ); ?></td>
											<td><?php echo esc_html( $row_item->fname ); ?></td>
											<td><?php echo esc_html( $row_item->lname ); ?></td>
											<td><?php echo esc_html( $row_item->email ); ?></td>
											<td><?php echo esc_html( $row_item->date ); ?></td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<?php
				} else {
					?>
						<div class="dappc-error-container">
							<table>
								<thead>
									<tr>
										<th><?php echo esc_html__( 'Type', 'dev-app-challenge' ); ?></td>
										<th><?php echo esc_html__( 'Error Message', 'dev-app-challenge' ); ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach ( $api_results as $error_key => $error ) {
									?>
									<tr>
										<th><?php echo esc_html( $error_key ); ?></th>
										<td><?php echo esc_html( $error ); ?></td>
									</tr>
									<?php
								}
								?>
								</tbody>
							</table>
						</div>
						<?php
				}
				?>
				<div class="refresh-btn-wrapper">
					<button type="button" id="refresh-table-data" class="refresh-button">
						<?php esc_html_e( 'Refresh API Data', 'dev-app-challenge' ); ?>
					</button>
					<span class="dappc-loading"><?php esc_html_e( 'Refreshing...', 'dev-app-challenge' ); ?></span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Check whether current page is plugin's options page or not.
	 */
	public function is_admin_page() {

        $current_page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : ''; // phpcs:ignore

		return ( 'dappc-options' === $current_page && is_admin() ) ? true : false;

	}

	/**
	 * Data template to be used via javascript.
	 */
	public function data_template() {
		// Return if not plugin options page.
		if ( ! $this->is_admin_page() ) {
			return;
		}

		?>
		<script type="text/html" id="dappc-admin-template">
			<h3><%= title %></h3>
			<table>
				<thead>
					<tr>
						<% 
							_.each( headers, function( item ) {
								%>
									<th><%= item %></th>
								<%
							} );
						%>
					</tr>
				</thead>
				<tbody>
					<% 
						_.each( rows, function( item ) {
							%>
								<tr>
									<td><%= item.id %></td>
									<td><%= item.fname %></td>
									<td><%= item.lname %></td>
									<td><%= item.email %></td>
									<td><%= item.date %></td>
								</tr>
							<%
						} );
					%>
				</tbody>
			</table>
		</script>
		<?php
	}

}

new DAPPC_Aadmin_Pages();
