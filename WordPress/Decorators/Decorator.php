<?php

namespace Xeeeveee\Core\WordPress\Decorators;

abstract class Decorator implements DecoratorInterface {

	/**
	 * @var object
	 */
	protected $original = null;

	/**
	 * Create a link to the original by reference
	 *
	 * @param object $original
	 */
	public function __construct( &$original ) {
		$this->original = $original;
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
}
