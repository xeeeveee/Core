<?php

namespace Xeeeveee\Core\Validation;

interface ValidatorInterface {

	public function get_validators();

	public function get_errors();

	public function get_error_templates();

	public function register_validator( $name, $callback, $error_message );

	public function validate( array $data = [ ], array $rules = [ ] );


}