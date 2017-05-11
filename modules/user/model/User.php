<?php
/**
 * user model
 * @package user
 * @version 0.0.1
 * @upgrade true
 */

namespace User\Model;

class User extends \Model
{
    public $table = 'user';
    public $q_field = 'fullname';
}