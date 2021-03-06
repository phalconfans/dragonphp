<?php
/*
+------------------------------------------------------------------------+
| DragonPHP Website                                                      |
+------------------------------------------------------------------------+
| Copyright (c) 2016-2017 DragonPHP Team (https://www.dragonphp.com)      |
+------------------------------------------------------------------------+
| This source file is subject to the New BSD License that is bundled     |
| with this package in the file LICENSE.txt.                             |
|                                                                        |
| If you did not receive a copy of the license and are unable to         |
| obtain it through the world-wide-web, please send an email             |
| to license@dragonphp.com so we can send you a copy immediately.       |
+------------------------------------------------------------------------+
| Authors: Frank Kennedy Yuan <kideny@msn.com>                     |
+------------------------------------------------------------------------+
*/

namespace DragonPHP\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class SignUpForm extends Form
{

    /**
     * Forms initializer
     *
     * @param Users $user
     * @param array $options
     */
    public function initialize($entity = null, $options = null)
    {

        // In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {

            $id = new Hidden('id');

        } else {

            $id = new Text('id',[
                'class'  => 'form-control',
            ]);

        }

        // FirstName
        $firstName = new Text('firstName',
            [
                'class'  => 'form-control',
                'placeholder' => '请输入您的名',
                'required' => 'true',
            ]
        );

        $firstName->setLabel('First Name');

        // Set multiple filters
        $firstName->setFilters(
            [
                "string",
                "trim",
            ]
        );

        $this->add($firstName);

        // LastName
        $lastName = new Text('lastName',
            [
                'class'  => 'form-control',
                'placeholder' => '请输入您的姓',
                'required' => 'true',
            ]
        );
        $lastName->setLabel('Last Name');

        // Set multiple filters
        $lastName->setFilters(
            [
                "string",
                "trim",
            ]
        );
        $this->add($lastName);

        // loginName
        $loginName = new Text('loginName', [
            'class'  => 'form-control',
            "maxlength"   => 30,
            'placeholder' => '请输入登录名',
            'required' => 'true'
        ]);

        $loginName->setLabel('LoginName');

        $loginName->addValidators([
            new PresenceOf(
                [
                    'message' => '登录名是必须要填写的！'
                ]
            ),
            new StringLength(
                [
                    "min"            => 5,
                    "messageMinimum" => "您的用户名太短了！",
                ]
            )
        ]);

        // Set multiple filters
        $loginName->setFilters(
            [
                "string",
                "trim",
            ]
        );

        $this->add($loginName);

        // email
        $email = new Text('email', [
            'class'  => 'form-control',
            "maxlength"  => 50,
            'placeholder'  =>  '请输入电子邮件',
            'required'  =>  'true',
            'autofocus'  =>  'true'
        ]);

        $email->setLabel('E-mail');

        $email->addValidators([
            new PresenceOf(
                [
                    'message' => 'E-mail是必须要填写的'
                ]
            ),
            new Email(
                [
                    'message' => '请输入有效的电子邮件地址'
                ]
            )
        ]);

        // Set one filter
        $email->setFilters(
            "email"
        );

        $this->add($email);

        // Password
        $password = new Password('password', [
            'class' => 'form-control',
            'placeholder' => '请输入密码',
            'required' => 'true',
            "autocomplete" => "off",
            'maxlengh' => 32,
        ]);

        $password->setLabel('Password');

        $password->addValidators([
            new PresenceOf([
                'message' => '密码是必须要输入的'
            ]),
            new StringLength([
                'min' => 8,
                'messageMinimum' => '您输入的密码太短. 最小为8个字符'
            ]),
            new StringLength([
                'max' => 32,
                'messageMinimum' => '您输入的密码太长. 最大为32个字符'
            ]),
            new Confirmation([
                'message' => '密码与确认密码不同',
                'with' => 'confirmPassword'
            ])
        ]);

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword', [
            'class'  => 'form-control',
            'placeholder' => '请输入确认密码',
            'required' => 'true',
            "autocomplete" => "off",
            'maxlengh' => 32,
        ]);

        $confirmPassword->setLabel('Confirm Password');

        $confirmPassword->addValidators([
            new PresenceOf([
                'message' => '确认密码是必须要输入的'
            ]),
            new StringLength([
                'max' => 32,
                'messageMinimum' => '您输入的密码太长. 最大为32个字符'
            ]),
        ]);

        $this->add($confirmPassword);

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical(
            [
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF 验证失败'
            ]
        ));

        $csrf->clear();

        $this->add($csrf);

        $this->add(new Submit('submit',
            [
                'class' => 'btn btn-primary'
            ]
        ));
    }

}