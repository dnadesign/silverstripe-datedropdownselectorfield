<?php

/**
 * @package datedropdownselectorfield
 */
class DateRangeFilter extends SearchFilter {

	private $min, $max;

	public function findMinMax() {
		if (!isset($this->value)) {
			return false;
		}

		$value = $this->value;

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

			$from = 0;
			$to = 0;
			
			if ($yF != 0 && $mF != 0 && $dF != 0) {
				$from = $yF . '-' . $mF . '-' . $dF;
			}
			
			if ($yT != 0 && $mT != 0 && $dT != 0) {
				$to = $yT . '-' . $mT . '-' . $dT;
			}

			$value = $from . '-to-' . $to;
		}
		
		if (strpos($value, '-to-') !== FALSE) {
			$valueArray = explode('-to-', $value);
			
			if ($valueArray[0] != 0) {
				$this->setMin($valueArray[0]);
			}

			if ($valueArray[1] != 0) {
				$this->setMax($valueArray[1]);
			}
		}
	}

	public function setMin($min) {
		$this->min = $min;
	}

	public function setMax($max) {
		$this->max = $max;
	}

	public function apply(SQLQuery $query) {
		if (!isset($this->min) || !isset($this->max)) {
			$this->findMinMax();
		}

		if ($this->min && $this->max) {
			$query->where(sprintf(
				"%s >= '%s' AND %s <= '%s'",
				$this->getDbName(),
				Convert::raw2sql($this->min),
				$this->getDbName(),
				Convert::raw2sql($this->max)
			));
		} else if ($this->min) {
			$query->where(sprintf(
				"%s >= '%s'",
				$this->getDbName(),
				Convert::raw2sql($this->min)
			));
		} else if ($this->max) {
			$query->where(sprintf(
				"%s <= '%s'",
				$this->getDbName(),
				Convert::raw2sql($this->max)
			));
		}
	}
}