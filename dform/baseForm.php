<?php
    namespace lib\DForm;

    class BaseForm {

        protected $valid = true;
        protected $isSend = false;
        protected $errList = array();

        public function beginForm($formName, $actionUrl, $method = "POST", $extras = "", $fileUpload = false, $defaultValidation = true, $manuelValidation = "", $target = "") {
            $code = '<form name="' . $formName . '" id="' . $formName . '" action="' . $actionUrl . '" method="' . $method . '" ';
            if($extras) {
                $code .= $extras;
            }

            if($fileUpload) {
                $code .= ' enctype="multipart/form-data" ';
            }

            if($defaultValidation) {
                $code .= ' onsubmit="return vFormValidate(\'' . $formName . '\', \'' . $manuelValidation . '\')"';
            } else {
                if($manuelValidation) {
                    $code .= ' onsubmit="return ' . $manuelValidation . '()" ';
                }
            }

            if($target) {
                $code .= ' target="' . $target . '" ';
            }

            echo $code . '>
        ';
        }

        public function endForm($formName) {
            echo "</form><script>markVFormRequiredFieldsLabels('" . $formName . "');</script>";
        }

        /*
        * 	Verilen object listesinden selectbox icin optionlari olusturur.
        */
        public function createSelectOption($list, $labelFieldName, $valueFieldName, $selectedValue = "") {
            if(!empty($list)) {
                foreach ($list as $o) {
                    echo '<option value="' . $o->$valueFieldName . '" ';
                    if($o->$valueFieldName == $selectedValue) {
                        echo ' selected';
                    }
                    echo '>' . $o->$labelFieldName . '</option>';
                }
            }
        }
        /* VALIDATIONS */
        public function requiredValidation() {
            if($this->required != null && count($this->required) > 0) {
                foreach ($this->required as $property) {
                    if($this->$property == null || trim($this->$property) == '' || strlen(trim($this->$property)) == 0) {
                        addToError("error.field.mustFill", array($property));
                        $this->valid = false;
                    }
                }
            }
        }

        public function mustIntValidation() {
            if($this->mustInt != null && count($this->mustInt) > 0) {
                foreach ($this->mustInt as $property) {
                    if(!is_int( (int) $this->$property)) {
                        addToError("error.field.mustBeInt", array($property));
                        $this->valid = false;
                    }
                }
            }
        }

        public function equalValidation() {
            if(!empty($this->mustEqual) && count($this->mustEqual) > 0) {
                foreach ($this->mustEqual as $items) {
                    $field1 = $items[0];
                    $field2 = $items[1];
                    if($field1 && $field2 && ( $this->$field1 != $this->$field2 ) ) {
                        addToError("error.equalValidationErr", array($field1, $field2));
                    }
                }
            }
        }

        // arttirilmali

        /* getter & setter */
        public function __get($p) {
            return $this->$p;
        }

        public function __set($p, $v) {
            $this->$p = $v;
        }

    }

?>