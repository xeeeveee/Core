<?php

namespace Xeeeveee\Core\Forms\Elements;

class Date extends Element {

	/**
	 * @inherit
	 */
	protected $type = 'date';

	/**
	 * @inherit
	 */
	public function get_element_html() {

		$html = '<input name="' . $this->name . '" type="text" ';

		if ( ! empty( $this->value ) && is_string( $this->value ) ) {
			$html .= 'value="' . $this->value . '" ';
		}

		$html .= $this->get_attributes_string();
		$html .= ' />';

		return $html;
	}
}