<?php

	class DMail {
		private $mailer;
		private $defaultFromAddress = "fromAdresiniz"; // orn: info@abc.com
		private $defaultFromName = "GondericiAdiniz";
		private $sourcePath = "lib/phpmailer/mail-source";
		
		public function __construct() {
			$this->mailer = new PHPMailer(true);
			$this->mailer->Username = "yourSmtpUserName";
			$this->mailer->Password = "****";
			$this->mailer->Host = "your.smtpserver.com";
			$this->mailer->Port = 465;
			$this->mailer->SMTPSecure = 'ssl';
			$this->mailer->isSMTP();
		}
		
		private function getSourceCode($sourceFileName, $parameters) {
			include($this->sourcePath . "/" . $sourceFileName . ".php");
			if(count($parameters) > 0) {
				for($a = 0; $a < count($parameters); $a++) {
					$key = "{" . $a . "}";
					$value = $parameters[$a];
					$sourceCode = str_replace($key, $value, $sourceCode);
				}
			}
			return $sourceCode;
		}

		public function testMail($toMail, $toName) {

			$this->mailer->addAddress($toMail, $toName);
			
			$parameters = array();
			$parameters[] = $toName;

			$sourceCode = $this->getSourceCode("testMail", $parameters);
			$this->mailer->setFrom($this->defaultFromAddress, $this->defaultFromName);
			$this->mailer->Subject = "Dany MVC Mail";
			$this->mailer->isHTML(true);
			$this->mailer->msgHTML($sourceCode);
			$this->mailer->send();
		}
	}

?>