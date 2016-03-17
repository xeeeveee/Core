<?php

namespace Xeeeveee\Core\Validation;

use Xeeeveee\Core\Utility\Singleton;

class Validator extends Singleton implements ValidatorInterface {

	protected $validators = [ ];

	protected $error_messages = [
		'string' => ':field must be a string'
	];

	public function get_validators() {
		// TODO: Implement get_validators() method.
	}

	public function register_validator( $name, $callback, $error_message, $force = false ) {

		if ( ! is_array( $callback ) && ! is_string( $callback ) ) {
			// TODO: Throw exception
		}

		if ( isset( $this->error_messages[ $name ] )
		     && ( isset( $this->validators[ $name ] ) || method_exists( $this, 'validate_' . $name ) )
		) {
			if ( $force ) {
				$this->validators[ $name ]     = $callback;
				$this->error_messages[ $name ] = $error_message;
			} else {
				// TODO: Throw exception
			}
		}
	}

	public function validate( array $data = [ ], array $rules = [ ] ) {
		// TODO: Implement validate() method.
	}

	public function get_errors() {
		// TODO: Implement get_errors() method.
	}

	protected function validate_string( $value ) {
		return is_string( $value );
	}
}

