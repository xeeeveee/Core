<?php

namespace Xeeeveee\Core\Utility;

abstract class Singleton {

	/**
	 * @var $this
	 *
	 * The reference to a *Singleton* instance of this class
	 */
	protected static $instances;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return $this
	 */
	final public static function get_instance() {
		$class = get_called_class();

		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new $class;
		}

		return self::$instances[ $class ];
	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct() {
	}

	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Singleton* instance.
	 */
	private function __clone() {
	}

	/**
	 * Private unserialize method to prevent unserializing of the *Singleton*
	 * instance.
	 */
	private function __wakeup() {
	}
}