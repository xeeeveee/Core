<?php

namespace Xeeeveee\Core\WordPress\Register\Types;

use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Exceptions\EmptyOrUndefinedException;
use Xeeeveee\Core\Exceptions\NotStringException;
use Xeeeveee\Core\Utility\Singleton;

abstract class Type extends Singleton implements TypeInterface {

	use ConfigurationTrait;

	/**
	 * @var string
	 *
	 * The identifier
	 */
	protected $identifier = '';

	/**
	 * @var array
	 *
	 * The labels
	 */
	protected $labels = [ ];

	/**
	 * @var array
	 *
	 * The default values for the labels
	 */
	protected $default_labels = [ ];

	/**
	 * @var array
	 *
	 * The merged labels
	 */
	protected $merged_labels = [ ];

	/**
	 * @var array
	 *
	 * The post type configuration
	 */
	protected $configuration = [ ];

	/**
	 * @var array
	 *
	 * The default configuration
	 */
	protected $default_configuration = [ ];

	/**
	 * @var array
	 *
	 * The merged configuration
	 */
	protected $merged_configuration = [ ];


	/**
	 * Registers the required actions with WordPress
	 *
	 * @throws EmptyOrUndefinedException
	 * @throws NotStringException
	 */
	protected function __construct() {

		if ( ! isset( $this->identifier ) || empty( $this->identifier ) ) {
			throw new EmptyOrUndefinedException( 'The "identifier" property must be set in class ' . get_class( $this ) );
		}

		if ( ! is_string( $this->identifier ) ) {
			throw new NotStringException( 'The "$identifier" property must be of type string in class ' . get_class( $this ) );
		}

		$this->set_labels();
		$this->set_configuration();

		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Registers the type with WordPress
	 */
	abstract public function register();

	/**
	 * Sets the merged_labels property
	 */
	private function set_labels() {

		$this->default_labels = array_map( function ( $value ) {
			return $this->replace_placeholders( $value );
		}, $this->default_labels );

		$labels = array_merge( $this->default_labels, $this->labels );
		$this->merged_labels = $this->translate_labels( $labels );
	}

	/**
	 * Sets the merged_configuration property
	 */
	private function set_configuration() {
		$configuration = array_merge( $this->default_configuration, $this->configuration );
		$configuration = $this->translate_configuration( $configuration );

		$this->merged_configuration = $configuration;
	}

	/**
	 * Replaces place holders with the $identifier
	 *
	 * @param $string
	 *
	 * @return mixed
	 */
	private function replace_placeholders( $string ) {
		return str_replace( '%%ID%%', ucfirst( $this->identifier ), $string );
	}

	/**
	 * Translate the labels
	 *
	 * @param $labels
	 */
	abstract protected function translate_labels( $labels );

	/**
	 * Translate the configuration
	 *
	 * @param $configuration
	 *
	 * @return mixed
	 */
	abstract protected function translate_configuration( $configuration );
}