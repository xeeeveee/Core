<?php

namespace Xeeeveee\Core\Forms;

interface FormInterface {

	public function set_action( $action );

	public function get_action();

	public function set_method( $method );

	public function get_method();

	public function set_reserved_attributes( array $attributes );

	public function get_reserved_attributes();

	public function set_protected_element_types( array $protected_element_types = [ ] );

	public function get_protected_element_types();

	public function set_attributes( array $attributes = [ ] );

	public function add_attributes( array $attributes = [ ], $override = true );

	public function get_attributes();

	public function clear_attributes();

	public function set_values( array $values = [ ] );

	public function add_values( array $values = [ ], $override = true );

	public function get_values();

	public function clear_values();

	public function set_elements( array $elements = [ ] );

	public function add_elements( array $elements = [ ], $override = true );

	public function add_element( array $elements, $override = true );

	public function get_elements();

	public function clear_elements();

	public function set_wrappers( array $wrappers );

	public function get_wrappers();

	public function get_nonce_field();

	public function get_nonce_action();

	public function get_nonce_name();

	public function get_html();

	public function get_elements_html();

	public function get_form_opening_html();

	public function get_form_closing_html();
}