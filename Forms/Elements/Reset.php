<?php

namespace Xeeeveee\Core\Forms\Elements;

class Reset extends Element {

	/**
	 * @inherit
	 */
	protected $type = 'reset';

	/**
	 * @inherit
	 */
	public function getElementHtml() {
		$html = '<button type="reset" ';
		$html .= $this->getAttributesString();
		$html .= ' />';
		$html .= $this->value;
		$html .= '</button>';

		return $html;
	}
}