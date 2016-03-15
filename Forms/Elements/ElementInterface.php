<?php

namespace Xeeeveee\Core\Forms\Elements;

interface ElementInterface {

	public function set_name( $name );

	public function get_name();

	public function get_type();

	public function set_attributes( array $attributes = [ ] );

	public function add_attributes( array $attributes = [ ], $override = true );

	public function get_attributes();

	public function clear_attributes();

	public function set_label( $label );

	public function get_label();

	public function set_value( $value );

	public function add_value( $value );

	public function add_values( array $values );

	public function get_value();

	public function clear_value();

	public function get_tooltip();

	public function set_tooltip( $tooltip );

	public function set_tooltip_location( $location );

	public function get_tooltip_location();

	public function set_block_wrappers( array $wrappers = [ ] );

	public function add_block_wrappers( array $wrappers = [ ], $override = true );

	public function get_block_wrappers();

	public function clear_block_wrappers();

	public function set_element_wrappers( array $wrappers = [ ] );

	public function add_element_wrappers( array $wrappers = [ ], $override = true );

	public function get_element_wrappers();

	public function clear_element_wrappers();

	public function get_html();

	public function get_element_html();

	public function get_tooltip_html();

	public function get_pre_block_html();

	public function get_post_block_html();

	public function get_pre_element_html();

	public function get_post_element_html();
}