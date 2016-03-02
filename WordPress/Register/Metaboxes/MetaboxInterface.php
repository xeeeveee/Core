<?php

namespace Xeeeveee\Core\WordPress\Register\Metaboxes;

interface MetaboxInterface {

	public function render();

	public function get_meta( $post_id );

	public function save_meta( $post_id );
}