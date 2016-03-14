<?php

namespace Xeeeveee\Core\Forms;

use Soap\Provenue\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Exceptions\ElementNotFoundException;
use Xeeeveee\Core\Exceptions\NotStringException;
use Xeeeveee\Core\Forms\Elements\Nonce;
use Xeeeveee\Core\Utility\Tag;

class Form extends Tag implements FormInterface {

	use ConfigurationTrait;

	/**
	 * The name of the form
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * The elements attached to this form
	 *
	 * @var array
	 */
	protected $elements = [ ];

	/**
	 * The form method
	 *
	 * @var string
	 */
	protected $method = 'POST';

	/**
	 * The form action
	 *
	 * @var string
	 */
	protected $action = '';

	/**
	 * The nonce field for the form
	 *
	 * @var Nonce
	 */
	protected $nonce;

	/**
	 * Reserved attributes
	 *
	 * These will be ignored if added as attributes
	 *
	 * @var array
	 */
	protected $reserved_attributes = [
		'method',
		'action'
	];

	/**
	 * The element wrappers
	 *
	 * @var array
	 */
	protected $wrappers = [
		'block'   => '',
		'element' => ''
	];

	/**
	 * Reserved element types
	 *
	 * These will not have values mass assigned
	 *
	 * @var array
	 */
	protected $protected_element_types = array(
		'submit',
		'button',
		'html',
		'nonce'
	);

	/**
	 * Prepares a new form
	 *
	 * @param $name
	 * @param array $args
	 *
	 * @throws NotStringException
	 */
	public function __construct( $name, array $args = [ ] ) {

		$this->set_name( $name );

		/*
		 * Format: <base>/form/<name>/args
		 */
		$args = apply_filters( $this->filter_base . 'form/global/args', $args['elements'], $this->name, $this );
		$args = apply_filters( $this->filter_base . 'form/' . $this->name . '/args', $args, $this->name, $this );

		if ( ! isset( $args['elements'] ) ) {
			$args['elements'] = [ ];
		}

		if ( ! isset( $args['attributes'] ) ) {
			$args['attributes'] = [ ];
		}

		if ( ! isset( $args['wrappers'] ) ) {
			$args['wrappers'] = [ ];
		}

		if ( ! isset( $args['method'] ) ) {
			$args['method'] = '';
		}

		if ( ! isset( $args['action'] ) ) {
			$args['action'] = '';
		}

		if ( ! isset( $args['values'] ) ) {
			$args['values'] = [ ];
		}

		/*
		 * Format: <base>/form/<name>/elements
		 */
		$elements = apply_filters( $this->filter_base . 'form/global/elements', $args['elements'], $this->name, $this );
		$elements = apply_filters( $this->filter_base . 'form/' . $this->name . '/elements', $elements, $this->name, $this );
		$this->set_elements( $elements );

		/*
		 * Format: <base>/form/<name>/attributes
		 */
		$attributes = apply_filters( $this->filter_base . 'form/global/attributes', $args['attributes'], $this->name, $this );
		$attributes = apply_filters( $this->filter_base . 'form/' . $this->name . '/attributes', $attributes, $this->name, $this );
		$this->set_attributes( $attributes );

		/*
		 * Format: <base>/form/<name>/wrappers
		 */
		$wrappers = apply_filters( $this->filter_base . 'form/global/wrappers', $args['wrappers'], $this->name, $this );
		$wrappers = apply_filters( $this->filter_base . 'form/' . $this->name . '/wrappers', $wrappers, $this->name, $this );
		$this->set_wrappers( $wrappers );

		/*
		 * Format: <base>/form/<name>/method
		 */
		$method = apply_filters( $this->filter_base . 'form/global/method', $args['method'], $this->name, $this );
		$method = apply_filters( $this->filter_base . 'form/' . $this->name . '/method', $method, $this->name, $this );
		$this->set_method( $method );

		/*
		 * Format: <base>/form/<name>/action
		 */
		$action = apply_filters( $this->filter_base . 'form/global/action', $args['action'], $this->name, $this );
		$action = apply_filters( $this->filter_base . 'form/' . $this->name . '/action', $action, $this->name, $this );
		$this->set_action( $action );

		/*
		 * Format: <base>/form/<name>/values
		 */
		$values = apply_filters( $this->filter_base . 'form/global/values', $args['values'], $this->name, $this );
		$values = apply_filters( $this->filter_base . 'form/' . $this->name . '/values', $values, $this->name, $this );
		$this->set_values( $values );

		$this->nonce = new Nonce( $this->name );
	}

