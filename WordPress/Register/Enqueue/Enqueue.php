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
	 * @var string
	 *
	 * The type of enqueue to call
	 */
	protected $type = '';

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
	 * The location with in the scripts or styles url
	 *
	 * @var string
	 */
	protected $location = '';

	/**
	 * Register the appropriate actions with WordPress
	 */
	public function __construct() {

		if ( $this->frontend ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		}

		if ( $this->admin ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
		}

		$base              = $this->getBaseFolder();
		$this->assets_url  = $this->getAssetsUrl( $base );
		$this->scripts_url = $this->getScriptsUrl();
		$this->styles_url  = $this->getStylesUrl();
		$this->location    = $this->getLocation();
		$this->setSource();
	}

	/**
	 * Should be implemented to register the asset appropriately
	 *
	 * @return mixed
	 */
	abstract public function enqueue();

	/**
	 * Set the source of the resource
	 *
	 * @return $this
	 */
	protected function setSource() {
		if ( empty( $this->resource ) ) {
			$this->source = false;
		} elseif ( strpos( $this->resource, '/' ) !== false ) {
			$this->source = $this->resource;
		} else {
			if ( $this->type == 'script' ) {
				$url = $this->scripts_url;
			} else {
				$url = $this->styles_url;
			}
			$this->source = $url . $this->location . $this->resource;
		}

		return $this;
	}

	/**
	 * Get the base assets folder
	 *
	 * Based on namespace "Package" (Second part)
	 *
	 * @return mixed
	 */
	protected function getBaseFolder() {
		$reflectionClass = new ReflectionClass( $this );
		$parts           = explode( '\\', $reflectionClass->name );

		if ( is_array( $parts ) && isset( $parts[1] ) ) {
			return $parts[1];
		} else {
			return $reflectionClass->name;
		}
	}

	/**
	 * Get the assets URL
	 *
	 * @param $base
	 *
	 * @return string
	 */
	protected function getAssetsUrl( $base ) {
		return trailingslashit( plugins_url() ) . $base . '/Assets/';
	}

	/**
	 * Get the scripts URL
	 *
	 * @return string
	 */
	protected function getScriptsUrl() {
		return $this->assets_url . 'Scripts/';
	}

	/**
	 * Get the styles URL
	 *
	 * @return string
	 */
	protected function getStylesUrl() {
		return $this->assets_url . 'Styles/';
	}

	/**
	 * Get the styles URL
	 *
	 * @return string
	 */
	protected function getLocation() {
		if ( $this->admin ) {
			return 'Admin/';
		} else {
			return 'Frontend/';
		}
	}
}