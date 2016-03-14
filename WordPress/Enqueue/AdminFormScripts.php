<?php

namespace Xeeeveee\Core\WordPress\Enqueue;

use Xeeeveee\Core\WordPress\Register\Enqueue\EnqueueScript;

class AdminFormScripts extends EnqueueScript {

	/**
	 * @inherit
	 */
	protected $handle = 'admin_forms';

	/**
	 * @inherit
	 */
	protected $frontend = false;

	/**
	 * @inherit
	 */
	protected $admin = true;

	/**
	 * @inherit
	 */
	protected function setSource() {
		$this->source = $this->scripts_url . 'AdminForms.min.js';
	}
}