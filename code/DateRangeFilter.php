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
		$yF = $mF = $dF = 0;
		$yT = $mT = $dT = 0;

		$value = $this->value;

		if(is_array($value)) {
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

	public function apply(DataQuery $query) {
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

	/**
	 * Applies a match on the starting characters of a field value.
	 *
	 * @return DataQuery
	 */
	protected function applyOne(DataQuery $query) {
		return true;
	}

	/**
	 * Applies a match on the starting characters of a field value.
	 *
	 * @return DataQuery
	 */
	protected function applyMany(DataQuery $query) {
		return true;
	}

	/**
	 * Excludes a match on the starting characters of a field value.
	 *
	 * @return DataQuery
	 */
	protected function excludeOne(DataQuery $query) {
		return true;
	}
}