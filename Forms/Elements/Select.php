<?php

namespace Xeeeveee\Core\Forms\Elements;

class Select extends Element {

	/**
	 * @inherit
	 */
	protected $type = 'select';

	/**
	 * @inherit
	 */
	public function getElementHtml() {

		$html = '<' . $this->type . ' name="' . $this->name . '" ';
		$html .= $this->get_attributes_string();
		$html .= ' >';

		// TODO: Add support for option groups

		foreach ( $this->options as $value => $label ) {
			$html .= '<option value="' . $value . '" ';
			$html .= selected( $value, $this->value );
			$html .= ' >';
			$html .= $label;
			$html .= '</option>';
		}

		$html .= '</' . $this->type . '>';

		return $html;
	}
}