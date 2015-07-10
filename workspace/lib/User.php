<?php
/**
 * Created by PhpStorm.
 * User: p24457
 * Date: 13.06.2015
 * Time: 11:06
 */

class User extends Entity {

	private $userName;
	private $passwordHash;

	public function __construct($id, $userName, $passwordHash) {
		parent::__construct($id);
		$this->userName = $userName;
		$this->passwordHash = $passwordHash;
	}

	public function getUserName() {
		return $this->userName;
	}

	public function getPasswordHash() {
		return $this->passwordHash;
	}
}