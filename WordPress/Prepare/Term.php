<?php

namespace Xeeeveee\Core\WordPress\Prepare;

use Xeeeveee\Core\Cache\Cache;
use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Utility\Singleton;
use stdClass;
use WP_Term;

class Term extends Singleton implements TermInterface {

	use ConfigurationTrait;

	/**
	 * Registers the required actions with WordPress
	 */
	protected function __construct() {
		$this->cache = new Cache( 'cache' . DIRECTORY_SEPARATOR . 'terms' );
		add_filter( 'get_terms', [ $this, 'prepare_collection' ] );
	}

	/**
	 * Prepares a single term
	 *
	 * Expects meta to be passed directly, however if can query meta directly if no meta is passed, however doing so
	 * will cause cause a query per call, for multiple terms it is significantly more efficient to pass the collection
	 * to prepare_collection
	 *
	 * @param WP_Term $term
	 * @param array $meta_collection
	 * @param bool|false $get_meta
	 *
	 * @return WP_Term
	 */
	public function prepare( WP_Term $term, array $meta_collection = [ ], $get_meta = false ) {

		if ( empty( $meta_collection ) && $get_meta === true ) {
			$meta_collection = $this->get_meta( $term );
		}

		$term->meta = new StdClass();
		foreach ( $meta_collection as $meta ) {

			/*
			* Format <base>/prepare/meta/<taxonomy>/<meta key>
			*/
			$meta->meta_value = apply_filters( $this->filter_base . 'prepare/meta/global/global', $meta->meta_key,
				$meta->meta_value );
			$meta->meta_value = apply_filters( $this->filter_base . 'prepare/meta/global/' . $meta->meta_key,
				$meta->meta_value );
			$meta->meta_value = apply_filters( $this->filter_base . 'prepare/meta/' . $term->taxonomy . '/' . $meta->meta_key,
				$meta->meta_value );

			if ( $term->term_id == $meta->term_id ) {
				if ( isset( $term->meta->{$meta->meta_key} ) ) {
					if ( ! is_array( $term->meta->{$meta->meta_key} ) ) {
						$term->meta->{$meta->meta_key} = [
							$term->meta->{$meta->meta_key},
							$meta->meta_value
						];
					} else {
						$term->meta->{$meta->meta_key}[] = $meta->meta_value;
					}
				} else {
					$term->meta->{$meta->meta_key} = $meta->meta_value;
				}
			}
		}

		return $term;
	}

	/**
	 * Maps term meta to terms
	 *
	 * @param $terms
	 *
	 * @return array
	 */
	public function prepare_collection( array $terms = [ ] ) {

		$cache = apply_filters( $this->filter_base . 'prepare/term/cache', true, $terms );

		if ( $cache ) {
			$prepared_terms = $this->cache->get( $this->get_cache_key( $terms ) );

			if ( $prepared_terms !== false ) {
				return $prepared_terms;
			}
		}

		$prepared_terms = [ ];
		$meta           = $this->get_meta( $terms );

		foreach ( $terms as $term ) {
			if ( $term instanceof WP_Term ) {
				$prepared_terms[] = $this->prepare( $term, $meta );
			}
		}

		if ( $cache ) {
			$this->cache->add( $this->get_cache_key( $terms ), $prepared_terms );
		}

		return $prepared_terms;
	}

	/**
	 * Gets all the terms meta data
	 *
	 * @param array $terms
	 *
	 * @return array|null|object
	 */
	protected function get_meta( array $terms = [ ] ) {

		global $wpdb;

		$ids          = [ ];
		$placeholders = [ ];

		$sql = "SELECT ";
		$sql .= "$wpdb->termmeta.term_id, ";
		$sql .= "$wpdb->termmeta.meta_key, ";
		$sql .= "$wpdb->termmeta.meta_value ";
		$sql .= "FROM $wpdb->termmeta ";
		$sql .= "WHERE $wpdb->termmeta.term_id IN (";

		foreach ( $terms as $term ) {
			if ( ! in_array( $term->term_id, $ids ) ) {
				$placeholders[] = "%d";
				$ids[]          = $term->term_id;
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
	 * @param array|WP_Term $terms
	 *
	 * @return bool|string
	 */
	protected function get_cache_key( $terms ) {

		if ( is_array( $terms ) ) {
			$ids = [ ];
			foreach ( $terms as $term ) {
				if ( $term instanceof WP_Term ) {
					$ids[] = base_convert( $term->ID, 10, 36 );
				}
			}

			return join( $ids, '.' );
		}

		if ( $terms instanceof WP_Term ) {
			return base_convert( $terms->ID, 10, 36 );
		}

		return false;
	}
}