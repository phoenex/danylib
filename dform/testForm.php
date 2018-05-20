<?php
    namespace lib\DForm;

    class TestForm extends BaseForm {

        private $id;
        private $label;

        protected $required = array("label");
        protected $mustInt = array("id");

        public function __construct(){ }

        public function fill($o) {
            $this->id = $o->id;
            $this->label = $o->label;
        }

        public function setObject(&$o) {
            $o->id = $this->id;
            $o->label = $this->label;
        }

        public function isValid() {
            $this->requiredValidation();
            $this->mustIntValidation();
            return $this->valid;
        }

        /* getter & setter */
        public function __get($p) {
            return $this->$p;
        }

        public function __set($p, $v) {
            $this->$p = $v;
        }

    }

?>