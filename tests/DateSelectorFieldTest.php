<?php

namespace DNADesign\SilverstripeDatedropdownselectorfield\Tests;

use DNADesign\SilverstripeDatedropdownselectorfield\DateSelectorField;
use SilverStripe\Dev\SapphireTest;
use DateTime;

class DateSelectorFieldTest extends SapphireTest {

	public function testSetValueDateTime() {
		$field = new DateSelectorField('Date');

		$field->setValue(new DateTime('1980-01-01'));
		$this->assertEquals('1980-01-01', $field->getValue());

		$children = $field->getChildren();
		$this->assertEquals('01', $children[0]->dataValue());
		$this->assertEquals('01', $children[1]->dataValue());
		$this->assertEquals('1980', $children[2]->dataValue());
	}

	public function testSetValueString() {
		$field = new DateSelectorField('Date');

		$field->setValue('1980-1-1');
		$this->assertEquals('1980-01-01', $field->getValue());

		$children = $field->getChildren();
		$this->assertEquals('01', $children[0]->dataValue());
		$this->assertEquals('01', $children[1]->dataValue());
		$this->assertEquals('1980', $children[2]->dataValue());

		$field->setValue('1980-01-01');
		$this->assertEquals('1980-01-01', $field->getValue());

		$children = $field->getChildren();
		$this->assertEquals('01', $children[0]->dataValue());
		$this->assertEquals('01', $children[1]->dataValue());
		$this->assertEquals('1980', $children[2]->dataValue());
	}

	public function testSetValueArray() {
		$field = new DateSelectorField('Date');

		$field->setValue(array(
			'Day' => 2,
			'Month' => 10,
			'Year' => 2000
		));

		$children = $field->getChildren();
		$this->assertEquals('02', $children[0]->dataValue());
		$this->assertEquals('10', $children[1]->dataValue());
		$this->assertEquals('2000', $children[2]->dataValue());

		$this->assertEquals('2000-10-02', $field->getValue());
	}

}
