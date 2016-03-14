<?php

namespace Xeeeveee\Core\Forms\Elements;

class Wysiwyg extends Element {

	/**
	 * @inherit
	 */
	protected $type = 'wysiwyg';

	/**
	 * Renders a standard WordPress TinyMCE WYSIWYG editor
	 *
	 * TODO: Add full support for the wp_editor() $settings (3rd parameter)
	 */
	public function get_element_html() {
		ob_start();
		wp_editor( $this->value, $this->name, [
			'textarea_name' => $this->name
		] );

		return ob_get_flush();
	}
}