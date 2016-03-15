<?php

namespace Xeeeveee\Core\Forms\Elements;

class Submit extends Element {

	/**
	 * @inherit
	 */
	protected $type = 'submit';

	/**
	 * @inherit
	 */
	public function get_element_html() {
		$html = '<button type="submit" ';
		$html .= $this->get_attributes_string();
		$html .= ' />';
		$html .= $this->value;
		$html .= '</button>';

		return $html;
	}
}