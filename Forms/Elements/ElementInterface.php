<?php

namespace Xeeeveee\Core\Forms\Elements;

interface ElementInterface {

	public function setName( $name );

	public function getName();

	public function getType();

	public function setAttributes( array $attributes = [ ] );

	public function addAttributes( array $attributes = [ ], $override = true );

	public function getAttributes();

	public function clearAttributes();

	public function setLabel( $label );

	public function getLabel();

	public function setValue( $value );

	public function getValue();

	public function clearValue();

	public function getTooltip();

	public function setTooltip( $tooltip );

	public function setTooltipLocation( $location );

	public function getTooltipLocation();

	public function setBlockWrappers( array $wrappers = [ ] );

	public function addBlockWrappers( array $wrappers = [ ], $override = true );

	public function getBlockWrappers();

	public function clearBlockWrappers();

	public function setElementWrappers( array $wrappers = [ ] );

	public function addElementWrappers( array $wrappers = [ ], $override = true );

	public function getElementWrappers();

	public function clearElementWrappers();

	public function getHtml();

	public function getElementHtml();

	public function getTooltipHtml();

	public function getPreBlockHtml();

	public function getPostBlockHtml();

	public function getPreElementHtml();

	public function getPostElementHtml();
}