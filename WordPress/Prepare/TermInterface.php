<?php

namespace Xeeeveee\Core\WordPress\Prepare;

use WP_Term;

interface TermInterface {

	public function prepare( WP_Term $term, array $meta_collection = [ ], $get_meta = false );

	public function prepare_collection( array $terms = [ ] );
}