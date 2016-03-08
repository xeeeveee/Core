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
	public function getElementHtml() {
		$html = '<button type="submit" ';
		$html .= $this->getAttributesString();
		$html .= ' />';
		$html .= $this->value;
		$html .= '</button>';

		return $html;
	}
}