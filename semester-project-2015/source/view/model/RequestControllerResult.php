<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/3/2015
 * Time: 8:24 PM
 */

namespace source\view\model;


use source\common\BaseObject;

class RequestControllerResult extends BaseObject
{

    public $valid;

    public $nextView;

    public $args;

    public function __construct($valid = false, $nextView = null, array $args = null){
        parent::__construct();

        $this->valid = (boolean) $valid;
        $this->nextView = (string) $nextView;
        $this->args = $args;
    }
}