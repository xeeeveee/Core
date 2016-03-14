<?php

namespace Xeeeveee\Core\WordPress\Register\Enqueue;

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
	 * The source of the file to enqueue
	 */
	protected $source = '';

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

		$this->assets_url  = trailingslashit( plugins_url() ) . 'Assets/';
		$this->scripts_url = $this->assets_url . 'Scripts/';
		$this->styles_url  = $this->assets_url . 'Styles/';
		$this->setSource();

		if ( $this->frontend ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		}

		if ( $this->admin ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
		}
	}

	/**
	 * Should be implemented to register the asset appropriately
	 * @return mixed
	 */
	abstract public function enqueue();

	/**
	 * Sets the source
	 *
	 * Allows the source to be computed by overriding this method
	 *
	 * @return string
	 */
	abstract protected function setSource();
}