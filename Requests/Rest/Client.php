<?php

namespace Xeeeveee\Core\Requests\Rest;

use Guzzle\Http\Client as GuzzleClient;
use Xeeeveee\Core\Exceptions\ComposerInstallationRequiredException;

class Client implements ClientInterface {

	/**
	 * Make a GET request
	 *
	 * @param $url
	 * @param array $parameters
	 * @param array $args
	 *
	 * @return array|\WP_Error
	 */
	public function get( $url, array $parameters = [], array $args = [ ] ) {
		$args['method'] = 'GET';
		$args['body'] = $parameters;
		return wp_remote_request( $url, $args );
	}

	/**
	 * Make a POST request
	 *
	 * @param $url
	 * @param array $parameters
	 * @param array $args
	 *
	 * @return array|\WP_Error
	 */
	public function post( $url, array $parameters = [], array $args = [ ] ) {
		$args['method'] = 'POST';
		$args['body'] = $parameters;
		return wp_remote_request( $url, $args );
	}

	/**
	 * Make a PUT request
	 *
	 * @param $url
	 * @param array $parameters
	 * @param array $args
	 *
	 * @return array|\WP_Error
	 */
	public function put( $url, array $parameters = [], array $args = [ ] ) {
		$args['method'] = 'PUT';
		$args['body'] = $parameters;
		return wp_remote_request( $url, $args );
	}

	/**
	 * Make a PATCH request
	 *
	 * @param $url
	 * @param array $parameters
	 * @param array $args
	 *
	 * @return array|\WP_Error
	 */
	public function patch( $url, array $parameters = [], array $args = [ ] ) {
		$args['method'] = 'PATCH';
		$args['body'] = $parameters;
		return wp_remote_request( $url, $args );
	}

	/**
	 * Make a DELETE request
	 *
	 * @param $url
	 * @param array $parameters
	 * @param array $args
	 *
	 * @return array|\WP_Error
	 */
	public function delete( $url, array $parameters = [], array $args = [ ] ) {
		$args['method'] = 'DELETE';
		$args['body'] = $parameters;
		return wp_remote_request( $url, $args );
	}

	/**
	 * Make a HEAD request
	 *
	 * @param $url
	 * @param array $parameters
	 * @param array $args
	 *
	 * @return array|\WP_Error
	 */
	public function head( $url, array $parameters = [], array $args = [ ] ) {
		$args['method'] = 'HEAD';
		$args['body'] = $parameters;
		return wp_remote_request( $url, $args );
	}

	/**
	 * Make a OPTIONS request
	 *
	 * @param $url
	 * @param array $parameters
	 * @param array $args
	 *
	 * @return array|\WP_Error
	 */
	public function options( $url, array $parameters = [], array $args = [ ] ) {
		$args['method'] = 'OPTIONS';
		$args['body'] = $parameters;
		return wp_remote_request( $url, $args );
	}
}