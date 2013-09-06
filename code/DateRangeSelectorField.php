<?php

/**
 * A {@link FormField} containing two {@link DateSelectorField} instances for 
 * users to select between a range of dates
 *
 * @package datedropdownselectorfield
 */
class DateRangeSelectorField extends CompositeField {

	public function __construct($name, $title = null, $value = null) {
		$this->name = $name;
		$this->setTitle($name);

		parent::__construct(array(
			$from = new DateSelectorField($this->name, $name, null, 'From'),
			$to = new DateSelectorField($this->name, $name, null, 'To'),
		));

		$from->addExtraClass('rangeselectedfrom');
		$to->addExtraClass('rangeselectedto');
		
		$this->setValue($value);
	}

	public function hasData() {
		return true;
	}

	public function isComposite() {
		return false;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		if(is_array($value)) {
			if (isset($value['FromDay'])) {
				$dF = $value['FromDay'];
			}
			
			if (isset($value['FromMonth'])) {
				$mF = $value['FromMonth'];
			}
			
			if (isset($value['FromYear'])) {
				$yF = $value['FromYear'];
			}
			
			if (isset($value['ToDay'])) {
				$dT = $value['ToDay'];
			}
			
			if (isset($value['ToMonth'])) {
				$mT = $value['ToMonth'];
			}
			
			if (isset($value['ToYear'])) {
				$yT = $value['ToYear'];
			}

			$value = $yF . '-' . $mF . '-' . $dF . '-to-' . $yT . '-' . $mT . '-' . $dT;
		}

		if (strpos($value, '-to-') !== FALSE) {
			$valueArray = explode('-to-', $value);
			$i = 0;

			foreach ($this->getChildren() as $child) {
				$child->setValue($valueArray[$i]);
				$i++;
			}

			$this->value = $value;
		}

		return $this;
	}

}