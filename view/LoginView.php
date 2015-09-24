<?php

/**
 * The view for the login-functionality
 */

namespace view;

use model\User;

require_once("./model/LoginModel.php");

/**
 * The view for login
 *
 * Class LoginView
 * @package view
 */
class LoginView {
	/**
	 * The login-button
	 *
	 * @var string
	 */
	private static $login = 'LoginView::Login';
	/**
	 * The logout-button
	 *
	 * @var string
	 */
	private static $logout = 'LoginView::Logout';
	/**
	 * The text field where the user inputs username
	 *
	 * @var string
	 */
	private static $name = 'LoginView::UserName';
	/**
	 * The text field where the user inputs password
	 *
	 * @var string
	 */
	private static $password = 'LoginView::Password';
	/**
	 * Name of the cookie containing the username
	 *
	 * @var string
	 */
	private static $cookieName = 'LoginView::CookieName';
	/**
	 * Name of the cookie containing the (randomized) password
	 *
	 * @var string
	 */
	private static $cookiePassword = 'LoginView::CookiePassword';
	/**
	 * Remember me-checkbox
	 *
	 * @var string
	 */
	private static $keep = 'LoginView::KeepMeLoggedIn';
	/**
	 * Div tag where a message can be rendered to the user
	 *
	 * @var string
	 */
	private static $messageId = 'LoginView::Message';
	/**
	 * Session variable to store a temporary message
	 *
	 * @var string
	 */
	private static $message = "MessageSessionVariable";
	/**
	 * Session variable to store the username
	 *
	 * @var string
	 */
	private static $username = "UsernameSessionVariable";
	/**
	 * Name of the PHP session session variable
	 *
	 * @var string
	 */
	private static $PHPSessionCookie = "PHPSESSID";
	/**
	 * Session variable to temporarily store a, for the user client, unique string
	 *
	 * @var string
	 */
	private static $UserClientSession = "UserClientSession";
	/**
	 * The model for login
	 *
	 * @var \model\LoginModel
	 */
	private static $LoginModel;

	/**
	 * Creates the view
	 *
	 * @param \model\LoginModel $model
	 */
	public function __construct(\model\LoginModel $model){
		self::$LoginModel = $model;
	}

	/**
	 * Returns true if it's a new PHP session
	 *
	 * @return bool
	 */
	private function isNewSession() {
		return !isset($_COOKIE[self::$PHPSessionCookie]);
	}

	/**
	 * Returns true if the session is valid
	 *
	 * @return bool
	 */
	public function validSession() {
		if(!$this->isNewSession()) {
			if(isset($_SESSION[self::$UserClientSession])) {
				if ($_SESSION[self::$UserClientSession] == $this->getUserClient())
					return true;
				else
					return false;
			}
			$_SESSION[self::$UserClientSession] = $this->getUserClient();
		}
		return true;
	}

	/**
	 * Returns a unique string for the users client
	 *
	 * @return string
	 */
	private function getUserClient() {
		return $_COOKIE[self::$PHPSessionCookie] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'];
	}

	/**
	 * Only validates the credentials, don't actually login.
	 *
	 * @param User $User
	 * @return string Error message, empty if credentials are valid
	 */
	public function loginTest(\model\User $User) {
		if(empty($_POST[self::$name]))
			return "Username is missing";
		else if(empty($_POST[self::$password]))
			return "Password is missing";
		else if (!self::$LoginModel->checkCredential($User))
			return "Wrong name or password";
		else
			return '';
	}

	/**
	 * Returns the current User
	 *
	 * @return User|string The user entered in the UI
	 */
	public function getUser() {
		$User = '';
		if($_SERVER['REQUEST_METHOD'] == "POST")
			$User = new User($_POST[self::$name], $_POST[self::$password]);
		else if ($this->cookiesAreSet())
			$User = new User($_COOKIE[self::$cookieName], '');
		else
			$User = new User('', '');
		return $User;
	}

	/**
	 * Returns true if the user wants to login
	 *
	 * @return bool True if the user wants to login
	 */
	public function theUserWantToLogin() {
		return isset($_POST[self::$login]) && !self::$LoginModel->userLoggedIn();
	}

