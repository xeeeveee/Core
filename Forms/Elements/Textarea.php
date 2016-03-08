<?php

namespace Xeeeveee\Core\Forms\Elements;

class Textarea extends Element {

	/**
	 * @inherit
	 */
	protected $type = 'textarea';

	/**
	 * @inherit
	 */
	public function getElementHtml() {
		$html = '<textarea name="' . $this->name . '" ' . $this->getAttributesString() . '>';

		if ( ! empty( $this->value ) ) {
			$html .= $this->value;
		}

		$html .= '</textarea/>';

		return $html;
	}
}