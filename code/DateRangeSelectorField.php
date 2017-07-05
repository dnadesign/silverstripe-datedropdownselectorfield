<?php

/**
 * A {@link FormField} containing two {@link DateSelectorField} instances for
 * users to select between a range of dates
 *
 * @package datedropdownselectorfield
 */
class DateRangeSelectorField extends CompositeField {

	protected $from;

	protected $to;

	public function __construct($name, $title = null, $value = null) {
		$this->name = $name;
		$this->setTitle($title);

		parent::__construct(array(
			$this->from = new DateSelectorField($this->name, $title, null, 'From'),
			$this->to = new DateSelectorField($this->name, $title, null, 'To'),
		));

		$this->from->addExtraClass('rangeselectedfrom');
		$this->to->addExtraClass('rangeselectedto');

		$this->setValue($value);
	}

	public function hasData() {
		return true;
	}

	public function getValue() {
		return $this->value;
	}

	/**
	 * Set the field name
	 */
	public function setName($name) {
		$this->name = $name;
		$this->from->setName($name);
		$this->to->setName($name);
		return $this;
	}

	public function setValue($value, $data = null) {
		if(is_array($value)) {
			$yF = 0;
			$mF = 0;
			$dF = 0;
			$yT = 0;
			$mT = 0;
			$dT = 0;

			if (isset($value['From']['Day'])) {
				$dF = $value['From']['Day'];
			}

			if (isset($value['From']['Month'])) {
				$mF = $value['From']['Month'];
			}

			if (isset($value['From']['Year'])) {
				$yF = $value['From']['Year'];
			}

			if (isset($value['To']['Day'])) {
				$dT = $value['To']['Day'];
			}

			if (isset($value['To']['Month'])) {
				$mT = $value['To']['Month'];
			}

			if (isset($value['To']['Year'])) {
				$yT = $value['To']['Year'];
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
