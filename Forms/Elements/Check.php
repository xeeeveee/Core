<?php

namespace Xeeeveee\Core\Forms\Elements;

class Check extends Element {

	/**
	 * @var string
	 *
	 * This is only really important for elements using the html input tag
	 */
	protected $type = 'check';

	/**
	 * @inherit
	 */
	public function get_pre_block_html() {

		$html = '<div class="';

		if ( isset( $this->attributes['class'] ) && ! empty( $this->attributes['class'] ) ) {
			$classes = array_merge( $this->block_wrap_classes, $this->attributes['class'] );
		} else {
			$classes = $this->block_wrap_classes;
		}

		$classes = join( ' ', $classes );
		$html .= $classes . ' ' . $this->type;
		$html .= '">';

		if ( ! empty( $this->label ) ) {
			$html .= '<label>' . $this->label;
			$html .= '</label>';
		}

		return $html;
	}

	/**
	 * @inherit
	 */
	public function get_element_html() {

		if ( ! is_array( $this->options ) ) {
			return '';
		}

		$html = '';

		foreach ( $this->options as $value => $label ) {
			if ( count( $this->options ) > 1 ) {
				$name = $this->name . '[' . $value . ']';
			} else {
				$name = $this->name;
			}

			$html .= '<label>';
			$html .= '<input type="hidden" name="' . $name . '" value="0" >';
			$html .= '<input type="checkbox" name="' . $name . '" value="1" ' . checked( $value, '1', false ) . '>';
			$html .= '<span>' . $label . '</span></label>';
		}

		return $html;
	}
}