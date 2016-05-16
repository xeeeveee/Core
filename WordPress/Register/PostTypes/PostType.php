<?php

namespace Xeeeveee\Core\WordPress\Register\PostTypes;

use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\WordPress\Register\Types\Type;

abstract class PostType extends Type implements PostTypeInterface {

	/**
	 * @var string
	 *
	 * The post type identifier
	 */
	protected $identifier = '';

	/**
	 * @var array
	 *
	 * The default values for the labels
	 */
	protected $default_labels = [
		'name'               => '%%ID%%s',
		'singular_name'      => '%%ID%%',
		'menu_name'          => '%%ID%%s',
		'name_admin_bar'     => '%%ID%%',
		'add_new'            => 'Add New %%ID%%',
		'add_new_item'       => 'Add New %%ID%%',
		'new_item'           => 'New %%ID%%',
		'edit_item'          => 'Edit %%ID%%',
		'view_item'          => 'View %%ID%%',
		'all_items'          => 'All %%ID%%s',
		'search_items'       => 'Search %%ID%%s',
		'parent_item_colon'  => 'Parent %%ID%%s:',
		'not_found'          => 'No %%ID%%s found.',
		'not_found_in_trash' => 'No %%ID%%s found in Trash.',
	];

	/**
	 * @var array
	 *
	 * The default post type configuration
	 */
	protected $default_configuration = [
		'description'     => '',
		'public'          => true,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'query_var'       => true,
		'capability_type' => 'post',
		'has_archive'     => true,
		'hierarchical'    => false,
		'menu_position'   => null,
		'supports'        => [
			'title',
			'editor'
		]
	];

	/**
	 * Registers the post type with WordPress
	 */
	public function register() {
		$args           = $this->merged_configuration;
		$args['labels'] = $this->merged_labels;

		register_post_type( $this->identifier, $args );
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
			'name'               => _x( $labels['name'], 'post type general name', $this->text_domain ),
			'singular_name'      => _x( $labels['singular_name'], 'post type singular name', $this->text_domain ),
			'menu_name'          => _x( $labels['menu_name'], 'admin menu', $this->text_domain ),
			'name_admin_bar'     => _x( $labels['name_admin_bar'], 'add new on admin bar', $this->text_domain ),
			'add_new'            => _x( $labels['add_new'], $this->text_domain ),
			'add_new_item'       => __( $labels['add_new_item'], $this->text_domain ),
			'new_item'           => __( $labels['new_item'], $this->text_domain ),
			'edit_item'          => __( $labels['edit_item'], $this->text_domain ),
			'view_item'          => __( $labels['view_item'], $this->text_domain ),
			'all_items'          => __( $labels['all_items'], $this->text_domain ),
			'search_items'       => __( $labels['search_items'], $this->text_domain ),
			'parent_item_colon'  => __( $labels['parent_item_colon'], $this->text_domain ),
			'not_found'          => __( $labels['not_found'], $this->text_domain ),
			'not_found_in_trash' => __( $labels['not_found_in_trash'], $this->text_domain )
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
		$configuration['description'] = __( $configuration['description'], $this->text_domain );

		return $configuration;
	}
}