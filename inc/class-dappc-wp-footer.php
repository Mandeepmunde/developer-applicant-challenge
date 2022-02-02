<?php
/**
 * Footer Actions
 *
 * @package Developer Applicant Challenge
 */

/**
 * Class DAPPC_Wp_Footer class.
 */
class DAPPC_Wp_Footer {


	/**
	 * DAPPC_Wp_Footer constructor.
	 */
	public function __construct() {

		// Add Data Template.
		add_action( 'wp_footer', array( $this, 'data_template' ) );

	}

	/**
	 * Data template to be used via javascript.
	 */
	public function data_template() {
		?>
		<script type="text/html" id="dappc-template">
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
							var itemDate = new Date( item.date ).toLocaleString();
							%>
								<tr>
									<td><%= item.id %></td>
									<td><%= item.fname %></td>
									<td><%= item.lname %></td>
									<td><%= item.email %></td>
									<td><%= itemDate %></td>
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

new DAPPC_Wp_Footer();
