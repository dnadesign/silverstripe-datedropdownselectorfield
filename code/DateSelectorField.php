<?php

namespace DNADesign\SilverstripeDatedropdownselectorfield;

use DateTime;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\View\Requirements;

/**
 * @package datedropdownselectorfield
 */
class DateSelectorField extends CompositeField {

	protected $modifier;

	protected $useHeading;

	protected $day;
	protected $month;
	protected $year;

	public function __construct($name, $title = null, $value = null, $modifier = null) {
		$this->name = $name .'[' . $modifier . ']';

		$this->setTitle($title);
		$this->modifier = $modifier;
		$dayArray = array(
			0 => 'Day'
		);
		for($i = 1; $i < 32; $i++) {
			if($i < 10) {
				$dayArray['0' . $i] = $i;
			} else {
				$dayArray[$i] = $i;
			}
		}
		$monthArray = array(
			'0' => 'Month',
			'01' => 'Jan',
			'02' => 'Feb',
			'03' => 'March',
			'04' => 'Apr',
			'05' => 'May',
			'06' => 'June',
			'07' => 'July',
			'08' => 'Aug',
			'09' => 'Sept',
			'10' => 'Oct',
			'11' => 'Nov',
			'12' => 'Dec'
		);
		$now = new DateTime();
		$startYear = $now->format('Y');
		$endYear = $startYear - 105;
		$yearArray = array(
			0 => 'Year'
		);

		for($i = $startYear; $i >= $endYear; $i--) {
			$yearArray[$i] = $i;
		}

		$fields = array(
			$this->day = new DropdownField($this->name . '[Day]', '', $dayArray),
			$this->month = new DropdownField($this->name . '[Month]', '', $monthArray),
			$this->year = new DropdownField($this->name . '[Year]', '', $yearArray)
		);

		foreach($fields as $field) {
			$field->addExtraClass('dateselectorfield');
		}

		if($title) {
			if($this->useHeading) {
				array_unshift($fields, $header = new HeaderField(
					$this->name.$this->modifier.'Title',
					trim($title . ' ' . $this->modifier),
					4
				));
				$header->setAllowHTML(true);
			} else {
				array_unshift($fields, new LiteralField(
					$this->name.$this->modifier.'Title',
					'<label class="left" for="' . $this->name . '">' . trim($title . ' ' . $this->modifier) . '</label>'
				));
			}

		}

		Requirements::css('dnadesign/silverstripe-datedropdownselectorfield: css/admin.css');

		parent::__construct($fields);
		$this->setValue($value);
	}

	public function setUseHeading($bool) {
		$this->useHeading = $bool;
	}

	/**
	 * Set the field name
	 */
	public function setName($name) {
		$this->name = $name . '[' . $this->modifier . ']';
		$this->day->setName($this->name . '[Day]');
		$this->month->setName($this->name . '[Month]');
		$this->year->setName($this->name . '[Year]');
		return $this;
	}

	/**
	 * Returns the fields nested inside another DIV
	 *
	 * @param array
	 * @return html
	 */
	public function FieldHolder($properties = array()) {
		$idAtt = isset($this->id) ? " id=\"{$this->id}\"" : '';
		$className = ($this->columnCount) ? "field CompositeField {$this->extraClass()} multicolumn" : "field CompositeField {$this->extraClass()}";
		$content = "<div class=\"$className\"$idAtt>\n";

		foreach($this->getChildren() as $subfield) {
			if($this->columnCount) {
				$className = "column{$this->columnCount}";
				if(!next($fs)) $className .= " lastcolumn";
				$content .= "\n<div class=\"{$className}\">\n" . $subfield->FieldHolder() . "\n</div>\n";
			} else if($subfield){
				$content .= "\n" . $subfield->FieldHolder() . "\n";
			}
		}
		$message = $this->Message();
		$type = $this->MessageType();

		$content .= (!empty($message)) ? "<span class=\"message $type\">$message</span>" : "";

		$content .= "</div>\n";

		return $content;
	}


	public function hasData() {
		return true;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value, $data = null) {
		if (is_string($value)) {
			$value = new DateTime($value);
		}
		$d = null;
		$m = null;
		$y = null;
		if ($value instanceof DateTime) {
			$d = $value->format('d');
			$m = $value->format('m');
			$y = $value->format('Y');
		} else if (is_array($value)) {
			if (isset($value[$this->modifier . 'Day'])) {
				$d = $value[$this->modifier . 'Day'];
			}
			if (isset($value[$this->modifier . 'Month'])) {
				$m = $value[$this->modifier . 'Month'];
			}
			if (isset($value[$this->modifier . 'Year'])) {
				$y = $value[$this->modifier . 'Year'];
			}
		}
		if ($d && $m && $y) {
			foreach($this->getChildren() as $child) {
				switch($child->getName()) {
					case $this->name.'[' . $this->modifier . 'Day]':
						$child->setValue($d);
						break;
					case $this->name.'[' . $this->modifier . 'Month]':
						$child->setValue($m);
						break;
					case $this->name.'[' . $this->modifier . 'Year]':
						$child->setValue($y);
						break;
				}
			}

			// suffix zero if one is missing for single digit days/months
			if($d < 10 && strlen($d) == 1) {
				$d = '0' . $d;
			}
			if($m < 10 && strlen($m) == 1) {
				$m = '0' . $m;
			}

			$this->value = $y . '-' . $m . '-' . $d;
		}

		return $this;
	}

	function validate($i = null) {
		$children = $this->getChildren();
		$value = true;

		foreach($children as $child) {
			if(get_class($child) == DropdownField::class) {
				if(!($value = $child->Value()) || $value == '') $value = false;
			}
		}

		return $value;
	}
}
