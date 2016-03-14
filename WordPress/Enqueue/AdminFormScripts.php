<?php

namespace Xeeeveee\Core\WordPress\Enqueue;

use Xeeeveee\Core\WordPress\Register\Enqueue\EnqueueScript;

class AdminFormScripts extends EnqueueScript {

	/**
	 * @inherit
	 */
	protected $handle = 'admin_main';

	/**
	 * @inherit
	 */
	protected $resource = 'main.min.js';

	/**
	 * @inherit
	 */
	protected $frontend = false;

	/**
	 * @inherit
	 */
	protected $admin = true;
}