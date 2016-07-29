<?php

namespace digi\transfert;

if ( !defined( 'ABSPATH' ) ) exit; ?>
<div class="about-wrap wp-digi-wrap wp-digi-clearer">
	<h1><?php _e( 'Transfer datas for digirisk', 'wp-digi-dtrans-i18n' ); ?></h1>
	<div class="about-text"><?php _e( 'Next digirisk version will use more wordpress fonctionnalities. For this purpose we need to transfer some datas from our specific storage to wordpress storage.', 'wp-digi-dtrans-i18n' ); ?></div>
	<?php wp_nonce_field('reset_method_evaluation'); ?>
	<h2 class="wp-digi-alert wp-digi-alert-error wp-digi-center" ><span class="wp-digi-bold" ><?php _e( 'Important note : ', 'wp-digi-dtrans-i18n' ); ?></span><?php _e( 'Be sure to make a backup of your datas before launching datas transfer', 'wp-digi-dtrans-i18n' ); ?></h2>

	<ul class="wp-digi-elements-to-transfer" >
	<?php
		$documents_to_transfer = $documents_transfered = $documents_not_transfered = 0;

		/**	Define if config components have to be transferd or not */
		$main_config_components_are_transfered = null;
		if ( in_array( TABLE_GROUPEMENT, TransferData_class::get()->element_type) ) :
			global $wpdb;
			require( \wpdigi_utils::get_template_part( DIGI_DTRANS_DIR, DIGI_DTRANS_TEMPLATES_MAIN_DIR, "backend", "transfert", "components" ) );
		endif;

		/**	Read the different types	*/
		$element_to_treat = null;
		foreach ( TransferData_class::get()->element_type as $element_type ) :
			/**	Define element to treat by default with first array entry	*/
			$element_to_treat = empty( $element_to_treat ) ? $element_type : $element_to_treat;

			/**	Define the subelement type from main given	*/
			$sub_element_type = '';
			switch ( $element_type ) :
				case TABLE_TACHE:
					$main_element_name = __( 'Tasks', 'wp-digi-dtrans-i18n' );
					$sub_element_type = TABLE_ACTIVITE;
					$sub_element_name = __( 'Sub tasks', 'wp-digi-dtrans-i18n' );
				break;

				case TABLE_GROUPEMENT:
					$main_element_name = __( 'Groups', 'wp-digi-dtrans-i18n' );
					$sub_element_type = TABLE_UNITE_TRAVAIL;
					$sub_element_name = __( 'Work unit', 'wp-digi-dtrans-i18n' );
				break;
			endswitch;

			/**	Count the different eleent that have to be transfered	*/
			$element_to_transfert_count = TransferData_class::get()->get_transfer_progression( $element_type, $sub_element_type );
			$main_element_to_transfer = $element_to_transfert_count[ 'to_transfer' ]->main_element_nb;
				$main_element_transfered = !empty( $element_to_transfert_count[ 'transfered' ] ) && !empty( $element_to_transfert_count[ 'transfered' ][ $element_type ]) ? count( $element_to_transfert_count[ 'transfered' ][ $element_type ] ) : 0;
			$sub_element_to_transfer = $element_to_transfert_count[ 'to_transfer' ]->sub_element_nb;
				$sub_element_transfered = !empty( $element_to_transfert_count[ 'transfered' ] ) && !empty( $element_to_transfert_count[ 'transfered' ][ $sub_element_type ] ) ? count( $element_to_transfert_count[ 'transfered' ][ $sub_element_type ] ) : 0;
			$documents_to_transfer += $element_to_transfert_count[ 'to_transfer' ]->nb_picture + $element_to_transfert_count[ 'to_transfer' ]->nb_document + $element_to_transfert_count[ 'to_transfer' ]->nb_fiches + $element_to_transfert_count[ 'to_transfer' ]->nb_duer;

			/**		*/
			if ( ( $element_to_treat == $element_type ) && ( $main_element_transfered + $sub_element_transfered ) == ( $main_element_to_transfer + $sub_element_to_transfer ) ) {
				$element_to_treat = null;
			}

			/**	Increment document number transfered and not transfered due to error	*/
			$documents_transfered = 0;
			$documents_transfered += !empty( $element_to_transfert_count[ 'transfered' ] ) && !empty( $element_to_transfert_count[ 'transfered' ][ 'picture' ] ) && !empty( $element_to_transfert_count[ 'transfered' ][ 'picture' ][ 'ok' ] ) ? count( $element_to_transfert_count[ 'transfered' ][ 'picture' ][ 'ok' ] ) : 0;
			$documents_transfered += !empty( $element_to_transfert_count[ 'transfered' ] ) && !empty( $element_to_transfert_count[ 'transfered' ][ 'document' ] ) && !empty( $element_to_transfert_count[ 'transfered' ][ 'document' ][ 'ok' ] ) ? count( $element_to_transfert_count[ 'transfered' ][ 'document' ][ 'ok' ] ) : 0;
			$documents_not_transfered = 0;
			$documents_not_transfered += !empty( $element_to_transfert_count[ 'transfered' ] ) && !empty( $element_to_transfert_count[ 'transfered' ][ 'picture' ] ) && !empty( $element_to_transfert_count[ 'transfered' ][ 'picture' ][ 'nok' ] ) ? count( $element_to_transfert_count[ 'transfered' ][ 'picture' ][ 'nok' ] ) : 0;
			$documents_not_transfered += !empty( $element_to_transfert_count[ 'transfered' ] ) && !empty( $element_to_transfert_count[ 'transfered' ][ 'document' ] ) && !empty( $element_to_transfert_count[ 'transfered' ][ 'document' ][ 'nok' ] ) ? count( $element_to_transfert_count[ 'transfered' ][ 'document' ][ 'nok' ] ) : 0;
		?>
		<li>
			<div class="wp-digi-datastransfer-element-type-name wp-digi-datastransfer-element-type-name-<?php echo $element_type; ?><?php if ( $main_element_to_transfer == $main_element_transfered ) : echo ' dashicons-before dashicons-yes'; endif; ?>" ><?php echo $main_element_name; ?></div>
			<ul class="wp-digi-datastransfer-element-type-detail" >
				<li><?php _e( 'Total', 'wp-digi-dtrans-i18n' ); ?> : <span class="wpdigi-to-transfer-element-nb-<?php echo $element_type; ?>" ><?php echo $main_element_to_transfer; ?></span></li>
				<li><?php _e( 'Transfered', 'wp-digi-dtrans-i18n' ); ?> : <span class="wpdigi-transfered-element-nb-<?php echo $element_type; ?>" ><?php echo $main_element_transfered; ?></span></li>
				<li>&nbsp;</li>
			</ul>
		</li>
		<li>
			<div class="wp-digi-datastransfer-element-type-name wp-digi-datastransfer-element-type-name-<?php echo $sub_element_type; ?><?php if ( $sub_element_to_transfer == $sub_element_transfered ) : echo ' dashicons-before dashicons-yes'; endif; ?>" ><?php echo $sub_element_name; ?></div>
			<ul class="wp-digi-datastransfer-element-type-detail" >
				<li><?php _e( 'Total', 'wp-digi-dtrans-i18n' ); ?> : <span class="wpdigi-to-transfer-element-nb-<?php echo $sub_element_type; ?>" ><?php echo $sub_element_to_transfer; ?></span></li>
				<li><?php _e( 'Transfered', 'wp-digi-dtrans-i18n' ); ?> : <span class="wpdigi-transfered-element-nb-<?php echo $sub_element_type; ?>" ><?php echo $sub_element_transfered; ?></span></li>
				<li>&nbsp;</li>
			</ul>
		</li>
	<?php endforeach; ?>

	<?php /**	Display	document transfer informations */	?>
		<li>
			<div class="wp-digi-datastransfer-element-type-name wp-digi-datastransfer-element-type-name-documents<?php if ( $documents_to_transfer == ( $documents_transfered + $documents_not_transfered ) ) : echo ' dashicons-before dashicons-yes'; endif; ?>" ><?php _e( 'Documents', 'wp-digi-dtrans-i18n' ); ?></div>
			<ul class="wp-digi-datastransfer-element-type-detail" >
				<li><?php _e( 'Total', 'wp-digi-dtrans-i18n' ); ?> : <span class="wpdigi-to-transfer-element-nb-documents" ><?php echo $documents_to_transfer; ?></span></li>
				<li><?php _e( 'Transfered', 'wp-digi-dtrans-i18n' ); ?> : <span class="wpdigi-transfered-element-nb-documents" ><?php echo $documents_transfered; ?></span></li>
				<li><?php _e( 'Not Transfered', 'wp-digi-dtrans-i18n' ); ?> : <span class="wpdigi-not-transfered-element-nb-documents" ><?php echo $documents_not_transfered; ?></span></li>
			</ul>
		</li>
	</ul>
	<div class="wp-digi-alert wp-digi-alert-error wp-digi-center" id="wp-digi-transfert-message" ></div>
	<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" id="wpdigi-datastransfer-form" method="post" >
		<input type="hidden" name="action" value="wpdigi-datas-transfert" />
		<input type="hidden" name="sub_action" value="<?php echo ( false === $main_config_components_are_transfered ) ? 'config_components' : ( empty( $element_to_treat ) ? 'doc' : 'element' ); ?>" />
		<input type="hidden" name="wpdigi-nonce" value="<?php echo wp_create_nonce( 'wpdigi-launchtransfer-form' ); ?>" />
		<input type="hidden" name="element_type_to_transfert" value="<?php echo ( empty( $element_to_treat ) ? $this->element_type[ 0 ] :  $element_to_treat ); ?>" />
		<input type="hidden" name="number_per_page" value="<?php echo DIGI_DTRANS_NB_ELMT_PER_PAGE; ?>" />

		<button class="wp-digi-bton wp-digi-bton-first alignright" ><?php _e( 'Launch transfer', 'wp-digi-dtrans-i18n' ); ?></button>
	</form>

</div>