	/**
	 * Sets the name
	 *
	 * @param string $name
	 *
	 * @return $this
	 * @throws NotStringException
	 */
	public function set_name( $name ) {
		if ( is_string( $name ) ) {
			$this->name = $name;
		} else {
			throw new NotStringException( 'The parameter $name must be of type string, ' . gettype( $name ) . ' given.' );
		}

		return $this;
	}

	/**
	 * Get the name
	 *
	 * @return string
	 */
	public function get_name() {
		return $name;
	}

	/**
	 * Sets the action
	 *
	 * @param string $action
	 *
	 * @return bool
	 */
	public function set_action( $action ) {
		if ( is_string( $action ) ) {
			$this->action = $action;
		}

		return $this;
	}

	/**
	 * Get the action
	 *
	 * @return string
	 */
	public function get_action() {
		return $this->action;
	}

	/**
	 * Sets the method
	 *
	 * @param string $method
	 *
	 * @return $this
	 */
	public function set_method( $method ) {
		if ( is_string( $method ) ) {
			$this->method = strtoupper( $method );
		}

		return $this;
	}

	/**
	 * Get the method
	 *
	 * @return string
	 */
	public function get_method() {
		return $this->method;
	}

	/**
	 * Set the guarded types
	 *
	 * @param array $protected_element_types
	 *
	 * @return $this
	 */
	public function set_protected_element_types( array $protected_element_types = [ ] ) {
		$this->protected_element_types = $protected_element_types;

		return $this;
	}

	/**
	 * Get the guarded types
	 *
	 * @return array
	 */
	public function get_protected_element_types() {
		return $this->protected_element_types;
	}

	/**
	 * Sets the values
	 *
	 * Will clear existing value is replacement is not set
	 *
	 * @param array $values the values to set
	 *
	 * @return $this
	 */
	public function set_values( array $values = [ ] ) {
		foreach ( $this->elements as $element ) {
			if ( ! in_array( $element->getType(), $this->protected_element_types )
			     && isset( $values[ $element->getName() ] )
			) {
				$element->setValue( $values[ $element->getName() ] );
			} else {
				$element->clearValue();
			}
		}

		return $this;
	}

	/**
	 * Adds values
	 *
	 * Adds values to the form, does not clear existing values
	 *
	 * @param array $values
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function add_values( array $values = [ ], $override = true ) {
		foreach ( $this->elements as $element ) {
			if ( ! in_array( $element->type, $this->protected_element_types ) && isset( $values[ $element->name ] ) ) {
				$element->setValue( $values[ $element->name ] );
			}
		}

		return $this;
	}

	/**
	 * Get the element values
	 *
	 * @return array
	 */
	public function get_values() {
		$values = [ ];

		foreach ( $this->elements as $element ) {
			if ( ! in_array( $element->type, $this->protected_element_types ) ) {
				$values[] = $element->getValue();
			}
		}

		return $values;
	}

	/**
	 * Clears all the element values
	 *
	 * @return bool true on success
	 */
	public function clear_values() {
		foreach ( $this->elements as $element ) {
			if ( ! in_array( $element->type, $this->protected_element_types ) ) {
				$element->clearValue();
			}
		}

		return $this;
	}

	/**
	 * Sets the elements
	 *
	 * This differs from add_elements in that is erases the existing elements before adding the new ones
	 *
	 * @param array $elements
	 *
	 * @return $this
	 */
	public function set_elements( array $elements = [ ] ) {
		$this->elements = [ ];

		foreach ( $elements as $element ) {
			$this->add_element( $element );
		}

		return $this;
	}

	/**
	 * Adds the elements
	 *
	 * This differs from set_elements in that is does not erase the existing elements before adding the new ones
	 *
	 * @param array $elements
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function add_elements( array $elements = [ ], $override = true ) {
		foreach ( $elements as $element ) {
			$this->add_element( $element, $override );
		}

		return $this;
	}

	/**
	 * Adds the element
	 *
	 * This differs from setElement in that is does not erase the existing element before adding the new ones
	 *
	 * @throws ElementNotFoundException
	 *
	 * @param array $element
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function add_element( array $element, $override = true ) {
		if ( isset( $element['type'] ) && isset( $element['name'] ) ) {
			if ( $override == false ) {
				if ( isset( $this->elements[ $element['name'] ] ) ) {
					return $this;
				}
			}

			$class = ucfirst( $element['type'] );
			$class = __NAMESPACE__ . '\\Elements\\' . $class;

			if ( class_exists( $class ) ) {
				$this->elements[ $element['name'] ] = new $class( $element['name'], $element );
			} else {
				throw new ElementNotFoundException( 'Field type "' . ucfirst( $element['type'] ) . '" does not exist' );
			}
		}

		return $this;
	}

	/**
	 * Get the elements
	 *
	 * @return array
	 */
	public function get_elements() {
		return $this->elements;
	}

