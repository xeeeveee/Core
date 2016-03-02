<?php

namespace Xeeeveee\Core\WordPress\Register\Settings;

use ReflectionClass;
use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Exceptions\EmptyOrUndefinedException;
use Xeeeveee\Core\Exceptions\NotStringException;
use Xeeeveee\Core\Utility\Singleton;

abstract class Page extends Singleton implements SettingsInterface {

	use ConfigurationTrait;

	/**
	 * @var string
	 *
	 * The name of the option to save the data against
	 */
	protected $settings_option = '';

	/**
	 * @var array
	 *
	 * The values for the page
	 */
	protected $configuration = [ ];

	/**
	 * @var array
	 *
	 * The default values for the page
	 */
	private $default_configuration = [
		'parent_slug' => '',
		'page_title'  => '',
		'menu_title'  => '',
		'capability'  => 'manage_options',
		'menu_slug'   => '',
		'icon_url'    => '',
		'position'    => null
	];

	/**
	 * @var array
	 *
	 * The settings configuration merged with the default configuration
	 */
	private $merged_configuration = [ ];

	/**
	 * @var array
	 *
	 * Holds a list of setting's that should not be saved
	 */
	protected $ignore_list = [ ];

	/**
	 * @var bool
	 *
	 * Whether the update was a success or not
	 */
	private $update_success = false;

	/**
	 * Registers the required actions with WordPress
	 */
	protected function __construct() {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'save_settings' ] );

		$this->merged_configuration = array_merge( $this->default_configuration, $this->configuration );
	}

	/**
	 * To be implemented by the extending class
	 *
	 * This should output the content of the settings page
	 */
	public function render() {
		$class    = new ReflectionClass( $this );
		$path     = dirname( $class->getFileName() ) . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . $class->getShortName() . '.php';
		$settings = $this->get_settings();

		if ( file_exists( $path ) ) {
			require_once( $path );
		}
	}

	/**
	 * Gets the page's settings
	 *
	 * @return mixed|void
	 * @throws EmptyOrUndefinedException
	 * @throws NotStringException
	 */
	public function get_settings() {
		return get_option( $this->get_option() );
	}

	/**
	 * Saves the settings
	 *
	 * Any keys in the ignore_list property will not be saved
	 *
	 * @throws EmptyOrUndefinedException
	 * @throws NotStringException
	 */
	public function save_settings() {

		if ( empty( $_POST ) ) {
			return;
		}

		if ( ! isset( $_GET['page'] ) || $_GET['page'] != $this->merged_configuration['menu_slug'] ) {
			return;
		}

		$data = array_diff_key(
			$_POST,
			apply_filters( $this->filter_base . '/settings/' . get_class( $this ) . '/ignore', $this->ignore_list )
		);

		$this->update_success = update_option( $this->get_option(), $data );
		add_action( 'admin_notices', [ $this, 'display_update_notice' ] );
	}

	/**
	 * Registers the settings page with WordPress
	 *
	 * If parent_slug is set a sub menu page will be added, otherwise a top level menu will be added
	 */
	public function add_settings_page() {
		if ( ! empty( $this->merged_configuration['parent_slug'] ) && is_string( $this->merged_configuration['parent_slug'] ) ) {
			add_submenu_page(
				$this->merged_configuration['parent_slug'],
				$this->merged_configuration['page_title'],
				$this->merged_configuration['menu_title'],
				$this->merged_configuration['capability'],
				$this->merged_configuration['menu_slug'],
				[ $this, 'render' ]
			);
		} else {
			add_menu_page(
				$this->merged_configuration['page_title'],
				$this->merged_configuration['menu_title'],
				$this->merged_configuration['capability'],
				$this->merged_configuration['menu_slug'],
				[ $this, 'render' ],
				$this->merged_configuration['icon_url'],
				$this->merged_configuration['position']
			);
		}
	}

	/**
	 * Displays an update success / failure message when the page is saved
	 */
	public function display_update_notice() {
		if ( $this->update_success ) {
			echo '<div class="updated"><p>Settings updated successfully.</p></div>';
		} else {
			echo '<div class="error"><p>There was an error updating your settings.</p></div>';
		}
	}

	/**
	 * Gets the settings option for the page
	 *
	 * @return mixed|void
	 * @throws EmptyOrUndefinedException
	 * @throws NotStringException
	 */
	private function get_option() {
		if ( ! isset( $this->settings_option ) || empty( $this->settings_option ) ) {
			throw new EmptyOrUndefinedException( 'The "$settings_option" property must be set in class ' . get_class( $this ) );
		}

		if ( ! is_string( $this->settings_option ) ) {
			throw new NotStringException( 'The "$settings_option" property must be of type string in class ' . get_class( $this ) );
		}

		return apply_filters(
			$this->filter_base . '/settings/' . get_class( $this ) . '/option/' . $this->prefix . $this->settings_option,
			$this->prefix . $this->settings_option
		);
	}
}