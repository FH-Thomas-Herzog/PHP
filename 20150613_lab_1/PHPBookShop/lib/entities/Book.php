<?php

/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 6/13/2015
 * Time: 10:52 AM
 */
class Book extends Entity
{

    private $author;
    private $price;
    private $categoryId;
    private $title;

    /**
     * Constructor which constructs an book entity.
     * @param integer $id
     * @param string $author
     * @param double $price
     * @param integer $categoryId
     * @param string $title
     */
    function __construct($id, $categoryId, $title, $author, $price)
    {
        parent::__construct($id);
        $this->author = $author;
        $this->title = $title;
        $this->categoryId = intval($categoryId);
        $this->price = doubleval($price);
    }


    /**
     * Gets the author of this book
     * @return tthe book's author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the author of this book
     * @param string $author the author of this book
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Gets the price of this book
     * @return double the book's price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the book prce
     * @param double $price the book's price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * The related category of this book
     * @return integer the related category
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Sets the related category
     * @param integer $categoryId the book's related category
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * GTets the books's title
     * @return string the book's title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the book's title
     * @param strign $title the book's title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


}