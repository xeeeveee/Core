<?php

namespace Xeeeveee\Core\Validation;

class Validator implements ValidatorInterface {

	/**
	 * Holds the validation provider
	 *
	 * @var ValidationProvider
	 */
	protected $provider;

	/**
	 * Holds the rules
	 *
	 * @var array
	 */
	protected $rules = [ ];

	/**
	 * Holds the data
	 *
	 * @var array
	 */
	protected $data = [ ];

	/**
	 * Holds the errors
	 *
	 * @var array
	 */
	protected $errors = [ ];

	/**
	 * Prepare the object
	 *
	 * @param $data
	 */
	public function __construct( array $data = [ ] ) {
		$provider   = ValidationProvider::get_instance();
		$this->data = $data;
	}

	/**
	 * Get the rules
	 *
	 * @return array
	 */
	public function get_rules() {
		return $this->rules();
	}

	/**
	 * Get the data
	 *
	 * @return array
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * Get the errors
	 *
	 * @return array
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Checks if the validator has passed
	 *
	 * @return bool
	 */
	public function passes() {
		return ( $this->provider->validate( $this->data, $this->rules() ) === true );
	}

	/**
	 * Checks if the validator has failed
	 *
	 * @return bool
	 */
	public function fails() {
		return ( $this->provider->validate( $this->data, $this->rules() ) === false );
	}

	/**
	 * Gets the rules
	 *
	 * This is implemented as a method to allow logic to be applied to the rules in extending classes
	 *
	 * @return array
	 */
	protected function rules() {
		return $this->rules;
	}

}

