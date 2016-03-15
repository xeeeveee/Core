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
		if ( ! empty( $this->handle ) && ( $this->source === false || is_string( $this->source ) && ! empty( $this->source ) ) ) {

			if ( wp_style_is( $this->handle, 'registered' ) ) {
				$handle = $this->handle;
			} else {
				$handle = $this->prefix . $this->handle;
			}

			wp_enqueue_style(
				$handle,
				$this->source,
				$this->dependencies,
				$this->version,
				$this->media
			);
		}
	}

	/**
	 * Set the source of the resource
	 *
	 * @return $this
	 */
	protected function setSource() {
		if ( empty( $this->resource ) ) {
			$this->source = false;
		} else {
			$this->source = $this->scripts_url . $this->location . $this->resource;
		}

		return $this;
	}
}