<?php
    namespace lib\DForm;

    class DForm {
        private $path = "lib/dform";

        public function getForm($formName) {
            $formPath = $this->path . "/" . $formName . ".php";
            if(file_exists($formPath)) {
                include($formPath); // esas oglan gelsin
                $formObjectName = ucfirst($formName);
                $form = new $formObjectName();
                return $form;
            }
            return null;
        }

        // formu post datalari ile doldurur
        // formun propertyleri ile html form propertylerinin
        // isimlerinin ayni olmasi gerekiyor
        public function fillForm(&$form) {
            foreach(array_keys($_POST) as $key){
                $form->$key = $_POST[$key];
            }
        }
    }
?>