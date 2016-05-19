<?php

namespace Xeeeveee\Core\WordPress\Register\Decorators;

use ReflectionClass;
use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Utility\Singleton;

abstract class Decorator extends Singleton implements DecoratorInterface {

	use ConfigurationTrait;

	/**
	 * @var string
	 */
	protected $post_type = '';

	/**
	 * @var string
	 */
	protected $decorator = '';

	/**
	 * @var bool
	 */
	protected $meta = false;

	/**
	 * @var bool
	 */
	protected $term = false;

	/**
	 * @var int
	 */
	protected $priority = 20;

	/**
	 * Register the decorator
	 */
	protected function __construct() {
		if ( ! empty( $this->post_type ) && is_string( $this->post_type ) && ! $this->meta && ! $this->term ) {
			add_filter( $this->filter_base . 'prepare/post/' . $this->post_type . '/decorator',
					[ $this, 'attach' ], $this->priority, 2 );
		}

		if ( $this->meta === true || $this->term === true ) {
			$types = [ ];

			if ( $this->meta === true ) {
				$types[] = 'meta';
			}

			if ( $this->term === true ) {
				$types[] = 'term';
			}

			foreach ( $types as $type ) {
				if ( ! empty( $this->post_type ) && is_string( $this->post_type ) ) {
					add_filter( $this->filter_base . 'decorators/' . $type . '/' . $this->post_type, [
							$this,
							'set'
					], $this->priority, 2 );
				} else {
					add_filter( $this->filter_base . 'decorators/' . $type . '/global', [
							$this,
							'set'
					], $this->priority, 2 );
				}
			}
		}
	}

	/**
	 * Return a new instance of the decorator
	 *
	 * @param $decorator
	 * @param $original
	 *
	 * @return mixed
	 */
	public function attach( $decorator, $original ) {
		if ( ! empty( $this->decorator ) && is_string( $this->decorator ) && is_object( $original ) ) {
			if ( class_exists( $this->decorator ) ) {
				$reflectionClass = new ReflectionClass( $this->decorator );
				if ( $reflectionClass->IsInstantiable() ) {
					return new $this->decorator( $original );
				}
			}
		}

		return $decorator;
	}

	/**
	 * Set a custom term or meta decorator
	 *
	 * @param $decorator
	 * @param $post
	 *
	 * @return string
	 */
	public function set( $decorator, $post ) {
		return $this->decorator;
	}
}
