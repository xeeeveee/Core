<?php

namespace Xeeeveee\Core\Forms\Elements;

class Html extends Element {

	/**
	 * @inherit
	 */
	protected $type = 'html';

	/**
	 * @inherit
	 */
	public function get_html() {
		return $this->get_element_html();
	}

	/**
	 * @inherit
	 */
	public function get_element_html() {
		return $this->value;
	}

	/**
	 * @inherit
	 */
	public function get_pre_block_html() {
		$html    = '';
		$classes = [ ];

		if ( isset( $this->block_wrap_classes ) && ! empty( $this->block_wrap_classes ) ) {
			$classes = array_merge( $classes, $this->block_wrap_classes );
		}

		$html .= '<div class="';
		$html .= join( ' ', $classes ) . ' ' . $this->type;
		$html .= '" >';

		return $html;
	}

	/**
	 * @inherit
	 */
	public function get_post_block_html() {
		return '</div>';
	}

	/**
	 * @inherit
	 */
	public function get_pre_element_html() {
		$html    = '';
		$classes = [ ];

		$html .= '<div class="';

		if ( isset( $this->element_wrap_classes ) && ! empty( $this->element_wrap_classes ) ) {
			$classes = array_merge( $classes, $this->element_wrap_classes );
		}

		$html .= $this->name . ' ' . $this->type . ' ' . join( ' ', $classes );
		$html .= '">';

		return $html;
	}

	/**
	 * @inherit
	 */
	public function get_post_element_html() {
		return '</div>';
	}
}