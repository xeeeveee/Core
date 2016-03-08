<?php

namespace Xeeeveee\Core\Utility;

abstract class Tag implements TagInterface {

	/**
	 * Reserved attributes
	 *
	 * These will be ignored if added as attributes
	 *
	 * @var array
	 */
	protected $reserved_attributes = [ ];

	/**
	 * The attributes to be added to the html tag
	 *
	 * @var array
	 */
	protected $attributes = [ ];

	/**
	 * Set the reserved attributes
	 *
	 * @param array $attributes
	 *
	 * @return $this
	 */
	protected function set_reserved_attributes( array $attributes ) {
		$this->reserved_attributes = $attributes;

		return $this;
	}

	/**
	 * Set the reserved attributes
	 *
	 * @param array $attribute
	 *
	 * @return $this
	 */
	protected function add_reserved_attribute( $attribute ) {
		$this->reserved_attributes[] =  $attribute;

		return $this;
	}

	/**
	 * Set the reserved attributes
	 *
	 * @param array $attributes
	 *
	 * @return $this
	 */
	protected function add_reserved_attributes( array $attributes ) {
		$this->reserved_attributes = array_merge( $this->reserved_attributes, $attributes );

		return $this;
	}

	/**
	 * Get the reserved attributes
	 *
	 * @return array
	 */
	public function get_reserved_attributes() {
		return $this->reserved_attributes;
	}

	/**
	 * Set the attributes
	 *
	 * This difference from add_attributes in that is will erase all the existing attributes and assign the new ones
	 *
	 * @param array $attributes the attributes to add
	 *
	 * @return $this
	 */
	public function set_attributes( array $attributes = [ ] ) {
		$this->clear_attributes();

		foreach ( $attributes as $key => $val ) {
			if ( ! in_array( $key, $this->reserved_attributes ) && is_array( $val ) ) {
				$this->attributes[ $key ] = $val;
			}
		}

		return $this;
	}

	/**
	 * Add attributes
	 *
	 * This differs from set_attributes in that is does not erase the attributes before adding the new ones
	 *
	 * @param array $attributes
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function add_attributes( array $attributes = [ ], $override = true ) {
		foreach ( $attributes as $key => $val ) {
			if ( ! in_array( $key, $this->reserved_attributes ) && is_array( $val ) ) {
				if ( $override == false ) {
					if ( isset( $this->attributes[ $key ] ) ) {
						continue;
					}
				}

				$this->attributes[ $key ] = $val;
			}
		}

		return $this;
	}

	/**
	 * Get the attributes
	 *
	 * @return array
	 */
	public function get_attributes() {
		return $this->attributes;
	}

	/**
	 * Clears the existing attributes
	 *
	 * @return $this
	 */
	public function clear_attributes() {
		$this->attributes = [ ];

		return $this;
	}

	/**
	 * Add a class to the tag
	 *
	 * @param $class
	 *
	 * @return $this
	 */
	public function add_class( $class ) {
		if ( is_string( $class ) && ! $this->class_exists( $class ) ) {
			$this->attributes['class'][] = $class;
		}

		return $this;
	}

	/**
	 * Add multiple classes to the tag
	 *
	 * @param array $classes
	 *
	 * @return $this
	 */
	public function add_classes( array $classes = [ ] ) {
		foreach ( $classes as $class ) {
			if ( is_string( $class ) && ! $this->class_exists( $class ) ) {
				$this->attributes['class'][] = $class;
			}
		}

		return $this;
	}

	/**
	 * Set a single class on the tag
	 *
	 * This will remove any existing classes
	 *
	 * @param $class
	 *
	 * @return $this
	 */
	public function set_class( $class ) {
		if ( is_string( $class ) ) {
			$this->clear_classes();
			$this->attributes['class'][] = $class;
		}

		return $this;
	}

	/**
	 * Sets the class on the tag
	 *
	 * This will remove any existing classes
	 *
	 * @param array $classes
	 *
	 * @return $this
	 */
	public function set_classes( array $classes = [ ] ) {
		$this->clear_classes();
		foreach ( $classes as $class ) {
			if ( is_string( $class ) && ! $this->class_exists( $class ) ) {
				$this->attributes['class'][] = $class;
			}
		}

		return $this;
	}

	/**
	 * Check if a class already exists on the tag
	 *
	 * @param $class
	 *
	 * @return bool
	 */
	public function class_exists( $class ) {
		return ( is_string( $class ) && in_array( $class, $this->attributes['class'] ) );
	}

	/**
	 * Remove a single class from the tag
	 *
	 * @param $class
	 *
	 * @return $this
	 */
	public function remove_class( $class ) {
		if ( is_string( $class ) && ( $key = array_search( $class, $this->attributes['class'] ) ) !== false ) {
			unset( $this->attributes['class'][ $key ] );
		}

		return $this;
	}

	/**
	 * Remove multiple classes from the tag
	 *
	 * @param array $classes
	 *
	 * @return $this
	 */
	public function remove_classes( array $classes = [ ] ) {
		foreach ( $classes as $class ) {
			if ( is_string( $class ) && ( $key = array_search( $class, $this->attributes['class'] ) ) !== false ) {
				unset( $this->attributes['class'][ $key ] );
			}
		}

		return $this;
	}

	/**
	 * Removes all classes from the tag
	 */
	public function clear_classes() {
		$this->attributes['class'] = [ ];
	}
}