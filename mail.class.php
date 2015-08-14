<?php
	class Mail {
	    const DEFAULT_SENDER_EMAIL = "abfrage3@simsso.de";
	
	    public $to;
	    public $subject;
	    public $body;
	    public $header;
		
		protected $from;
		protected $reply_to;
	
		// Mail class constructor
	    public function __construct($to, $from, $reply_to, $subject, $body) {
	    	$this->from = $from;
			$this->reply_to = $reply_to;
			
	    	// set to default value if no $from has been passed
	    	if (is_null($this->from)) {
	    		$this->from = self::DEFAULT_SENDER_EMAIL;
	    	}
	    	
	    	// set $reply_to to $from if no reply address has been passed
	    	if (is_null($this->reply_to)) {
	    		$this->reply_to = $this->from;
	    	}
	    	
	    	// set attributes
	        $this->to = $to;
	        $this->subject = $subject;
	        $this->body = $body;
	
			// default header
	        $this->update_header();
	    }
	    
		protected function update_header() {
			$this->header = "From: " . $this->from . "\r\nReply-To: " . $this->reply_to . "\r\n";
		}
	
	    function send() {
	        mail($this->to, $this->subject, $this->body, $this->header);
	    }
		
		static function get_email_confirmation_mail($name, $email, $key) {
			$text = 'You have created an Abfrage3 account. Confirm your email address by clicking the following link:<br>
			<a href="http://abfrage3.simsso.de/?email=' . $email . '&email_confirmation_key=' . $key . '">http://abfrage3.simsso.de/?email=' . $email . '&email_confirmation_key=' . $key . '</a></p>
			<p>If you have not created an account simply ignore this email.';
			return new Default_Client_HTML_Mail($email, "Abfrage3 email confirmation", $name, $text);
		}
	}
	
	// HTML mail
	class HTML_Mail extends Mail {
	    public function __construct($to, $from, $reply_to, $subject, $body) {
	    	// call default constructor
	        parent::__construct($to, $from, $reply_to, $subject, $body);
	        
	        // set different header with HTML information
	        $this->update_header();
	    }
	    
		protected function update_header() {
			$this->header = "From: " . $this->from . "\r\nReply-To: " . $this->reply_to . "\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=ISO-8859-1\r\n";
		}
	}
	
	// default HTML mail for users
	class Default_Client_HTML_Mail extends HTML_Mail {
		public function __construct($to, $subject, $name, $text) {
			$body ='
<html>
	<body style="font-family: arial; background-color: #ECEFF1; margin: 0; padding: 0;">
		<nav style="position: relative;
		    height: 56px;
		    width: 100%;
		    padding: 25px;
		    background-color: #8892BF;
		    border-style: solid;
		    border-width: 0 0 6px 0;
		    border-color: #4F5B93;">
			<div><a href="http://abfrage3.simsso.de"><img src="http://abfrage3.simsso.de/img/logo-56.png" style="margin-left: 5px;" alt="Abfrage3"></a></div>
		</nav>
		<div style="padding: 20px 50px;">
			<h4>' . $subject . '</h4>
			<h3>Hey ' . $name . '!</h3>
			<p>' . $text . '</p>
			<p>Best regards, <br>Your Abfrage3 Team</p>
			<hr style="margin-top: 50px; background-color: #777777; height: 1px; border: 0; "/>
			<p style="font-size: 80%; text-align: center">
				<a href="http://abfrage3.simsso.de">Website</a> &middot; 
				<a href="http://abfrage3.simsso.de#imprint">Imprint</a> &middot; 
				<a href="http://abfrage3.simsso.de#contact">Contact</a>
			</p>
		</div>
	</body>
</html>
			';
			parent::__construct($to, self::DEFAULT_SENDER_EMAIL, null, $subject, $body);
		}
	}
?>
