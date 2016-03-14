<?php

namespace Xeeeveee\Core\WordPress\Register\Enqueue;

class EnqueueStyle extends Enqueue {

	/**
	 * @var string
	 *
	 * The media type the style is intended for
	 */
	protected $media = 'all';

	/**
	 * Enqueues the style
	 */
	public function enqueue() {
		if ( ! empty( $this->handle ) && ! empty( $this->source ) ) {
			wp_enqueue_style( $this->prefix . $this->handle, $this->source, $this->dependencies, $this->version, $this->media );
		}
	}
}