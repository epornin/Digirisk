<?php if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Fichier du controlleur principal de l'extension digirisk pour wordpress / Main controller file for digirisk plugin
 *
 * @author Evarisk development team <dev@evarisk.com>
 * @version 6.0
 */

/**
 * Classe du controlleur principal de l'extension digirisk pour wordpress / Main controller class for digirisk plugin
 *
 * @author Evarisk development team <dev@evarisk.com>
 * @version 6.0
 */
class workunit_action {

	/**
	 * CORE - Instanciation des actions ajax pour les unités de travail / Instanciate ajax treatment for work unit
	 */
	function __construct() {
		/**	Affiche une fiche d'unité de travail / Display a work unit sheet	*/
		add_action( 'wp_ajax_wpdigi_workunit_sheet_display', array( $this, 'display_workunit_sheet' ) );

		/**	Création d'une unité de travail / Create a work unit	*/
		add_action( 'wp_ajax_wpdigi_ajax_workunit_create', array( $this, 'create_workunit' ) );

		/** Suppresion d'une unité de travail / Delete a work unit */
		add_action( 'wp_ajax_wpdigi_ajax_workunit_delete', array( $this, 'delete_workunit' ) );

		/** Mise à jour d'une unité de travail / Update a work unit */
		add_action( 'wp_ajax_wpdigi_ajax_workunit_update', array( $this, 'update_workunit' ) );

		/**	Affichage de la liste des utilisateurs affectés à une unité de travail / Display user associated to a work unit	*/
		add_action( 'wp_ajax_wpdigi_loadsheet_workunit', array( $this, 'display_workunit_sheet_content' ), 9 );

		/**	Association de fichiers à une unité de travail / Associate file to a workunit	*/
		add_action( 'wp_ajax_wpfile_associate_file_digi-workunit', array( $this, 'associate_file_to_workunit' ) );

		/**	Génération de la fiche d'une unité de travail / Generate sheet for a workunit	*/
		add_action( 'wp_ajax_wpdigi_save_sheet_digi-workunit', array( $this, 'generate_workunit_sheet' ) );
	}

	/**
	 * Affiche la fiche d'une unité de travail / Display a work unit sheet
	 */
	function display_workunit_sheet() {
		/**	Check if the ajax request come from a known source	*/
		check_ajax_referer( 'wpdigi_workunit_sheet_display', 'wpdigi_nonce' );

		/**	Check if requested workunit is weel formed	*/
		$workunit_id = null;
		if ( !empty( $_POST ) && !empty( $_POST[ 'workunit_id' ] ) && is_int( (int)$_POST[ 'workunit_id' ] ) && ( 0 < (int)$_POST[ 'workunit_id' ]) ) {
			$workunit_id = (int)$_POST[ 'workunit_id' ];
		}

		$this->display( $workunit_id );
		wp_die();
	}

	/**
	 * Création d'une unité de travail / Create a new workunit
	 */
	public function create_workunit() {
		/**	Check if the ajax request come from a known source	*/
		check_ajax_referer( 'wpdigi-workunit-creation', 'wpdigi_nonce' );

		if ( 0 === ( int )$_POST['workunit']['parent_id'] )
			wp_send_json_error();
		else
			$parent_id = (int) $_POST['workunit']['parent_id'];

		/**	Génération des identifiants unique pour l'unité / Create the unique identifier for workunit	*/
		$next_identifier = wpdigi_utils::get_last_unique_key( 'post', workunit_class::get()->get_post_type() );

		$workunit = array(
			'title' => sanitize_text_field( $_POST['workunit']['title'] ),
			'parent_id' => $parent_id,
			'option' => array(
				'unique_key' => (int)( $next_identifier + 1 ),
				'unique_identifier' => 'UT' . ( $next_identifier + 1 ),
			)
		);

		/**	Création de l'unité / Create the unit	*/
		$workunit = workunit_class::get()->create( $workunit );

		if ( !empty( $workunit->id ) ) {
			$args['workunit_id'] = $workunit->id;
			/**	Define a nonce for display sheet using ajax	*/
			$workunit_display_nonce = wp_create_nonce( 'wpdigi_workunit_sheet_display' );

			$status = true;
			$message = __( 'Work unit have been created succesfully', 'digirisk' );
			/**	Affichage de la liste des unités de travail pour le groupement actuellement sélectionné / Display the work unit list for current selected group	*/
			ob_start();
			require_once( wpdigi_utils::get_template_part( WPDIGI_STES_DIR, WPDIGI_STES_TEMPLATES_MAIN_DIR, 'workunit', 'list', 'item' ) );
			$output = ob_get_contents();
			ob_end_clean();
		}
		else {
			$status = false;
			$message = __( 'An error occured while creating work unit', 'digirisk' );
			$output = null;

			wpeologs_ctr::log_datas_in_files( $this->get_post_type(), array( 'object_id' => null, 'message' => sprintf( __( 'Work unit could not been create. request: %s response: %s', 'digirisk'), json_encode( $_POST ), json_encode( $workunit ) ), ), 2 );
		}

		ob_start();
		group_class::get()->display_all_group( $workunit->parent_id );
		wp_die( json_encode( array( 'template' => ob_get_clean(), 'status' => $status, 'message' => $message, 'element' => $workunit, 'output' => $output, ) ) );
	}

