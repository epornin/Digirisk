<?php if ( !defined( 'ABSPATH' ) ) exit;

class group_filter {

	public function __construct() {
		add_filter( 'digi_tab', array( $this, 'callback_digi_tab' ) );
	}

	function callback_digi_tab( $tab_list ) {
		$tab_list['digi-group']['configuration'] = array(
			'text' => __( 'Configuration', 'digirisk' ),
		);

		return $tab_list;
	}
}

new group_filter();
