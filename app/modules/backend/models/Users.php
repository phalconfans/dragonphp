<?php
/*
+------------------------------------------------------------------------+
| DragonPHP                                                             |
+------------------------------------------------------------------------+
| Copyright (c) 2016-2017 DragonPHP Team and contributors                  |
+------------------------------------------------------------------------+
| This source file is subject to the New BSD License that is bundled     |
| with this package in the file LICENSE.txt.                             |
|                                                                        |
| If you did not receive a copy of the license and are unable to         |
| obtain it through the world-wide-web, please send an email             |
| to license@dragonphp.com so we can send you a copy immediately.       |
+------------------------------------------------------------------------+
*/

namespace DragonPHP\Backend\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;

/**
* Class Users
*
* @property Simple badges badges=>原型
* @property Simple posts  posts=>邮件
* @property Simple replies replies=>回复
* @method Simple getBadges($parameters=null)
* @method Simple getPosts($parameters=null)
* @method Simple getReplies($parameters=null)
* @method static Users findFirstById(int $id)
* @method static Users findFirstByLogin(string $login)
* @method static Users findFirstByName(string $name)
* @method static Users findFirstByEmail(string $email)
* @method static Users findFirstByAccessToken(string $token)
* @method static Users[] find($parameters=null)
*
* @package DragonPHP\Backend\Model
*/
class Users extends ModelBase
{
    /**
    * @var int
    */
    protected $id;

    /**
    * @var string
    */
    protected $firstName;

    /**
    * @var string
    */
    protected $lastName;

    /**
    * @var string
    */
    protected $loginName;

    /**
    * @var string
    */
    protected $email;

    /**
    * @var string
    */
    public $password;

    /**
    * @var string
    */
    protected $activationCode;

    /**
    * @var string
    */
    protected $persistCode;

    /**
    * @var string
    */
    protected $resetPasswordCode;

    /**
    * @var test
    */
    protected $permissions;

    /**
    * @var bool
    */
    protected $isActivated;

    /**
    * @var timestamp
    */
    protected $lastLogin;

    /**
    * @var timestamp
    */
    protected $activatedAt;

    /**
    * @var timestamp
    */
    protected $createdAt;

    /**
    * @var timestamp
    */
    protected $updatedAt;

    /**
    * @var bool
    */
    protected $isSuperUser;

    const SYSTEM_USER = 1;

    public function initialize()
    {
        // Mapping
        $this->setSource("backend_users");

        $this->hasMany(
        'id',
        'DragonPHP\Backend\Models\UsersGroups',
        'user_id',
            [
                'alias' => 'userID',
                'reusable' => true
            ]
        );

        $this->hasMany('id', __NAMESPACE__ . '\SuccessLogins', 'usersId', [
            'alias' => 'successLogins',
            'foreignKey' => [
                'message' => '用户不能被删除，因为他/她在系统中是有效的。'
            ]
        ]);

    }

    /**
    * @return int
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * @param int $id
    */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
    * @return string
    */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
    * @param string $firstName
    */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
    * @return string
    */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
    * @param string $larstName
    */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
    * @return string
    */
    public function getLoginName()
    {
        return $this->loginName;
    }

    /**
    * @param string $loginName
    */
    public function setLoginName($loginName)
    {
        $this->loginName = $loginName;
    }

    /**
    * @return string
    */
    public function getEmail()
    {
        return $this->email;
    }

    /**
    * @param string $email
    */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
    * @return datetime
    */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
    * @param datetime $createdAt
    */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
    * @return datetime
    */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
    * @param datetime $updatedAt
    */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
    * @return int
    */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
    * @param int $id
    */
    public function setIsActivated($id)
    {
        $this->isActivated = $id;
    }

    /**
    * @return int
    */
    public function getIsSuperUser()
    {
        return $this->isSuperUser;
    }

    /**
    * @param int $id
    */
    public function setIsSuperUser($isSuperUser)
    {
        $this->isSuperUser = $isSuperUser;
    }

    /**
    * @param $karma
    */
    public function increaseKarma($karma)
    {
        $this->karma += $karma;
        $this->votes_points += $karma;
    }

    /**
    * @param $karma
    */
    public function decreaseKarma($karma)
    {
        $this->karma -= $karma;
        $this->votes_points -= $karma;
    }

    protected function beforeSave()
    {
        $this->createdAt = date('Y-m-d H:i:s');

        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public function beforeCreate()
    {
        $this->createdAt = date('Y-m-d H:i:s');

    }

    public function beforeUpdate()
    {
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public function afterValidation()
    {

    }

    public function afterCreate()
    {
    }

    /**
    * @return string
    */
    public function getHumanKarma()
    {
        if ($this->karma >= 1000) {
            return sprintf("%.1f", $this->karma / 1000) . 'k';
        } else {
            return $this->karma;
        }
    }

    /**
    * Independent Column Mapping.key as datebase field, value as application field
    *
    * @return array
    */
    public function columnMap()
    {
        return [
            'id'                 =>     'id',
            'first_name'         =>     'firstName',
            'last_name'          =>     'lastName',
            'login_name'         =>     'loginName',
            'email'              =>     'email',
            'password'           =>     'password',
            'activation_code'    =>     'activationCode',
            'persist_code'       =>     'persistCode',
            'reset_password_code'=>     'resetPasswordCode',
            'permissions'        =>     'permissions',
            'is_activated'       =>     'isActivated',
            'activated_at'       =>     'activatedAt',
            'last_login'         =>     'lastLogin',
            'created_at'         =>     'createdAt',
            'updated_at'         =>     'updatedAt',
            'is_superuser'       =>     'isSuperUser',
        ];
    }

}