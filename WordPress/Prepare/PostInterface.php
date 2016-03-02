<?php

namespace Xeeeveee\Core\WordPress\Prepare;

use WP_Post;

interface PostInterface {

	public function prepare( WP_Post $post, array $post_meta = [ ], array $terms = [ ] );

	public function prepare_collection( array $posts = [ ], $include_terms = true );
}