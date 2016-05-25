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
	protected $path = '';

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
		} else {
			if(!empty($this->path) && is_string($this->path)) {
				$parts = explode( '.', $this->path );
				$value = $this->original;

				foreach ( $parts as $part ) {
					if ( isset( $value->{$part} ) ) {
						$value = $value->{$part};
					} else {
						return null;
					}
				}

				if ( is_object( $value ) && isset( $value->{$property} ) ) {
					return $value->{$property};
				}

			} elseif ( is_object( $this->original ) && isset( $this->original->{$property} ) ) {
				return $this->original->{$property};
			}
		}

		return null;
	}

	/**
	 * Check if a magic method isset
	 *
	 * Required to use isset, empty and similar functions on values accesed via magic methods
	 *
	 * @param $property
	 *
	 * @return bool
	 */
	public function __isset( $property ) {
		$method = 'get_' . $property;
		if ( method_exists( $this, $method ) ) {
			return true;
		} else {
			if(!empty($this->path) && is_string($this->path)) {
				$parts = explode( '.', $this->path );
				$value = $this->original;

				foreach ( $parts as $part ) {
					if ( isset( $value->{$part} ) ) {
						$value = $value->{$part};
					} else {
						return false;
					}
				}

				if ( is_object( $value ) && isset( $value->{$property} ) ) {
					return true;
				}

			} elseif ( is_object( $this->original ) && isset( $this->original->{$property} ) ) {
				return true;
			}
		}

		return false;
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
