<?php

namespace Xeeeveee\Core\WordPress\Register\Forms;

abstract class Form implements FormInterface {

	/**
	 * The form object
	 *
	 * @var \Xeeeveee\Core\Forms\FormInterface
	 */
	protected $form;

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
	 * The form values
	 *
	 * @var array
	 */
	protected $values = [ ];

	/**
	 * Prepares the form
	 */
	public function __construct() {
		$this->form = new \Xeeeveee\Core\Forms\Form( [ ] );
		$this->form->setAction( $this->get_action() );
		$this->form->setMethod( $this->get_method() );
		$this->form->addAttributes( $this->get_attributes() );
		$this->form->addElements( $this->get_elements() );
		$this->form->addElements( $this->get_values() );
	}

	/**
	 * Get the action
	 *
	 * @return string
	 */
	protected function get_action() {
		return $this->action;
	}

	/**
	 * Get the method
	 *
	 * @return string
	 */
	protected function get_method() {
		return $this->method;
	}

	/**
	 * Get the attributes
	 *
	 * @return array
	 */
	protected function get_attributes() {
		return $this->attributes;
	}

	/**
	 * Get the elements
	 *
	 * @return array
	 */
	protected function get_elements() {
		return $this->elements;
	}

	/**
	 * Get the values
	 *
	 * @return mixed
	 */
	protected function get_values() {
		return $this->values;
	}
}