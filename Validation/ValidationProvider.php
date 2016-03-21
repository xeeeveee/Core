<?php

namespace Xeeeveee\Core\Validation;

use Xeeeveee\Core\Utility\Singleton;

class ValidationProvider extends Singleton implements ValidationProviderInterface {

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

	/**
	 * Registers a new validator
	 *
	 * @param string $name
	 * @param string|array $callback
	 * @param array $error_template
	 * @param bool $force
	 */
	public function register_validator( $name, $callback, $error_template, $force = false ) {

		if ( ! is_array( $callback ) && ! is_string( $callback ) ) {
			// TODO: Throw exception
		}

		if ( isset( $this->error_templates[ $name ] )
		     && ( isset( $this->validators[ $name ] ) || method_exists( $this, 'validate_' . $name ) )
		) {
			if ( $force ) {
				$this->validators[ $name ]      = $callback;
				$this->error_templates[ $name ] = $error_template;
			} else {
				// TODO: Throw exception
			}
		}
	}

	public function validate( array $data = [ ], array $rules = [ ] ) {
		// TODO: Implement validate() method.
	}

	/**
	 * Ensures the value matches an accepted value
	 *
	 * Valid values:
	 * - true
	 * - 1
	 * - yes
	 * - on
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_accepted( $value, array $data = [ ], array $parameters = [ ] ) {
		return ( $value == true || $value == 1 || strtolower( $value ) == 'yes' || $value == 'on' );
	}

	/**
	 * Ensure the value is an array
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_array( $value, array $data = [ ], array $parameters = [ ] ) {
		return is_array( $value );
	}

	/**
	 * Ensure the value is within a range
	 *
	 * Numeric values will be compared numerically, strings will be compared on length, arrays will be compared on size
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_between( $value, array $data = [ ], array $parameters = [ ] ) {
		if ( is_numeric( $value ) ) {
			return ( $value >= $parameters[0] && $value <= $parameters[1] );
		}

		if ( is_string( $value ) ) {
			return ( strlen( $value ) >= $parameters[0] && $value <= $parameters[1] );
		}

		if ( is_array( $value ) ) {
			return ( count( $value ) >= $parameters[0] && count( $value ) >= $parameters[0] );
		}

		return false;
	}

	/**
	 * Ensures the value is a boolean or boolean like
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function boolean( $value, array $data = [ ], array $parameters = [ ] ) {
		return ( is_bool( $value ) || $value == 1 || $value == 0 || $value == '1' || $value == '0' );
	}

	/**
	 * Ensure the value is a date
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_date( $value, array $data = [ ], array $parameters = [ ] ) {
		return ( strtotime( $value ) !== false );
	}

	/**
	 * Ensure the date is a given format
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_date_format( $value, array $data = [ ], array $parameters = [ ] ) {
		$date = date_parse_from_format( $parameters[0], $value );

		return ( $date['error_count'] === 0 && $date['warning_count'] === 0 );
	}

	/**
	 * Ensure a date is after a given datetime
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_date_after( $value, array $data = [ ], array $parameters = [ ] ) {
		$date = strtotime( $value );

		return ( $date !== false && $value > strtotime( $parameters[0] ) );
	}

	/**
	 * Ensure a date is before a given datetime
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_date_before( $value, array $data = [ ], array $parameters = [ ] ) {
		$date = strtotime( $value );

		return ( $date !== false && $value < strtotime( $parameters[0] ) );
	}

	/**
	 * Ensure a date is between two given datetimes
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_date_between( $value, array $data = [ ], array $parameters = [ ] ) {
		$date = strtotime( $value );

		return ( $date !== false && $value > strtotime( $parameters[0] ) && $value < strtotime( $parameters[0] ) );
	}

	/**
	 * Ensures the value differs from another field
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_different( $value, array $data = [ ], array $parameters = [ ] ) {
		return $value != $data[ $parameters[0] ];
	}

	/**
	 * Ensures the value matches an other field
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_match( $value, array $data = [ ], array $parameters = [ ] ) {
		return $value == $data[ $parameters[0] ];
	}

	/**
	 * Ensure the value does not exceed a maximum
	 *
	 * Numeric values will be compared numerically, strings will be compared on length, arrays will be compared on size
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_max( $value, array $data = [ ], array $parameters = [ ] ) {
		if ( is_numeric( $value ) ) {
			return $value <= $parameters[0];
		}

		if ( is_string( $value ) ) {
			return strlen( $value ) <= $parameters[0];
		}

		if ( is_array( $value ) ) {
			return count( $value ) <= $parameters[0];
		}

		return false;
	}

	/**
	 * Ensure the value is at least a minimum value
	 *
	 * Numeric values will be compared numerically, strings will be compared on length, arrays will be compared on size
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_min( $value, array $data = [ ], array $parameters = [ ] ) {
		if ( is_numeric( $value ) ) {
			return $value >= $parameters[0];
		}

		if ( is_string( $value ) ) {
			return strlen( $value ) >= $parameters[0];
		}

		if ( is_array( $value ) ) {
			return count( $value ) >= $parameters[0];
		}

		return false;
	}

	/**
	 * Ensure the value is numeric
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_numeric( $value, array $data = [ ], array $parameters = [ ] ) {
		return is_numeric( $value );
	}

	/**
	 * Ensure the value is an object
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_object( $value, array $data = [ ], array $parameters = [ ] ) {
		return is_object( $value );
	}

	/**
	 * Ensure the URL is reachable
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_reachable_url( $value, array $data = [ ], array $parameters = [ ] ) {
		return checkdnsrr( $value );
	}

	/**
	 * Ensure a value exists and is not empty
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_required( $value, array $data = [ ], array $parameters = [ ] ) {
		return isset( $value ) && ! empty( $value );
	}

	/**
	 * Ensure a value exists and is not empty only when another field is a set value
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_required_if( $value, array $data = [ ], array $parameters = [ ] ) {
		if ( isset( $data[ $parameters[0] ] ) && $data[ $parameters[0] ] == $parameters[1] ) {
			return isset( $value ) && ! empty( $value );
		}

		return true;
	}

	/**
	 * Ensure a value exists and is not empty only when other fields are present
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_required_with( $value, array $data = [ ], array $parameters = [ ] ) {
		if ( isset( $data[ $parameters[0] ] ) && ! empty( $data[ $parameters[0] ] ) ) {
			return isset( $value ) && ! empty( $value );
		}

		return true;
	}

	/**
	 * Ensure a value exists and is not empty only when other fields are present
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_required_without( $value, array $data = [ ], array $parameters = [ ] ) {
		if ( ! isset( $data[ $parameters[0] ] ) || empty( $data[ $parameters[0] ] ) ) {
			return isset( $value ) && ! empty( $value );
		}

		return true;
	}

	/**
	 * Ensure the value is a string
	 *
	 * @param $value
	 * @param array $data
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function validate_string( $value, array $data = [ ], array $parameters = [ ] ) {
		return is_string( $value );
	}
}

