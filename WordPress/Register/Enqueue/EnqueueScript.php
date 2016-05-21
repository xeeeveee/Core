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
	 * @inherit
	 */
	protected $type = 'script';

	/**
	 * Enqueues the style
	 */
	public function enqueue() {
		if ( ! empty( $this->handle ) && ( $this->source === false || is_string( $this->source ) && ! empty( $this->source ) ) ) {

			if ( wp_script_is( $this->handle, 'registered ' ) ) {
				$handle = $this->handle;
			} else {
				$handle = $this->prefix . $this->handle;
			}

			wp_enqueue_script(
					$handle,
					$this->source,
					$this->dependencies,
					$this->version,
					$this->footer
			);
		}
	}
}