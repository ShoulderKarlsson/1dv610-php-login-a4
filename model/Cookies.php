<?php

namespace model;


/**
 * Represents a 'catalog' of cookieinformation
 */
class Cookies {
	private static $cookiePassword = 'cookiePassword';
	private static $cookieTime = 'cookieTime';

	private $cookieDAL;
	private $storedCookies;

	public function __construct(CookieDAL $cd) {
		$this->cookieDAL = $cd;
		$this->getStoredCookies();

	}

	public function updateCookiePassword(\model\Cookie $c) {
		$c->cookiePassword = $this->generateRandomCookieString();
		return $c;
	}

	public function replaceOldCookie(\model\Cookie $newCookie, $oldPw) {
		$cookiePlaceholder = array();
		foreach($this->storedCookies as $cookie) {
			if ($cookie[self::$cookiePassword] !== $oldPw) {
				$cookiePlaceholder[] = $cookie;
			}
		}
		$cookiePlaceholder[] = $newCookie;

		return $cookiePlaceholder;
	}

	public function getCookie() : array {
		$this->cookieDAL->collectCookies();
	}

	public function saveCookie(\model\Cookie $c) {
		$c->cookiePassword = $this->generateRandomCookieString();
		$this->cookieDAL->saveCookie($c, $this->storedCookies);
	}

	public function saveNewCookieList(array $list) {
		$this->cookieDAL->updatePassword($list);
	}

	public function isStored(string $cookiePW) : bool {
		foreach($this->storedCookies as $cookie) {
			if ($cookie[self::$cookiePassword] === $cookiePW && $cookie[self::$cookieTime] > time()) {
				return true;
			}
		}

		return false;
	}

	public function removeCookies(\model\Cookie $c) {
		$cookiePlaceholder = array();
		foreach($this->storedCookies as $cookie) {
			if ($cookie[self::$cookiePassword] !== $c->cookiePassword) {
				$cookiePlaceholder[] = $cookie;
			}
		}

		$this->cookieDAL->updatePassword($cookiePlaceholder);
	}

	private function getStoredCookies() {
		$this->storedCookies = $this->cookieDAL->collectCookies();
	}

	private function generateRandomCookieString() : string {
		$tempString = password_hash('super_string_deluxe_o_y_e_a', PASSWORD_BCRYPT);
		$secret = '';

		for ($i=0; $i < 50; $i++) {
			$secret .= $tempString[rand(0, strlen($tempString) - 1)];
		}

		return $secret;
	}
}
