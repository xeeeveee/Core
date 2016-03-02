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
	 * The type(s) to apply the filters too, can be a mix of post types & taxonomies
	 */
	protected $type = 'global';

	/**
	 * Registers the required actions with WordPress
	 */
	protected function __construct() {

		if( is_array( $this->type ) ) {
			foreach( $this->type as $post_type ) {
				if( ! is_string( $post_type ) ) {
					add_filter(
						$this->filter_base . '/prepare/meta/' . $this->type . '/' . $this->field,
						[ $this, 'handle' ]
					);
				}
			}
		}

		if( is_string( $this->type ) ) {
			add_filter(
				$this->filter_base . '/prepare/meta/' . $this->type . '/' . $this->field,
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