<?php

namespace Xeeeveee\Core\WordPress\Prepare\Fields;

use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Utility\Singleton;

abstract class Field extends Singleton implements FieldInterface {

	use ConfigurationTrait;

	/**
	 * @var string
	 *
	 * The field to apply the filter too
	 */
	protected $field = '';

	/**
	 * @var string|array
	 *
	 * The post type(s) to apply the filters too
	 */
	protected $post_type = 'global';

	/**
	 * Registers the required actions with WordPress
	 */
	protected function __construct() {

		if( is_array( $this->post_type ) ) {
			foreach( $this->post_type as $post_type ) {
				if( ! is_string( $post_type ) ) {
					add_filter(
						$this->filter_base . '/prepare/meta/' . $this->post_type . '/' . $this->field,
						[ $this, 'handle' ]
					);
				}
			}
		}

		if( is_string( $this->post_type ) ) {
			add_filter(
				$this->filter_base . '/prepare/meta/' . $this->post_type . '/' . $this->field,
				[ $this, 'handle' ]
			);
		}
	}

	/**
	 * Responsible for handling the field
	 *
	 * @param $value
	 *
	 * @return mixed
	 */
	abstract public function handle( $value );
}