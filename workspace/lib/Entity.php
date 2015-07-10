<?php
/**
 * Created by PhpStorm.
 * User: p24457
 * Date: 13.06.2015
 * Time: 10:15
 */

interface IData {
	public function getID();
}

class Entity extends BaseObject implements IData {
	private $id;
	public function __construct($id) {
		$this->id = intval($id);
	}
	public function getID() {
		return $this->id;
	}
}