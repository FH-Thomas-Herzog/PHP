<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 11:33 AM
 */

namespace source\view\controller;


use \source\common\BaseObject;
use \Stash\Pool;
use \Stash\Driver\FileSystem;

class PoolController extends BaseObject
{

    public static function createFileSystemPool($namespace = null, array $arguments = null)
    {
        $driver = new FileSystem();
        if (isset($arguments)) {
            $driver->setOptions($arguments);
        }
        $pool = new Pool($driver);
        $pool->setNamespace($namespace);

        return $pool;
    }

    public static function clearPool(Pool $pool = null){
        if(isset($pool)) {
            $pool->flush();
        }
    }

    public static function cleanupPool(Pool $pool = null){
        if(isset($pool)) {
            $pool->purge();
        }
    }
}