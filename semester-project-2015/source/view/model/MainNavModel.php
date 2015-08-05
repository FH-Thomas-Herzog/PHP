<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/5/2015
 * Time: 12:06 PM
 */

namespace source\view\model;


use source\common\BaseObject;

class MainNavModel extends BaseObject
{

    public $channels;

    public function __construct(array $channels = array())
    {
        parent::__construct();
    }
}