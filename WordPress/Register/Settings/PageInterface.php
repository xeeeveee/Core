<?php
namespace Xeeeveee\Core\WordPress\Register\Settings;

interface PageInterface {

	public function render();

	public function get_settings();

	public function save_settings();
}