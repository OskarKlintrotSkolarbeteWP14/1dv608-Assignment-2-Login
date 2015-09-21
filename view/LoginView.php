<?php

namespace view;

use model\User;

require_once("./model/LoginModel.php");

/**
 * Class LoginView
 * @package view
 */
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
	private static $username = "UsernameSessionVariable";

	private $LoginModel;

	/**
	 * @param \model\LoginModel $model
	 */
	public function __construct(\model\LoginModel $model){
		$this->LoginModel = $model;
	}

	/**
	 * @param User $User
	 * @return string Error message, empty if credentials are valid
	 */
	public function loginTest(\model\User $User) {
		if(empty($_POST[self::$name]))
			return "Username is missing";
		else if(empty($_POST[self::$password]))
			return "Password is missing";
		else if (!$this->LoginModel->checkCredential($User))
			return "Wrong name or password";
		else
			return '';
	}

	/**
	 * @return User|string The user entered in the UI
	 */
	public function getUser() {
		$User = '';
		if($_SERVER['REQUEST_METHOD'] == "POST")
			$User = new User($_POST[self::$name], $_POST[self::$password]);
		else
			$User = new User('', '');
		return $User;
	}

	/**
	 * @return bool True if the user wants to login
	 */
	public function doTheUserWantToLogin() {
		return isset($_POST[self::$login]);
	}

	/**
	 * @return bool True if the user wants to logout
	 */
	public function doTheUserWantToLogout() {
		return isset($_POST[self::$logout]);
	}

	/** Returns true if the users has checked the "Keep me logged in"-button
	 *
	 * @return bool True if users want to be kept logged in
	 */
	public function isKeepLoggedInChecked() {
		return isset($_POST[self::$keep]);
	}

	public function setKeepLogin() {
		setcookie(self::$cookieName, self::$cookiePassword, -1);
	}

	public function removeKeepLogin() {
		if (isset($_COOKIE[self::$cookieName]))
			setcookie(self::$cookieName, null, time() -300);
	}

	/**
	 * Set the message to be seen on successful login or
	 * displays the error message if login failed
	 */
	public function setLoginView() {
		$message = ($this->loginTest($this->getUser()));
		if(empty($message))
			$message = "Welcome";
		$this->setMessage($message);
	}

	/**
	 * Set the message to be displayed on logout
	 */
	public function setLogoutView() {
		$this->setMessage("Bye bye!");
	}

	/**
	 * @param $message Set a one time message
	 */
	private function setMessage($message) {
		if($_POST) {
			$_SESSION[self::$message] = $message;
		}
	}

	/**
	 * @return string Can only be get once, then it is deleted
	 */
	private function getMessage() {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			if(isset($_SESSION[self::$message])) {
				$ret = $_SESSION[self::$message];
				unset($_SESSION[self::$message]);
				return $ret;
			}
		}
		return '';
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

		if($this->LoginModel->isUserLoggedIn())
		{
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

	/**
	 * @return string Username that the user tries to login with
	 */
	private function getRequestUserName() {
		if(isset($_POST[self::$name]))
			$_SESSION[self::$username] = $_POST[self::$name];
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			if (isset($_SESSION[self::$username])) {
				$ret = $_SESSION[self::$username];
				$_SESSION[self::$username] = null;
				return $ret;
			}
		}
			return '';
	}
}