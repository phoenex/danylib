<?php
	namespace lib\di18n;
	/**
	* Class Di18n
	* @author Cenk Atesdagli
	* @mail phoenex@gmail.com
	*/
	
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	
	
	class Di18n {
		private $lang;
		private $values = array();
		private $validLanguageSet = array("tr");
		private $defaultLanguageIndex = 0;
		private $param = "lang";
		private $_languageFileFolder = "lib/di18n";
	
		public function __construct() {
			$this->loadTranslateFile();
		}
		 
		public function get($key) {
			$value = "??? no value ???";
			if($this->values != null) {
				$value = $this->values[$key];
				if(!$value) {
					$value = "??? " . $this->key . " ???";
				}
			}
			return $value;
		}
		
		public function write($key) {
			echo $this->get($key);
		}
		 
		public function set($languageCode) {
			$languageCode = htmlentities($languageCode);
			if(!$this->isValidLanguage($languageCode)) {
				$languageCode = $this->validLanguageSet[ $defaultLanguageIndex ];
			}
			$this->setLanguageToSession($languageCode);
		}
		 
		public function getParam() {
			return $this->param;
		}
		 
		private function checkLanguageInSession() {
			if (!isset($_SESSION[ $this->param ])) {
				$_SESSION[ $this->param ] = $this->validLanguageSet[ $this->defaultLanguageIndex ];
			}
			$this->lang = $_SESSION[ $this->param ];
		}
		 
		private function loadTranslateFile() {
			$this->checkLanguageInSession();
			$path = $this->_languageFileFolder . "/" . $this->lang . "/labels.php";
			if(file_exists($path)) {
				$values = require_once($path);
				$this->values = array_merge($this->values, $values);
			}
		}
		 
		private function setLanguageToSession($languageCode) {
			$_SESSION[ $this->param ] = $languageCode;
		}
		 
		private function isValidLanguage($languageCode) {
			$isInValidLanguages = array_search($languageCode, $this->validLanguageSet);
			return $isInValidLanguages;
		}
		 
	}
?>