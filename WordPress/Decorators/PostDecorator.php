<?php

namespace Xeeeveee\Core\WordPress\Decorators;

use stdClass;
use WP_Term;

class PostDecorator extends Decorator implements DecoratorInterface {

	/**
	 * @var string
	 */
	protected $date_format = 'l, jS F, Y';

	/**
	 * @var string
	 */
	protected $meta_decorator = 'Xeeeveee\Core\WordPress\Decorators\PostMetaDecorator';

	/**
	 * @var string
	 */
	protected $term_decorator = 'Xeeeveee\Core\WordPress\Decorators\TermDecorator';

	/**
	 * Apply filters to the post title
	 *
	 * @return mixed|void
	 */
	public function get_post_title() {
		return apply_filters( 'the_title', $this->original->post_title );
	}

	/**
	 * Apply filters to the post content
	 *
	 * @return mixed|void
	 */
	public function get_post_content() {
		return apply_filters( 'the_content', $this->original->post_content );
	}

	/**
	 * Apply filters to the post excerpt
	 *
	 * @return mixed|void
	 */
	public function get_excerpt() {
		return apply_filters( 'the_excerpt', $this->original->post_excerpt );
	}

	/**
	 * Apply filters to the guid
	 *
	 * @return mixed|void
	 */
	public function get_guid() {
		return apply_filters( 'the_guid', $this->original->guid );
	}

	/**
	 * Format the post date
	 *
	 * @return bool|string
	 */
	public function get_post_date() {
		return date( $this->date_format, strtotime( $this->original->post_date ) );
	}

	/**
	 * Format the post date gmt
	 *
	 * @return bool|string
	 */
	public function get_post_date_gmt() {
		return date( $this->date_format, strtotime( $this->original->post_date_gmt ) );
	}

	/**
	 * Format the post modified date
	 *
	 * @return bool|string
	 */
	public function get_post_modified() {
		return date( $this->date_format, strtotime( $this->original->post_modified ) );
	}

	/**
	 * Format the post modified gmt
	 *
	 * @return bool|string
	 */
	public function get_post_modified_gmt() {
		return date( $this->date_format, strtotime( $this->original->post_modified_gmt ) );
	}

	/**
	 * Attach a meta decorator
	 *
	 * @return mixed
	 */
	public function get_meta() {
		if ( isset( $this->meta_decorator )
		     && is_string( $this->meta_decorator )
		     && class_exists( $this->meta_decorator )
		     && is_subclass_of( $this->meta_decorator, 'Xeeeveee\Core\WordPress\Decorators\DecoratorInterface' )
		) {
			return new $this->meta_decorator( $this->original );
		}

		return $this->original->meta;
	}

	/**
	 * Attach term decorators
	 *
	 * @return stdClass
	 */
	public function get_terms() {
		if ( isset( $this->term_decorator )
		     && is_string( $this->term_decorator )
		     && class_exists( $this->term_decorator )
		     && is_subclass_of( $this->term_decorator, 'Xeeeveee\Core\WordPress\Decorators\DecoratorInterface' )
		) {
			$taxonomies      = get_object_vars( $this->original );
			$decorated_terms = new StdClass();

			foreach ( $taxonomies as $taxonomy => $terms ) {
				foreach ( $this->original->terms->{$taxonomy} as $key => $term ) {
					if ( $term instanceof WP_Term ) {
						if ( ! isset( $decorated_terms->{$taxonomy} ) ) {
							$decorated_terms->{$taxonomy} = [ ];
						}
						$decorated_terms->{$taxonomy}[ $key ] = new $this->term_decorator( $term );
					}
				}
			}

			return $decorated_terms;
		}

		return $this->original->terms;
	}
}
