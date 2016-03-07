<?php

namespace Xeeeveee\Core\WordPress\Prepare;

use Xeeeveee\Core\Cache\Cache;
use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Utility\Singleton;
use stdClass;
use WP_Post;
use WP_Term;

class Post extends Singleton implements PostInterface {

	use ConfigurationTrait;

	/**
	 * @var Term
	 */
	protected $term_transformer;

	/**
	 * @var Cache
	 */
	protected $cache;

	/**
	 * Registers the required actions with WordPress
	 */
	protected function __construct() {
		$this->term_transformer = Term::get_instance();
		$this->cache            = new Cache( 'cache' . DIRECTORY_SEPARATOR . 'posts' );
		add_filter( 'the_posts', [ $this, 'prepare_collection' ], 20, 2 );
	}

	/**
	 * Prepares a collection of posts
	 *
	 * @param array $posts
	 * @param bool $include_terms
	 *
	 * @return array
	 */
	public function prepare_collection( array $posts = [ ], $include_terms = true ) {

		$cache = apply_filters( $this->filter_base . 'prepare/post/cache', true, $posts );

		if ( $cache ) {
			$prepared_posts = $this->cache->get( $this->get_cache_key( $posts ) );
			if ( $prepared_posts !== false ) {
				return $prepared_posts;
			}
		}

		$prepared_posts = [ ];
		$meta           = $this->get_meta( $posts );

		if ( $include_terms ) {
			$terms = $this->get_terms( $posts );
		} else {
			$terms = [ ];
		}

		foreach ( $posts as $post ) {
			if ( $post instanceof WP_Post ) {
				$prepared_posts[] = $this->prepare( $post, $meta, $terms );
			}
		}

		if ( $cache ) {
			$this->cache->add( $this->get_cache_key( $posts ), $prepared_posts );
		}

		return $posts;
	}

	/**
	 * Prepares a single post
	 *
	 * @param WP_Post $post
	 * @param array $post_meta
	 * @param array $terms
	 *
	 * @return WP_Post
	 */
	public function prepare( WP_Post $post, array $post_meta = [ ], array $terms = [ ] ) {

		$post->meta  = new stdClass();
		$post->terms = new stdClass();

		foreach ( $post_meta as $meta ) {

			if ( $meta->post_id != $post->ID ) {
				continue;
			}

			/*
			 * Format <base>/prepare/meta<post type>/<meta key>
			 */
			$meta->meta_value = apply_filters( $this->filter_base . 'prepare/meta/global/global', $meta->meta_value );
			$meta->meta_value = apply_filters( $this->filter_base . 'prepare/meta/global/' . $meta->meta_key,
				$meta->meta_value );
			$meta->meta_value = apply_filters( $this->filter_base . 'prepare/meta/' . $post->post_type . '/' . $meta->meta_key,
				$meta->meta_value );

			if ( isset( $post->meta->{$meta->meta_key} ) ) {
				if ( ! is_array( $post->meta->{$meta->meta_key} ) ) {
					$post->meta->{$meta->meta_key} = [
						$post->meta->{$meta->meta_key},
						$meta->meta_value
					];
				} else {
					$post->meta->{$meta->meta_key}[] = $meta->meta_value;
				}
			} else {
				$post->meta->{$meta->meta_key} = $meta->meta_value;
			}
		}

		foreach ( $terms as $term ) {
			if ( $term->post_id != $post->ID ) {
				continue;
			}

			if ( isset( $post->terms->{$term->taxonomy} ) ) {
				$post->terms->{$term->taxonomy}[] = $term;
			} else {
				$post->terms->{$term->taxonomy} = [ $term ];
			}
		}

		return $post;
	}

	/**
	 * Gets all the posts terms
	 *
	 * @param array $posts
	 *
	 * @return array|null|object
	 */
	protected function get_terms( array $posts = [ ] ) {

		global $wpdb;

		$ids          = [ ];
		$placeholders = [ ];
		$cast_terms   = [ ];

		$sql = "SELECT ";
		$sql .= "$wpdb->term_relationships.object_id as post_id, ";
		$sql .= "$wpdb->term_taxonomy.taxonomy, ";
		$sql .= "$wpdb->terms.term_id, ";
		$sql .= "$wpdb->terms.name, ";
		$sql .= "$wpdb->terms.slug, ";
		$sql .= "$wpdb->term_taxonomy.description, ";
		$sql .= "$wpdb->term_taxonomy.parent, ";
		$sql .= "$wpdb->term_taxonomy.count, ";
		$sql .= "$wpdb->terms.term_group, ";
		$sql .= "$wpdb->term_relationships.term_order ";
		$sql .= "FROM $wpdb->term_relationships ";
		$sql .= "JOIN $wpdb->term_taxonomy ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id ";
		$sql .= "JOIN $wpdb->terms ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id ";
		$sql .= "WHERE $wpdb->term_relationships.object_id IN (";

		foreach ( $posts as $post ) {
			if ( $post instanceof WP_Post ) {
				$placeholders[] = "%d";
				$ids[]          = $post->ID;
			}
		}

		$sql .= join( $placeholders, ', ' ) . ") ";
		$terms = $wpdb->get_results( $wpdb->prepare( $sql, $ids ) );

		foreach ( $terms as $term ) {
			$cast_terms[] = new WP_Term( $term );
		}

		return $this->term_transformer->prepare_collection( $cast_terms );
	}

	/**
	 * Gets all the posts meta data
	 *
	 * @param array $posts
	 *
	 * @return array|null|object
	 */
	protected function get_meta( array $posts = [ ] ) {

		global $wpdb;

		$ids          = [ ];
		$placeholders = [ ];

		$sql = "SELECT ";
		$sql .= "$wpdb->postmeta.post_id, ";
		$sql .= "$wpdb->postmeta.meta_key, ";
		$sql .= "$wpdb->postmeta.meta_value ";
		$sql .= "FROM $wpdb->postmeta ";
		$sql .= "WHERE $wpdb->postmeta.post_id IN (";

		foreach ( $posts as $post ) {
			if ( ! in_array( $post->ID, $ids ) ) {
				$placeholders[] = "%d";
				$ids[]          = $post->ID;
			}
		}

		$sql .= join( $placeholders, ', ' ) . ") ";

		return $wpdb->get_results( $wpdb->prepare( $sql, $ids ) );
	}

	/**
	 * Get the cache key for a post or collection
	 *
	 * Will return the post ID's, separated by a common in base64 format
	 *
	 * @param array|WP_Post $posts
	 *
	 * @return bool|string
	 */
	protected function get_cache_key( $posts ) {

		if ( is_array( $posts ) ) {
			$ids = [ ];
			foreach ( $posts as $post ) {
				if ( $post instanceof WP_Post ) {
					$ids[] = base_convert( $post->ID, 10, 36 );
				}
			}

			return join( $ids, '.' );
		}

		if ( $posts instanceof WP_Post ) {
			return base_convert( $posts->ID, 10, 36 );
		}

		return false;
	}
}