	/**
	 * Gets a specific element
	 *
	 * @param string $name
	 *
	 * @return \Xeeeveee\Core\Forms\Elements\ElementInterface|bool
	 */
	public function get_element( $name ) {
		if ( isset( $this->elements[ $name ] ) ) {
			return $this->elements[ $name ];
		}

		return false;
	}

	/**
	 * Clears the elements
	 *
	 * @return $this
	 */
	public function clear_elements() {
		unset( $this->elements );

		return $this;
	}

	/**
	 * Sets the element wrappers
	 *
	 * @param array $wrappers
	 *
	 * @return $this
	 */
	public function set_wrappers( array $wrappers ) {
		$this->wrappers = [ ];

		if ( isset( $wrappers['block'] ) && is_array( $wrappers['block'] ) ) {
			$this->wrappers['block'] = $wrappers['block'];
		}

		if ( isset( $wrappers['element'] ) && is_array( $wrappers['element'] ) ) {
			$this->wrappers['element'] = $wrappers['element'];
		}

		foreach ( $this->elements as $element ) {
			if ( is_array( $this->wrappers['block'] ) && ! empty( $this->wrappers['block'] ) ) {
				$element->addBlockWrappers( $this->wrappers['block'] );
			}

			if ( is_array( $this->wrappers['element'] ) && ! empty( $this->wrappers['element'] ) ) {
				$element->setElementWrappers( $this->wrappers['element'] );
			}
		}

		return $this;
	}

	/**
	 * Get the form wrappers
	 *
	 * @return array
	 */
	public function get_wrappers() {
		$wrappers = [ ];

		foreach ( $this->elements as $element ) {
			$wrappers[ $element->getName() ] = [
				'block'   => $element->getBlockWrappers(),
				'element' => $element->get_elementWrappers()
			];
		}

		return $wrappers;
	}

	/**
	 * Get the nonce field
	 *
	 * @return Nonce
	 */
	public function get_nonce_field() {
		return $this->nonce;
	}

	/**
	 * Get the nonce field's action
	 *
	 * @return string
	 */
	public function get_nonce_action() {
		return $this->nonce->get_name();
	}

	/**
	 * Get the nonce fields name
	 *
	 * TODO: Ideally want to get this from the field, but the naming doesn't conform nicely
	 *
	 * @return string
	 */
	public function get_nonce_name() {
		return $this->nonce->get_name() . '_' . $this->nonce->get_type();
	}

	/**
	 * Gets the html to render the form
	 *
	 * @return string
	 */
	public function get_html() {
		$html = $this->get_form_opening_html();
		$html .= $this->get_elements_html();
		$html .= $this->get_form_closing_html();

		return $html;
	}

	/**
	 * Gets the elements without the form wrapper
	 *
	 * @return string
	 */
	public function get_elements_html() {
		$html = '';
		foreach ( $this->elements as $element ) {
			$html .= $element->get_html();
		}

		return $html;
	}

	/**
	 * Get form opening tag
	 *
	 * @return string
	 */
	public function get_form_opening_html() {
		$html = '<form ';

		if ( ! empty( $this->method ) ) {
			$html .= 'method="' . $this->method . '" ';
		}

		if ( ! empty( $this->action ) ) {
			$html .= 'action="' . $this->action . '" ';
		}

		if ( ! empty( $this->attributes ) ) {
			foreach ( $this->attributes as $key => $val ) {
				$values = join( ' ', $val );
				$html .= $key . '="' . $values . '" ';
			}
		}

		$html .= '>';

		return $html;
	}

	/**
	 * Get form closing tag
	 *
	 * @return string
	 */
	public function get_form_closing_html() {
		$html = $this->nonce->get_html();
		$html .= '</form>';

		return $html;
	}
}