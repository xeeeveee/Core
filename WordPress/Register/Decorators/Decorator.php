<?php

namespace Xeeeveee\Core\WordPress\Register\Decorators;

use WP_Post;

abstract class Decorator implements DecoratorInterface {

	/**
	 * @var string
	 *
	 * The action to respond to
	 */
	protected $post_type;

	/**
	 * @var \WP_Post
	 */
	protected $original;

	/**
	 * Create a link to the original by reference
	 *
	 * @param WP_Post $original
	 */
	public function __construct( WP_Post &$original ) {
		$this->original = $original;

		// TODO: Attach to WP_Post objects based post type & filters
	}

	/**
	 * Return the decorated property
	 *
	 * Looks for a method called get_{property} to return the value, if one doesn't exist, will check the origional for
	 * the property, failing this null is returned
	 *
	 * @param $property
	 *
	 * @return mixed|null
	 */
	public function __get( $property ) {

		$method = 'get_' . $property;
		if ( method_exists( $this, $method ) ) {
			return $this->{$method}();
		} elseif ( isset( $this->original->{$property} ) ) {
			return $this->original->{$property};
		}

		return null;
	}
}
