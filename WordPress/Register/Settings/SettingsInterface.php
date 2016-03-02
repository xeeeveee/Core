<?php
namespace Xeeeveee\Core\WordPress\Register\Settings;

interface SettingsInterface {

	public function render();

	public function get_settings();

	public function save_settings();
}