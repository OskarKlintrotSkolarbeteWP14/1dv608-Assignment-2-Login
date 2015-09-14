<?php

namespace view;

use model\User;

require_once("./model/LoginModel.php");

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $message = "MessageSessionVariable";

	private $LoginModel;

	public function __construct($model){
		$this->LoginModel = $model;
	}

	public function loginTest($User) {
		if(empty($_POST[self::$name]))
			return "Username is missing";
		else if(empty($_POST[self::$password]))
			return "Password is missing";
		else if (!$this->LoginModel->checkCredential($User))
			return "Wrong name or password";
	}

	public function getUser() {
		$User = '';
		if($_SERVER['REQUEST_METHOD'] == "POST")
			$User = new User($_POST[self::$name], $_POST[self::$password]);
		else
			$User = new User('', '');
		return $User;
	}

	public function doTheUserWantToLogin() {
		return isset($_POST[self::$login]);
	}

	public function doTheUserWantToLogout() {
		return isset($_POST[self::$logout]);
	}

	public function setMessage($message) {
		if($_POST) {
			$_SESSION[self::$message] = $message;
		}
	}

	public function getMessage() {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			if(isset($_SESSION[self::$message])) {
				$ret = $_SESSION[self::$message];
				$_SESSION[self::$message] = null;
				return $ret;
			}
			return '';
		}
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message = '';
		if($this->doTheUserWantToLogin()) {
			$this->setMessage($this->loginTest($this->getUser()));
		}
		else if($this->doTheUserWantToLogout()) {
			$this->setMessage("Bye bye!");
		}

		if($this->LoginModel->isUserLoggedIn())
		{
			if($this->doTheUserWantToLogin())
				$this->setMessage("Welcome");
			$message = $this->getMessage();
			$response = $this->generateLogoutButtonHTML($message);
		}
		else
		{
			$message = $this->getMessage();
			$response = $this->generateLoginFormHTML($message);
		}

		// $response .= $this->generateLogoutButtonHTML($message);
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName() {
		//RETURN REQUEST VARIABLE: USERNAME
		if(isset($_POST[self::$name]))
			return $_POST[self::$name];
		return '';
	}
	
}