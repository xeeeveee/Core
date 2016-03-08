<?php

namespace Xeeeveee\Core\Forms;

interface FormInterface {

	public function setAction( $action );

	public function getAction();

	public function setMethod( $method );

	public function getMethod();

	public function setReservedAttributes( array $attributes );

	public function getReservedAttributes();

	public function setProtectedElementTypes( array $protectedElementTypes = [ ] );

	public function getProtectedElementTypes();

	public function setAttributes( array $attributes = [ ] );

	public function addAttributes( array $attributes = [ ], $override = true );

	public function getAttributes();

	public function clearAttributes();

	public function setValues( array $values = [ ] );

	public function addValues( array $values = [ ], $override = true );

	public function getValues();

	public function clearValues();

	public function setElements( array $elements = [ ] );

	public function addElements( array $elements = [ ], $override = true );

	public function addElement( array $elements, $override = true );

	public function getElements();

	public function clearElements();

	public function setWrappers( array $wrappers );

	public function getWrappers();

	public function getHtml();

	public function getElementsHtml();

	public function getFormOpeningHtml();

	public function getFormClosingHtml();
}