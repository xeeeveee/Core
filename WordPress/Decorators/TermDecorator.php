<?php

namespace Xeeeveee\Core\WordPress\Decorators;

class TermDecorator extends Decorator implements DecoratorInterface {

	/**
	 * @var string
	 */
	protected $meta_decorator = 'Xeeeveee\Core\WordPress\Decorators\TermMetaDecorator';

	/**
	 * @inherit
	 */
	protected $path = 'term';

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
			return new $this->meta_decorator( $this->original->meta );
		}

		return $this->original->meta;
	}
}
