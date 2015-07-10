<?php
/**
 * Created by PhpStorm.
 * User: p24457
 * Date: 13.06.2015
 * Time: 10:20
 */

class Category extends Entity {

	private $name;

	public function __construct($id, $name) {
		parent::__construct($id);
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

}