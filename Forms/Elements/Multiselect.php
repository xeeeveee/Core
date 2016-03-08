<?php

namespace Xeeeveee\Core\Forms\Elements;

class MultiSelect extends Select {

	/**
	 * @inherit
	 */
	protected $type = 'select';

	/**
	 * @inherit
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [ ] ) {
		$this->add_attributes( [ 'multiple' => 'multiple' ] );
		$this->add_reserved_attribute( 'multiple' );
		parent::__construct( $args );
	}
}