<?php
/**
 * Affiches la liste des causeries
 *
 * @author    Evarisk <dev@evarisk.com>
 * @since     6.6.0
 * @version   6.6.0
 * @copyright 2018 Evarisk.
 * @package   DigiRisk
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<h2><?php esc_html_e( 'Causerie à démarrer', 'digirisk' ); ?></h2>

<table class="table start-causerie">
	<thead>
		<tr>
			<th class="w50 padding"><?php esc_html_e( 'Ref', 'digirisk' ); ?>.</th>
			<th class="w50 padding"><?php esc_html_e( 'Photo', 'digirisk' ); ?>.</th>
			<th class="w50 padding"><?php esc_html_e( 'Cat', 'digirisk' ); ?>.</th>
			<th class="padding"><?php esc_html_e( 'Titre et description', 'digirisk' ); ?>.</th>
			<th class="w50"></th>
		</tr>
	</thead>

	<tbody>
		<?php
		if ( ! empty( $causeries ) ) :
			foreach ( $causeries as $causerie ) :
				$causerie = apply_filters( 'digi_add_custom_key_to_causerie', $causerie );
				\eoxia\View_Util::exec( 'digirisk', 'causerie', 'start/list-item', array(
					'causerie' => $causerie,
					'started'  => false,
				) );
			endforeach;
		else :
			?>
			<tr>
				<td colspan="5" style="text-align: center;"><?php esc_html_e( 'Aucune causerie à démarrer', 'digirisk' ); ?></td>
			</tr>
			<?php
		endif;
		?>
	</tbody>
</table>

<h2><?php esc_html_e( 'Causerie en cours', 'digirisk' ); ?></h2>

<table class="table final-causerie">
	<thead>
		<tr>
			<th class="w50 padding"><?php esc_html_e( 'Ref', 'digirisk' ); ?>.</th>
			<th class="w50 padding"><?php esc_html_e( 'Photo', 'digirisk' ); ?>.</th>
			<th class="w50 padding"><?php esc_html_e( 'Cat', 'digirisk' ); ?>.</th>
			<th class="padding"><?php esc_html_e( 'Titre et description', 'digirisk' ); ?>.</th>
			<th class="w50"></th>
		</tr>
	</thead>

	<tbody>
		<?php
		if ( ! empty( $causeries_intervention ) ) :
			foreach ( $causeries_intervention as $causerie_intervention ) :
				$causerie_intervention = apply_filters( 'digi_add_custom_key_to_causerie', $causerie_intervention );
				\eoxia\View_Util::exec( 'digirisk', 'causerie', 'start/list-item', array(
					'causerie' => $causerie_intervention,
					'started'  => true,
				) );
			endforeach;
		else :
			?>
			<tr>
				<td colspan="5" style="text-align: center;"><?php esc_html_e( 'Aucune causerie en cours', 'digirisk' ); ?></td>
			</tr>
			<?php
		endif;
		?>
	</tbody>
</table>
