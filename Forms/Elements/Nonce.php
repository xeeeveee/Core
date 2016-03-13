<?php

namespace Xeeeveee\Core\Forms\Elements;

class Nonce extends Element {

	/**
	 * @inherit
	 */
	protected $type = 'nonce';

	/**
	 * @inherit
	 */
	public function get_element_html() {
		return wp_nonce_field( $this->name, $this->name . '_' . $this->type, true, false );
	}
}