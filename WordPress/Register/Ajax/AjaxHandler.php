<?php

namespace Xeeeveee\Core\WordPress\Register\Ajax;

use Xeeeveee\Core\Utility\Singleton;

abstract class AjaxHandler extends Singleton implements AjaxHandlerInterface {

	/**
	 * @var string
	 *
	 * The action to respond to
	 */
	protected $action;

	/**
	 * Registers the required actions with WordPress
	 */
	protected function __construct() {
		add_action( 'wp_ajax_' . $this->action . '_handler', [ $this, 'handle' ] );
		add_action( 'wp_ajax_nopriv_' . $this->action . '_handler', [ $this, 'handle' ] );
	}

	/**
	 * Returns the response
	 *
	 * @param $response
	 */
	protected function returnResponse( $response ) {
		echo json_encode( $response );
		die();
	}
}

