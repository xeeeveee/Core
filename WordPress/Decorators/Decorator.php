<?php

namespace Xeeeveee\Core\WordPress\Decorators;

use WP_Post;
use Xeeeveee\Core\Configuration\ConfigurationTrait;

abstract class Decorator implements DecoratorInterface {

	use ConfigurationTrait;

	/**
	 * @var object
	 */
	protected $original = null;

	/**
	 * @var string
	 */
	protected $meta_decorator = '';

	/**
	 * @var string
	 */
	protected $term_decorator = '';

	/**
	 * Create a link to the original by reference
	 *
	 * @param object $original
	 */
	public function __construct( &$original ) {
		$this->original = $original;

		if ( $this->original instanceof WP_Post ) {
			$this->set_meta_decorator();
			$this->set_term_decorator();
		}
	}

	/**
	 * Return the decorated property
	 *
	 * Looks for a method called get_{property} to return the value, if one doesn't exist, will check the original for
	 * the property, failing this null is returned
	 *
	 * @param $property
	 *
	 * @return mixed|null
	 */
	public function __get( $property ) {

		$method = 'get_' . $property;
		if ( method_exists( $this, $method ) ) {
			return $this->{$method}( $property );
		} elseif ( is_object( $this->original ) && isset( $this->original->{$property} ) ) {
			return $this->original->{$property};
		}

		return null;
	}

	/**
	 * Set the meta decorator
	 *
	 * Allows the meta decorator to be overwritten
	 */
	protected function set_meta_decorator() {
		$this->meta_decorator = apply_filters( $this->filter_base . 'decorators/meta/global', $this->meta_decorator, $this->original );
		$this->meta_decorator = apply_filters( $this->filter_base . 'decorators/meta/' . $this->original->post_type, $this->meta_decorator, $this->original );
	}

	/**
	 * Set the term decorator
	 *
	 * Allows the term decorator to be overwritten
	 */
	protected function set_term_decorator() {
		$this->meta_decorator = apply_filters( $this->filter_base . 'decorators/term/global', $this->meta_decorator, $this, $this->original );
		$this->meta_decorator = apply_filters( $this->filter_base . 'decorators/term/' . $this->original->post_type, $this->meta_decorator, $this->original );
	}
}
