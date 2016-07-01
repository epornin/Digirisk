<?php if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Fichier contenant les utilitaires pour la gestion des catégories de préconisation et les préconisations / File with all utilities for managing recommendation categories and recommendations
 *
 * @author Evarisk development team <dev@evarisk.com>
 * @version 6.0
 */

/**
 * Classe contenant les utilitaires pour la gestion des catégories de préconisations / Class with all utilities for managing recommendation categories
 *
 * @author Evarisk development team <dev@evarisk.com>
 * @version 6.0
 */
class recommendation_category_class extends term_class {

	/**
	 * Nom du modèle à utiliser / Model name to use
	 * @var string
	 */
	protected $model_name   = 'wpdigi_recommendation_category_mdl_01';
	/**
	 * Type de l'élément dans wordpress / Wordpress element type
	 * @var string
	 */
	protected $taxonomy    	= 'digi-recommendation-category';
	/**
	 * Nom du champs (meta) de stockage des données liées / Name of field (meta) for linked datas storage
	 * @var string
	 */
	protected $meta_key    	= '_wpdigi_recommendationcategory';

	/**	Défini la route par défaut permettant d'accèder à l'élément depuis WP Rest API  / Define the default route for accessing to element from WP Rest API	*/
	protected $base = 'digirisk/recommendation-category';
	protected $version = '0.1';

	public $element_prefix = 'R';

	/* PRODEST:
	{
		"name": "__construct",
		"description": "Instanciation des outils pour la gestion des catégories de préconisation et les préconisations / Instanciate utilities for managing recommendation categories and recommendations",
		"type": "function",
		"check": false,
		"author":
		{
			"email": "dev@evarisk.com",
			"name": "Alexandre T"
		},
		"version": 1.0
	}
	*/
	protected function construct() {
		/**	Inclusion du modèle / Include model	*/
		include_once( RECOMMENDATION_PATH . 'model/recommendation_category.model.01.php' );

		/**	Define taxonomy for recommendation categories	*/
		add_action( 'init', array( $this, 'recommendation_category_type' ), 0 );
	}

	/* PRODEST:
	{
		"name": "recommendation_category_type",
		"description": "Création du type d'élément interne a wordpress pour gérer les catégories de danger / Create wordpress element type for managing danger categories",
		"type": "function",
		"check": false,
		"author":
		{
			"email": "dev@evarisk.com",
			"name": "Alexandre T"
		},
		"version": 1.0
	}
	*/
	function recommendation_category_type() {
		$labels = array(
			'name'              => __( 'Recommendation categories', 'digirisk' ),
			'singular_name'     => __( 'Recommendation category', 'digirisk' ),
			'search_items'      => __( 'Search recommendation categories', 'digirisk' ),
			'all_items'         => __( 'All recommendation categories', 'digirisk' ),
			'parent_item'       => null,
			'parent_item_colon' => null,
			'edit_item'         => __( 'Edit recommendation category', 'digirisk' ),
			'update_item'       => __( 'Update recommendation category', 'digirisk' ),
			'add_new_item'      => __( 'Add New recommendation category', 'digirisk' ),
			'new_item_name'     => __( 'New recommendation category Name' , 'digirisk'),
			'menu_name'         => __( 'Recommendation category', 'digirisk' ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'recommendation-category' ),
		);

		register_taxonomy( $this->taxonomy, array( 'risk', 'societies' ), $args );
	}

}

recommendation_category_class::get();