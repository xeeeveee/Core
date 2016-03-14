<?php

namespace Xeeeveee\Core\WordPress\Register\Enqueue;

abstract class EnqueueStyle extends Enqueue {

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
		if ( ! empty( $this->handle ) && ( $this->source === false || ! empty( $this->source ) ) ) {
			wp_enqueue_style( $this->prefix . $this->handle, $this->source, $this->dependencies, $this->version, $this->media );
		}
	}

	/**
	 * Set the source of the resource
	 *
	 * @return $this
	 */
	protected function setSource() {
		$this->source = $this->styles_url . $this->location . $this->resource;

		return $this;
	}
}