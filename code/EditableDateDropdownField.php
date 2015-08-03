<?php

if(class_exists('EditableDateField')) {
	class EditableDateDropdownField extends EditableFormField {
		
		private static $singular_name = 'Date Dropdown Field';
		
		private static $plural_name = 'Date Dropdown Fields';
		
		public function populateFromPostData($data) {
			$fieldPrefix = 'Default-';
			
			if(empty($data['Default']) && !empty($data[$fieldPrefix.'Year']) && !empty($data[$fieldPrefix.'Month']) && !empty($data[$fieldPrefix.'Day'])) {
				$data['Default'] = $data['Year'] . '-' . $data['Month'] . '-' . $data['Day'];		
			}
			
			parent::populateFromPostData($data);
		}
		
		/**
		 * Return the form field
		 *
		 */
		public function getFormField() {
			$field = DateSelectorField::create($this->Name, $this->Title);

			if ($this->Required) {
				// Required validation can conflict so add the Required validation messages
				// as input attributes
				$errorMessage = $this->getErrorMessage()->HTML();
				$field->setAttribute('data-rule-required', 'true');
				$field->setAttribute('data-msg-required', $errorMessage);
			}
			
			return $field;
		}

		public function getIcon() {
			return 'userforms/images/editabledatefield.png';
		}

		public function getValueFromData($data) {
			$value = (isset($data[$this->Name])) ? $data[$this->Name] : false;

			if($value) {
				$output = '';

				if(isset($value[2]['Year'])) {
					$output = $value[2]['Year'];
				}

				if(isset($value[1]['Month'])) {
					$output .= '-'. $value[1]['Month'];
				}

				if(isset($value[0]['Day'])) {
					$output .= '-'. $value[0]['Day'];
				}

				return $output;
			}
		}
	}
}