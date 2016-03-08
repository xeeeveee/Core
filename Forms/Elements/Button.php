<?php

namespace Xeeeveee\Core\Forms\Elements;

class Button extends Element {

	/**
	 * the type element this is
	 *
	 * @var string
	 */
	protected $type = 'button';

	/**
	 * Get the element html
	 *
	 * @return string
	 */
	public function getElementHtml() {
		$html = '<button ';
		$html .= $this->getAttributesString();
		$html .= ' />';
		$html .= $this->value;
		$html .= '</button>';

		return $html;
	}
}