	/**
	 * Suppression d'une unité de travail / Delete a workunit
	 */
	public function delete_workunit() {
		if ( 0 === (int) $_POST['workunit_id'] )
			wp_send_json_error();
		else
			$workunit_id = (int) $_POST['workunit_id'];

		wpdigi_utils::check( 'ajax_delete_workunit_' . $workunit_id );

		$workunit = array(
			'id' 		=> $workunit_id,
			'status'	=> 'trash',
			'date_modified'	=> current_time( 'mysql', 0 ),
		);

		workunit_class::get()->update( $workunit );
		$workunit = workunit_class::get()->show( $workunit_id );

		ob_start();
		group_class::get()->display_all_group( $workunit->parent_id );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	/**
	 * Enregistrement des modifications sur une unité de travail / Update data for a workunit
	 */
	public function update_workunit() {
		if ( 0 === (int) $_POST['workunit_id'] )
			wp_send_json_error();
		else
			$workunit_id = (int) $_POST['workunit_id'];

		wpdigi_utils::check( 'ajax_update_workunit_' . $workunit_id );

		$workunit = array(
			'id' 		=> $workunit_id,
			'date_modified'	=> current_time( 'mysql', 0 ),
		);

		$workunit['title'] = sanitize_text_field( $_POST['title'] );

		$updated_workunit = $this->update( $workunit );

		wp_send_json_success( $updated_workunit );
	}

	/**
	 * Affichage du contenu de l'onglet sur lequel l'utilisateur vient de cliquer / Display tab content corresponding to which one the user clic on
	 */
	public function display_workunit_sheet_content() {
		if ( 0 === (int) $_POST['workunit_id'] )
			wp_send_json_error();
		else
			$workunit_id = (int) $_POST['workunit_id'];

		$subaction = sanitize_text_field( $_POST['subaction'] );

		$this->current_workunit = $this->show( $workunit_id );

		$response = array(
			'status'		=> false,
			'output'		=> null,
			'message'		=> __( 'Element to load have not been found', 'digirisk' ),
		);

		ob_start();
		$this->display_workunit_tab_content( $this->current_workunit, $subaction );
		$response['output'] = ob_get_contents();
		ob_end_clean();

		wp_die( json_encode( $response ) );
	}

	/**
	 * Affectation de fichiers a une unité de travail / Associate file to a workunit
	 */
	public function associate_file_to_workunit() {
		if ( 0 === (int) $_POST['element_id'] )
			wp_send_json_error();
		else
			$element_id = (int) $_POST['element_id'];

		if( !isset( $_POST['thumbnail'] ) )
			wp_send_json_error();
		else {
			$thumbnail = (bool) $_POST['thumbnail'];
		}

		wpdigi_utils::check( 'ajax_file_association_' . $element_id );

		if ( empty( $_POST ) || empty( $_POST[ 'files_to_associate'] ) || !is_array( $_POST[ 'files_to_associate'] ) )
			wp_send_json_error( array( 'message' => __( 'Nothing has been founded for association', 'digirisk' ), ) );


		$workunit = $this->show( $element_id );


		$response = null;
		foreach ( $_POST[ 'files_to_associate'] as $file_id ) {
			if ( true === is_int( (int)$file_id ) ) {
				if ( wp_attachment_is_image( $file_id ) ) {
					$workunit->option[ 'associated_document_id' ][ 'image' ][] = (int)$file_id;

					if ( !empty( $thumbnail ) ) {
						set_post_thumbnail( $element_id , (int)$file_id );
					}
				}
				else {
					$workunit->option[ 'associated_document_id' ][ 'document' ][] = (int)$file_id;
				}
			}
		}
		$updated_workunit = $this->update( $workunit );
		$workunit = $this->show( $element_id );
		$workunit_display_nonce = wp_create_nonce( 'wpdigi_workunit_sheet_display' );

		$this->current_workunit = $workunit;

		$workunit_default_tab = apply_filters( 'wpdigi_workunit_default_tab', '' );

		ob_start();
		require( wpdigi_utils::get_template_part( WPDIGI_STES_DIR, WPDIGI_STES_TEMPLATES_MAIN_DIR, 'workunit', 'list', 'item' ) );
		$list_item_workunit = ob_get_clean();

		ob_start();
		require( wpdigi_utils::get_template_part( WPDIGI_STES_DIR, WPDIGI_STES_TEMPLATES_MAIN_DIR, 'workunit', 'sheet', 'simple' ) );
		$sheet_simple = ob_get_clean();

		wp_send_json_success( array( 'workunit_id' => $element_id, 'list_item_workunit' => $list_item_workunit, 'sheet_simple' => $sheet_simple ) );
	}

	/**
	 * Enregistrement et création de la fiche d'une unité de travail / Save and create file for a workunit sheet
	 */
	public function generate_workunit_sheet() {
		check_ajax_referer( 'digi_ajax_generate_element_sheet' );

		$element_id = !empty( $_POST ) && !empty( $_POST[ 'element_id' ] ) && is_int( (int)$_POST[ 'element_id' ] ) ? (int)$_POST[ 'element_id' ] : ( null !== $element_id  && is_int( (int)$element_id ) ? (int)$element_id : null );

		if ( 0 === $element_id ) {
			wp_send_json_error( array( 'message' => __( 'Requested element for sheet generation is invalid. Please check your request', 'digirisk' ), ) );
		}

		$generation_response = workunit_class::get()->generate_workunit_sheet( $element_id );

		wp_die( json_encode( $generation_response ) );
	}
}

new workunit_action();