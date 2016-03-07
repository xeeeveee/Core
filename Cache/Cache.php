<?php

namespace Xeeeveee\Core\Cache;

use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Exceptions\EmptyOrUndefinedException;
use Xeeeveee\Core\Exceptions\NotStringException;

class Cache implements CacheInterface {

	use ConfigurationTrait;

	/**
	 * @var string
	 *
	 * Holds the cache directory
	 */
	protected $cache_directory;

	/**
	 * Sets the default cache directory
	 */
	public function __construct() {
		$this->set_cache_directory( $this->prefix . 'cache' );
	}

	/**
	 * Add an item to the cache
	 *
	 * @param $id
	 * @param $data
	 * @param int $expiration
	 *
	 * @return int
	 */
	public function add( $id, $data, $expiration = 3600 ) {

		$data = [
			'time'   => time(),
			'expire' => $expiration,
			'data'   => serialize( $data )
		];

		$file = $this->cache_directory . $this->encode_id( $id );

		return file_put_contents( $file, serialize( $data ) );
	}

	/**
	 * Gets an item from cache
	 *
	 * @param $id
	 *
	 * @return bool|mixed
	 */
	public function get( $id ) {
		if ( ! file_exists( $this->cache_directory . $this->encode_id( $id ) ) ) {
			return false;
		}

		$data = unserialize( file_get_contents( $this->cache_directory . $this->encode_id( $id ) ) );

		if ( time() > ( $data['time'] + $data['expire'] ) ) {
			$this->remove( $this->encode_id( $id ) );

			return false;
		}

		return unserialize( $data['data'] );
	}

	/**
	 * Removes a single item from cache
	 *
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function remove( $id ) {
		if ( file_exists( $this->cache_directory . $this->encode_id( $id ) ) && is_file( $this->cache_directory . $this->encode_id( $id ) ) ) {
			return unlink( $this->cache_directory . $this->encode_id( $id ) );
		}

		return false;
	}

	/**
	 * Clears the entire cache
	 *
	 * @return int
	 */
	public function clear() {
		$counter = 0;
		$files   = glob( $this->cache_directory . '*' );

		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				unlink( $file );
				$counter ++;
			}
		}

		return $counter;
	}

	/**
	 * Get the cache directory
	 *
	 * Creates it if it doesn't yet exist
	 *
	 * @return string
	 */
	public function get_cache_directory() {
		return $this->cache_directory;
	}

	/**
	 * Sets the cache directory
	 *
	 * Paths is always relative to wp-content
	 * @param $path
	 *
	 * @throws EmptyOrUndefinedException
	 * @throws NotStringException
	 */
	public function set_cache_directory( $path ) {

		if ( empty( $path ) ) {
			throw new EmptyOrUndefinedException( 'Parameter "$path" cannot be empty in ' . get_class( $this ) );
		}

		if ( ! is_string( $path ) ) {
			throw new NotStringException( 'Parameter "$path" must be of type string in ' . get_class( $this ) );
		}

		if ( ! file_exists( WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $path ) ) {
			mkdir( WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $path );
		}

		$this->cache_directory = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR;
	}

	/**
	 * Encodes the cache ID
	 *
	 * @param $id
	 *
	 * @return string
	 */
	private function encode_id( $id ) {
		return base64_encode( $id );
	}
}