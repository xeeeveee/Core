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
	public function get_element_html() {

		$html = '<' . $this->type . ' name="' . $this->name . '" ';
		$html .= $this->get_attributes_string();
		$html .= ' >';

		foreach ( $this->options as $value => $label ) {
			if ( is_array( $label ) ) {
				$html .= '<optgroup label="' . $label . '">';
				foreach ( $label as $group_value => $group_label ) {
					$html .= $this->get_option_html( $group_value, $group_label );
				}
				$html .= '</optgroup>';
			}
			$html .= $this->get_option_html( $value, $label );
		}

		$html .= '</' . $this->type . '>';

		return $html;
	}

	/**
	 * Gets the HTML for a group of options
	 *
	 * @param $value string
	 * @param $label string
	 *
	 * @return string
	 * @internal param $options
	 *
	 */
	protected function get_option_html( $value, $label ) {

		$html = '<option value="' . $value . '" ';

		if ( ( is_array( $this->value ) && in_array( $value, $this->value ) ) || ( $value == $this->value ) ) {
			$html .= 'selected';
		}

		$html .= ' >';
		$html .= $label;
		$html .= '</option>';

		return $html;
	}
}