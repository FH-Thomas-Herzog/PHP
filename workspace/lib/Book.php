<?php
/**
 * Created by PhpStorm.
 * User: p24457
 * Date: 13.06.2015
 * Time: 10:58
 */

class Book extends Entity {

	/*
	 * @var integer
	 */
	private $categoryId;

	/*
	 * @var string
	 */
	private $title;

	/*
	 * @var string
	 */
	private $author;

	/*
	 * @var double
	 */
	private $price;

	public function __construct($id, $categoryId, $title, $author, $price) {
		parent::__construct($id);
		$this->categoryId = intval($categoryId);
		$this->title = $title;
		$this->author = $author;
		$this->price = doubleval($price);
	}

	public function getCategoryId() {
		return $this->categoryId;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getAuthor() {
		return $this->author;
	}

	public function getPrice() {
		return $this->price;
	}
}