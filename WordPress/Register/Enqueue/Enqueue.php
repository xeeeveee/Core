<?php

namespace Xeeeveee\Core\WordPress\Register\Enqueue;

use ReflectionClass;
use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Utility\Singleton;

abstract class Enqueue extends Singleton implements EnqueueInterface {

	use ConfigurationTrait;

	/**
	 * @var string
	 *
	 * The handle to assign the enqueue
	 */
	protected $handle = '';

	/**
	 * @var string
	 *
	 * The resource (Filename) to enqueue
	 */
	protected $resource = '';

	/**
	 * @var string|boolean
	 *
	 * The source of the file to enqueue
	 */
	protected $source = false;

	/**
	 * @var bool
	 *
	 * Whether to enqueue on the frontend
	 */
	protected $frontend = true;

	/**
	 * @var bool
	 *
	 * Whether to enqueue in the admin
	 */
	protected $admin = false;

	/**
	 * @var array
	 *
	 * The handle of any dependencies
	 */
	protected $dependencies = [ ];

	/**
	 * @var bool|string
	 *
	 * The version of the asset
	 */
	protected $version = false;

	/**
	 * The URL to the assets folder
	 *
	 * @var string
	 */
	protected $assets_url = '';

	/**
	 * The URL to the scripts folder
	 *
	 * @var string
	 */
	protected $scripts_url = '';

	/**
	 * The URL to the styles folder
	 *
	 * @var string
	 */
	protected $styles_url = '';

	/**
	 * Register the appropriate actions with WordPress
	 */
	public function __construct() {

		if ( $this->frontend ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
			$this->location = 'Frontend/';
		}

		if ( $this->admin ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
			$this->location = 'Admin/';
		}

		$base              = $this->get_base_folder();
		$this->assets_url  = trailingslashit( plugins_url() ) . $base . '/Assets/';
		$this->scripts_url = $this->assets_url . 'Scripts/';
		$this->styles_url  = $this->assets_url . 'Styles/';
		$this->setSource();
	}

	/**
	 * Should be implemented to register the asset appropriately
	 *
	 * @return mixed
	 */
	abstract public function enqueue();

	/**
	 * Should be implemented to set the source of the asset appropriately
	 *
	 * @return mixed
	 */
	abstract protected function setSource();

	/**
	 * Get the base assets folder
	 *
	 * Based on namespace "Package" (Second part)
	 *
	 * @return mixed
	 */
	protected function get_base_folder() {
		$reflectionClass = new ReflectionClass( $this );
		$parts           = explode( '\\', $reflectionClass->name );

		if ( is_array( $parts ) && isset( $parts[1] ) ) {
			return $parts[1];
		} else {
			return $reflectionClass->name;
		}
	}
}