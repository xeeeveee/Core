<?php

namespace Xeeeveee\Core\WordPress\Register\Forms;

abstract class Form implements FormInterface {

	/**
	 * The form object
	 *
	 * @var \Xeeeveee\Core\Forms\FormInterface
	 */
	private $form;

	/**
	 * The name of the form
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * The form method
	 *
	 * @var string
	 */
	protected $action = '';

	/**
	 * The form method
	 *
	 * @var string
	 */
	protected $method = 'POST';

	/**
	 * The elements attached to this form
	 *
	 * @var array
	 */
	protected $elements = [ ];

	/**
	 * The attributes to be added to the html tag
	 *
	 * @var array
	 */
	protected $attributes = [ ];

	/**
	 * Prepares the form
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [ ] ) {

		$this->form = new \Xeeeveee\Core\Forms\Form( $this->name, [ ] );

		$this->form->set_action( $this->set_action() );
		$this->form->set_method( $this->set_method() );
		$this->form->set_attributes( $this->set_attributes() );
		$this->form->set_elements( $this->set_elements() );

		if ( isset( $args['values'] ) && is_array( $args['values'] ) ) {
			$this->form->set_values( $args['values'] );
		}

		return $this;
	}

	/**
	 * Get the constructed form
	 *
	 * @return \Xeeeveee\Core\Forms\FormInterface
	 */
	public function get_form() {
		return $this->form;
	}

	/**
	 * Get the action
	 *
	 * @return string
	 */
	protected function set_action() {
		return $this->action;
	}

	/**
	 * Get the method
	 *
	 * @return string
	 */
	protected function set_method() {
		return $this->method;
	}

	/**
	 * Get the attributes
	 *
	 * @return array
	 */
	protected function set_attributes() {
		return $this->attributes;
	}

	/**
	 * Get the elements
	 *
	 * @return array
	 */
	protected function set_elements() {
		return $this->elements;
	}
}