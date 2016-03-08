<?php

namespace Xeeeveee\Core\Utility;

interface TagInterface {

	public function get_reserved_attributes();

	public function set_attributes( array $attributes = [ ] );

	public function add_attributes( array $attributes = [ ], $override = true );

	public function get_attributes();

	public function clear_attributes();

	public function add_class( $class );

	public function add_classes( array $classes = [ ] );

	public function set_class( $class );

	public function set_classes( array $classes = [ ] );

	public function class_exists( $class );

	public function remove_class( $class );

	public function remove_classes( array $classes = [ ] );

	public function clear_classes();
}