<?php

namespace Xeeeveee\Core\WordPress\Register\Enqueue;

abstract class EnqueueScript extends Enqueue {

	/**
	 * @var bool
	 *
	 * Whether to enqueue the script in the footer
	 */
	protected $footer = false;

	/**
	 * Enqueues the style
	 */
	public function enqueue() {
		if ( ! empty( $this->handle ) && ! empty( $this->source ) ) {
			wp_enqueue_script( $this->prefix . $this->handle, $this->source, $this->dependencies, $this->version, $this->footer );
		}
	}
}