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

	/**
	 * Set the source of the resource
	 *
	 * @return $this
	 */
	protected function setSource() {
		if ( empty( $this->resource ) ) {
			$this->source = false;
		} elseif ( strpos( $this->resource, '/' ) !== false ) {
			$this->source = $this->resource;
		} else {
			$this->source = $this->scripts_url . $this->location . $this->resource;
		}

		return $this;
	}
}