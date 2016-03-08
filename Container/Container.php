<?php

namespace Xeeeveee\Core\Container;

use Xeeeveee\Core\Exceptions\ContainerOverrideException;
use Xeeeveee\Core\Exceptions\NotStringException;
use Xeeeveee\Core\Utility\Singleton;

class Container extends Singleton implements ContainerInterface {

	/**
	 * @var array
	 *
	 * The container
	 */
	protected $container = [ ];

	/**
	 * Add an item to the container
	 *
	 * @param $key
	 * @param $value
	 * @param bool|false $force
	 *
	 * @throws NotStringException
	 * @throws ContainerOverrideException
	 */
	public function add( $key, $value, $force = false ) {
		if ( ! is_string( $key ) ) {
			throw new NotStringException( 'Parameter "$key" is not of type string in ' . get_class( $this ) );
		}

		if ( isset( $this->container[ $key ] ) && ! $force ) {
			throw new ContainerOverrideException( 'Item ' . $key . ' already exists' );
		} else {
			$this->container[ $key ] = $value;
		}
	}

	/**
	 * Get a specific item from the container
	 *
	 * @param $key
	 *
	 * @return bool
	 * @throws NotStringException
	 */
	public function get( $key ) {
		if ( ! is_string( $key ) ) {
			throw new NotStringException( 'Parameter "$key" is not of type string in ' . get_class( $this ) );
		}

		if ( isset( $this->container[ $key ] ) ) {
			return $this->container[ $key ];
		}

		return false;
	}

	/**
	 * Checks if a key already exists within the container
	 * 
	 * @param $key
	 *
	 * @return bool
	 * @throws NotStringException
	 */
	public function exists( $key ) {
		if ( ! is_string( $key ) ) {
			throw new NotStringException( 'Parameter "$key" is not of type string in ' . get_class( $this ) );
		}

		return ( isset( $this->container[ $key ] ) );
	}

	/**
	 * Returns the entire container
	 *
	 * @return array
	 */
	public function getAll() {
		return $this->container;
	}

	/**
	 * Removes an item from the container
	 *
	 * @param $key
	 *
	 * @throws NotStringException
	 */
	public function remove( $key ) {
		if ( ! is_string( $key ) ) {
			throw new NotStringException( '"$key" is not of type string in ' . get_class( $this ) );
		}

		if ( isset( $this->container[ $key ] ) ) {
			unset( $this->container[ $key ] );
		}
	}

	/**
	 * Clears the container
	 */
	public function clear() {
		$this->container = [ ];
	}
}