<?php

namespace Xeeeveee\Core\WordPress\Register\Taxonomies;

use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\WordPress\Register\Types\Type;

abstract class Taxonomy extends Type implements TaxonomyInterface {

	/**
	 * @var string
	 *
	 * The post type identifier
	 */
	protected $identifier = '';

	/**
	 * @var array
	 *
	 * The post types to register the taxonomy against
	 */
	protected $post_types = [ ];

	/**
	 * @var array
	 *
	 * The default values for the labels
	 */
	protected $default_labels = [
		'name'                       => '%%ID%%s',
		'singular_name'              => '%%ID%%',
		'search_items'               => 'Search %%ID%%s',
		'all_items'                  => 'All %%ID%%s',
		'parent_item'                => 'Parent %%ID%%',
		'parent_item_colon'          => 'Parent %%ID%%:',
		'edit_item'                  => 'Edit %%ID%%',
		'update_item'                => 'Update %%ID%%',
		'add_new_item'               => 'Add new %%ID%%',
		'new_item_name'              => 'New %%ID%%s Name',
		'separate_items_with_commas' => 'Separate %%ID%%s Name with commas',
		'add_or_remove_items'        => 'Add or remove %%ID%%s',
		'choose_from_most_used'      => 'Choose from the most used %%ID%%s',
		'not_found'                  => 'No %%ID%%s found.',
		'menu_name'                  => '%%ID%%s',
	];

	/**
	 * @var array
	 *
	 * The default post type configuration
	 */
	protected $default_configuration = [
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
	];

	/**
	 * Registers the post type with WordPress
	 */
	public function register() {
		$args           = $this->merged_configuration;
		$args['labels'] = $this->merged_labels;
		$post_types     = apply_filters( $this->filter_base . '/taxonomies/' . get_class( $this ) . '/post_types', $this->post_types );

		register_taxonomy( $this->identifier, $post_types, $args );
	}

	/**
	 * Translate the labels
	 *
	 * @param $labels
	 *
	 * @return array
	 */
	protected function translate_labels( $labels ) {
		return [
			'name'                       => _x( $labels['name'], 'taxonomy general name', $this->text_domain ),
			'singular_name'              => _x( $labels['singular_name'], 'taxonomy singular name', $this->text_domain ),
			'search_items'               => __( $labels['search_items'], $this->text_domain ),
			'popular_items'              => __( $labels['popular_items'], $this->text_domain ),
			'name_admin_bar'             => __( $labels['name_admin_bar'], $this->text_domain ),
			'all_items'                  => __( $labels['all_items'], $this->text_domain ),
			'parent_item'                => __( $labels['parent_item'], $this->text_domain ),
			'parent_item_colon'          => __( $labels['parent_item_colon'], $this->text_domain ),
			'edit_item'                  => __( $labels['edit_item'], $this->text_domain ),
			'update_item'                => __( $labels['update_item'], $this->text_domain ),
			'add_new_item'               => __( $labels['add_new_item'], $this->text_domain ),
			'new_item_name'              => __( $labels['new_item_name'], $this->text_domain ),
			'separate_items_with_commas' => __( $labels['separate_items_with_commas'], $this->text_domain ),
			'add_or_remove_items'        => __( $labels['add_or_remove_items'], $this->text_domain ),
			'choose_from_most_used'      => __( $labels['choose_from_most_used'], $this->text_domain ),
			'not_found'                  => __( $labels['not_found'], $this->text_domain ),
			'menu_name'                  => __( $labels['menu_name'], $this->text_domain )
		];
	}

	/**
	 * Translate the configuration
	 *
	 * @param $configuration
	 *
	 * @return mixed
	 */
	protected function translate_configuration( $configuration ) {
		return $configuration;
	}
}