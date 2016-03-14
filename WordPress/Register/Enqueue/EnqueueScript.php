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
		if ( ! empty( $this->handle ) && ( $this->source === false || ! empty( $this->source ) ) ) {
			wp_enqueue_script( $this->prefix . $this->handle, $this->source, $this->dependencies, $this->version, $this->footer );
		}
	}

	/**
	 * Set the source of the resource
	 *
	 * @return $this
	 */
	protected function setSource() {
		$this->source = $this->scripts_url . $this->location . $this->resource;

		return $this;
	}
}