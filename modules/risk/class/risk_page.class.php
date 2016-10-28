<?php
/**
 * La classe qui contrôle la page "Tous les riques"
 *
 * @package Evarisk\Plugin
 */

namespace digi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * La classe qui contrôle la page "Tous les riques"
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @version 1.1.0.0
 */
class Risk_page_class extends singleton_util {
	/**
	 * Le constructeur obligatoirement pour utiliser la classe Singleton_util
	 *
	 * @return void nothing
	 */
	protected function construct() {}

	/**
	 * Affiches le contenu de la page "Tous les risques"
	 *
	 * @return void nothing
	 */
	public function display() {
		view_util::exec( 'risk', 'page/main' );
	}

	/**
	 * Charges tous les risques de l'application, ajoutes ses parents dans l'objet, et les tries selon leur cotation.
	 * Si $_GET['order_key'] et $_GET['order_type'] existent, le trie se fait selon ses critères.
	 *
	 * @return void nothing
	 */
	public function display_risk_list() {
		$risk_list = risk_class::g()->get( array(), array( 'comment', 'evaluation_method', 'evaluation', 'danger_category', 'danger' ) );

		$order_key = ! empty( $_GET['order_key'] ) ? $_GET['order_key'] : 'equivalence';
		$order_type = ! empty( $_GET['order_type'] ) ? $_GET['order_type'] : 'asc';
		$url_ref_order = '&order_key=equivalence&order_type=';
		$url_ref_order .= ( 'asc' === $order_type ) ? 'desc' : 'asc';

		if ( ! empty( $risk_list ) ) {
			foreach ( $risk_list as $key => $element ) {
				$risk_list[ $key ]->parent = society_class::g()->show_by_type( $element->parent_id );
				if ( 'digi -group' === $risk_list[ $key ]->parent->type ) {
					$risk_list[ $key ]->parent_group = $risk_list[ $key ]->parent;
				} else {
					$risk_list[ $key ]->parent_workunit = $risk_list[ $key ]->parent;
					$risk_list[ $key ]->parent_group = society_class::g()->show_by_type( $risk_list[ $key ]->parent_workunit->parent_id );
				}
			}
		}

		unset( $element );

		if ( count( $risk_list ) > 1 ) {
			if ( 'equivalence' === $order_key ) {
				if ( 'asc' === $order_type ) {
					usort( $risk_list, function( $a, $b ) {
						if ( $a->evaluation[0]->risk_level['equivalence'] === $b->evaluation[0]->risk_level['equivalence'] ) {
							return 0;
						}
						return ( $a->evaluation[0]->risk_level['equivalence'] > $b->evaluation[0]->risk_level['equivalence'] ) ? -1 : 1;
					} );
				} else {
					usort( $risk_list, function( $a, $b ) {
						if ( $a->evaluation[0]->risk_level['equivalence'] === $b->evaluation[0]->risk_level['equivalence'] ) {
							return 0;
						}
						return ( $a->evaluation[0]->risk_level['equivalence'] < $b->evaluation[0]->risk_level['equivalence'] ) ? -1 : 1;
					} );
				}
			}
		}

		view_util::exec( 'risk', 'page/list', array( 'risk_list' => $risk_list, 'url_ref_order' => $url_ref_order ) );
	}
}