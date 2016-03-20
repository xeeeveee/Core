<?php

namespace Xeeeveee\Core\Validation;

use Xeeeveee\Core\Utility\Singleton;

class Validator extends Singleton implements ValidatorInterface {

	/**
	 * Holds custom validators
	 *
	 * @var array
	 */
	protected $validators = [ ];

	/**
	 * Holds the error templates
	 *
	 * @var array
	 */
	protected $error_templates = [
		'string' => ':field must be a string'
	];

	/**
	 * Holds the validation errors
	 *
	 * @var array
	 */
	protected $errors = [ ];

	/**
	 * Get all the registered validators
	 *
	 * @return array
	 */
	public function get_validators() {
		return $this->validators;
	}

	/**
	 * Get all errors for the last validator
	 *
	 * @return array
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Get all error templates
	 *
	 * @return array
	 */
	public function get_error_templates() {
		return $this->error_templates;
	}

	public function register_validator( $name, $callback, $error_message, $force = false ) {

		if ( ! is_array( $callback ) && ! is_string( $callback ) ) {
			// TODO: Throw exception
		}

		if ( isset( $this->error_templates[ $name ] )
		     && ( isset( $this->validators[ $name ] ) || method_exists( $this, 'validate_' . $name ) )
		) {
			if ( $force ) {
				$this->validators[ $name ]      = $callback;
				$this->error_templates[ $name ] = $error_message;
			} else {
				// TODO: Throw exception
			}
		}
	}

	public function validate( array $data = [ ], array $rules = [ ] ) {
		// TODO: Implement validate() method.
	}

	/**
	 * Ensure the value is a string
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	protected function validate_string( $value, array $parameters = [ ] ) {
		return is_string( $value );
	}

	/**
	 * Ensure the value is numeric
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	protected function validate_numeric( $value, array $parameters = [  ) {
		return is_numeric( $value );
	}

	/**
	 * Ensure the value is an array
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	protected function is_array( $value, array $parameters = [  ) {
		return is_array( $value );
	}

	/**
	 * Ensure the value is an object
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	protected function is_object( $value, array $parameters = [  ) {
		return is_object( $value );
	}
}

