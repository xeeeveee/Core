<?php

namespace Xeeeveee\Core\WordPress\Enqueue\Style;

use Xeeeveee\Core\WordPress\Register\Enqueue\EnqueueStyle;

class ColorPicker extends EnqueueStyle {

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