	/**
	 * Returns true if the user wants to logout or if the session is corrupt
	 *
	 * @return bool True if the user wants to logout or if the session is corrupt
	 */
	public function theUserWantToLogout() {
		return (isset($_POST[self::$logout])
			&& self::$LoginModel->userLoggedIn())
			|| !$this->validSession();
	}

	/** Returns true if the users has checked the "Keep me logged in"-button
	 *
	 * @return bool True if users want to be kept logged in
	 */
	public function isKeepLoggedInChecked() {
		return isset($_POST[self::$keep]);
	}

	/**
	 * Creates cookie for the user to be remembered
	 *
	 * @param $randomizedPassword
	 */
	public function setKeepLogin($randomizedPassword) {
		setcookie(self::$cookieName, $_POST[self::$name], -1);
		setcookie(self::$cookiePassword, $randomizedPassword, -1);
	}

	/**
	 * Returns the username of the user that's being deleted
	 *
	 * @return string
	 */
	public function removeKeepLogin() {
		if ($this->anyCookiesAreSet()) {
			if (isset($_COOKIE[self::$cookieName]))
				$tempUsername = $_COOKIE[self::$cookieName];
			else
				$tempUsername = '';
			setcookie(self::$cookieName, null, time() - 300);
			setcookie(self::$cookiePassword, null, time() - 300);
			return $tempUsername;
		}
	}

	/**
	 * Returns the user that is persistent logged in
	 *
	 * @return User
	 */
	public function getPersistentLoggedInUser() {
		return new User($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);
	}

	/**
	 * Returns true if ALL cookies are set
	 *
	 * @return bool
	 */
	public function cookiesAreSet() {
		return isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword]);
	}

	/**
	 * Returns true if ANY cookie are set
	 *
	 * @return bool
	 */
	public function anyCookiesAreSet() {
		return isset($_COOKIE[self::$cookieName]) || isset($_COOKIE[self::$cookiePassword]);
	}

	/**
	 * Returns true if this is a current PHP session
	 *
	 * @return bool
	 */
	public function isPhpSession() {
		return isset($_COOKIE[self::$PHPSessionCookie]);
	}

	/**
	 * Returns true if there is a user logged
	 * in but there is no current PHP session
	 *
	 * @return bool
	 */
	public function checkIfPersistentLoggedIn() {
		return $this->validCookies() && !$this->isPhpSession();
	}

	/**
	 * Validates the cookies
	 *
	 * @return bool True if the cookies are valid
	 */
	public function validCookies() {
		if ($this->cookiesAreSet()) {
			$user = new User($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);
			return self::$LoginModel->checkCredentialForSavedUser($user);
		}
		return false;
	}

	/**
	 * Set the message to be seen on successful login or
	 * displays the error message if login failed
	 */
	public function setLoginView() {
		$message = ($this->loginTest($this->getUser()));
		if (empty($message) && $this->isKeepLoggedInChecked())
			$message = "Welcome and you will be remembered";
		else if(empty($message))
			$message = "Welcome";
		$this->setMessage($message);
	}

	/**
	 * Set the message to be displayed on logout
	 */
	public function setLogoutView() {
		if($this->validSession())
			$this->setMessage("Bye bye!");
	}

	/**
	 * Sets the welcome message if the users logs in with cookies
	 */
	public function setLoginWithCookiesView() {
		$this->setMessage("Welcome back with cookie");
	}

	/**
	 * Sets the error message if login with cookies fails
	 */
	public function setFailedLoginWithCookiesView() {
		if ($this->anyCookiesAreSet())
			$this->setMessage("Wrong information in cookies");
	}

	/**
	 * Function that sets a message that only can be retrieved once
	 *
	 * @param $message Set a one time message
	 */
	private function setMessage($message) {
//		if($_POST) {
			$_SESSION[self::$message] = $message;
//		}
	}

	/**
	 * Function that gets a message that only can be retrieved once
	 *
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

		if(self::$LoginModel->userLoggedIn())
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
	 * Returns the username that the user tries to login with
	 *
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