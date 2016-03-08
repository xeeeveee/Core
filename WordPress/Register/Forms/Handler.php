<?php

namespace Xeeeveee\Core\WordPress\Register\Forms;

use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Exceptions\InvalidCsrfTokenException;
use Xeeeveee\Core\Utility\Singleton;

abstract class Handler extends Singleton implements HandlerInterface {

	use ConfigurationTrait

	/**
	 * @var string
	 *
	 * The action to respond to
	 */
	protected $action;

	/**
	 * Registers the required actions with WordPress
	 *
	 * @param string $action
	 */
	protected function __construct( $action = '' ) {
		add_action( 'init', [ $this, 'assign_handler' ] );
	}

	/**
	 * Triggers the handler if appropriate
	 */
	public function assign_handler() {

		if ( isset( $_POST[ $this->prefix . 'action' ] ) ) {
			$data = $_POST;
		} elseif ( isset( $_GET[ $this->prefix . 'action' ] ) ) {
			$data = $_GET;
		} else {
			return;
		}

		if ( ! wp_verify_nonce( $data[ $this->prefix . 'csrf_token' ], $this->action ) ) {
			throw new InvalidCsrfTokenException( 'The token: "' . $data[ $this->prefix . 'csrf_token' ] . '" is invalid for the action: "' . $this->action );
			// TODO: Need to register a catch all for this
		}

		$this->handle( $data );
	}

	/**
	 * Handle the form
	 *
	 * @param array $data
	 *
	 * @return
	 */
	abstract public function handle( array $data = [ ] );
}

