<?php
/**
 * Created by PhpStorm.
 * User: fangle
 * Date: 2018/2/7
 * Time: 18:11
 */

interface RegisterInterface
{
    public function create($ip);
    public function delete();
    public function modify($ip);
    public function lists();
    public function status();
}