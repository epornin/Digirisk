<?php
/**
 * Gestion de l'affichage d'un DUER
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @version 6.1.9.0
 * @copyright 2015-2016 Evarisk
 * @package document
 * @subpackage view
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<li class='wp-digi-list-item wp-digi-risk-item'>
	<span><?php echo $element->unique_identifier; ?></span>
	<span class="padded"><?php echo esc_html( mysql2date( 'd/m/Y', current_time( 'mysql', 0 ), true ) ); ?></span>
	<span class="padded"><?php echo esc_html( mysql2date( 'd/m/Y', current_time( 'mysql', 0 ), true ) ); ?></span>
	<span class="padded"><?php echo esc_html( $element->document_meta['destinataireDUER'] ); ?></span>

	<span class="padded">
		<span class="span-content-methodology"><?php echo esc_html( $element->document_meta['methodologie'] ); ?></span>
		<span data-parent="wp-digi-risk-item"
					data-target="popup"
					data-cb-object="DUER"
					data-cb-func="fill_textarea_in_popup"
					data-title="Édition de la méthodologie"
					data-src="methodology"
					class="open-popup dashicons dashicons-media-default"></span>
	</span>

	<span class="padded">
		<span class="span-content-sources"><?php echo esc_html( $element->document_meta['sources'] ); ?></span>
		<span data-parent="wp-digi-risk-item"
					data-target="popup"
					data-cb-object="DUER"
					data-cb-func="fill_textarea_in_popup"
					data-title="Édition de la source"
					data-src="sources"
					class="open-popup dashicons dashicons-media-default"></span>
		</span>

	<span class="padded">
		<span class="span-content-notes-importantes"><?php echo esc_html( $element->document_meta['remarqueImportante'] ); ?></span>
		<span data-parent="wp-digi-risk-item"
					data-target="popup"
					data-cb-object="DUER"
					data-cb-func="fill_textarea_in_popup"
					data-title="Édition de la note importante"
					data-src="notes-importantes"
					class="open-popup dashicons dashicons-media-default"></span>
	</span>

	<span class="padded"><?php echo esc_html( $element->document_meta['dispoDesPlans'] ); ?></span>
	<span class="padded flex-tmp">
		<a href="<?php echo document_class::g()->get_document_path( $element ); ?>" class="wp-digi-bton-fifth" ><?php _e( 'Download', 'digirisk' ); ?></a>
		<a href="<?php echo document_class::g()->get_document_path( $element ); ?>" class="wp-digi-bton-sixth" ><?php _e( 'Zip', 'digirisk' ); ?></a>
		<a class="wp-digi-action wp-digi-action-delete dashicons dashicons-no-alt" data-parent-id="<?php echo $element->parent_id; ?>" data-id="<?php echo $element->id; ?>" href="#"></a>
	</span>
</li>
