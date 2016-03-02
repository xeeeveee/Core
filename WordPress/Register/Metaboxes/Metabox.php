<?php

namespace Xeeeveee\Core\WordPress\Register\Metaboxes;

use ReflectionClass;
use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Exceptions\NotIntegerException;
use Xeeeveee\Core\Utility\Singleton;

abstract class Metabox extends Singleton implements MetaboxInterface {

	use ConfigurationTrait;

	/**
	 * @var array
	 *
	 * The configuration
	 */
	protected $configuration = [ ];

	/**
	 * @var array
	 *
	 * The default configuration
	 */
	private $default_configuration = [
		'id'            => '',
		'title'         => '',
		'screen'        => '',
		'context'       => 'advanced',
		'priority'      => 'default',
		'callback_args' => null
	];

	/**
	 * @var array
	 *
	 * The merged configuration
	 */
	private $merged_configuration = [ ];

	/**
	 * @var array
	 *
	 * A list of items not to save
	 */
	protected $ignoreList = [ ];

	/**
	 * Registers the required actions with WordPress
	 */
	protected function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
		add_action( 'save_post', [ $this, 'save_meta' ] );

		$this->merged_configuration = array_merge( $this->default_configuration, $this->configuration );
	}

	/**
	 * Registers the metabox with WordPress
	 */
	public function add_meta_box() {
		add_meta_box(
			$this->merged_configuration['id'],
			$this->merged_configuration['title'],
			[ $this, 'render' ],
			$this->merged_configuration['screen'],
			$this->merged_configuration['context'],
			$this->merged_configuration['priority'],
			$this->merged_configuration['callback_args']
		);
	}

	/**
	 * Render the metabox
	 *
	 * @return mixed
	 */
	public function render() {
		$id       = $_GET['post'];
		$class    = new ReflectionClass( $this );
		$path     = dirname( $class->getFileName() ) . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . $class->getShortName() . '.php';
		$settings = [];

		if( ! empty( $id ) && is_int( $id ) ) {
			$settings = $this->get_meta( $id );
		}

		if ( file_exists( $path ) ) {
			require_once( $path );
		}
	}

	/**
	 * Gets the post meta
	 *
	 * @param $post_id
	 *
	 * @return array
	 * @throws NotIntegerException
	 */
	public function get_meta( $post_id ) {
		if ( ! is_integer( $post_id ) ) {
			throw new NotIntegerException( 'The parameter "$post_id" must be an integer' );
		}

		return get_post_custom( $post_id );
	}

	/**
	 * Saves the post meta
	 *
	 * @param $post_id
	 */
	public function save_meta( $post_id ) {
		foreach ( $_POST as $key => $val ) {
			$this->ignoreList = apply_filters( $this->filter_base . '/metabox/' . get_class() . '/ignore_list', $this->ignoreList );
			$this->ignoreList = apply_filters( $this->filter_base . '/metabox/global/ignore_list', $this->ignoreList );

			if ( strstr( $key, $this->prefix ) !== false
			     && ! in_array( str_replace( $this->prefix, '', $key ), $this->ignoreList )
			) {
				update_post_meta( $post_id, $key, $val );
			}
		}
	}
}