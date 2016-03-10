<?php

namespace Xeeeveee\Core\WordPress\Register\Decorators;

class PostDecorator extends Decorator implements DecoratorInterface {

	/**
	 * @inherit
	 */
	protected $post_type = 'global';

	/**
	 * @inherit
	 */
	protected $decorator = 'Xeeeveee\Core\WordPress\Decorators\PostDecorator';

	/**
	 * @inherit
	 */
	protected $priority = 10;
}
