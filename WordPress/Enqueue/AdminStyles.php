<?php

namespace Xeeeveee\Core\WordPress\Enqueue;

use Xeeeveee\Core\WordPress\Register\Enqueue\EnqueueStyle;

class AdminStyles extends EnqueueStyle {

	/**
	 * @inherit
	 */
	protected $handle = 'wp-color-picker';

	/**
	 * @inherit
	 */
	protected $frontend = false;

	/**
	 * @inherit
	 */
	protected $admin = true;
}