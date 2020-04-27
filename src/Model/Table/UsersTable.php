<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/03/07
 * Time: 23:12
 */

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;

class UsersTable  extends Table {


    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('user_name', 'A username is required')
            ->notEmpty('mail', 'A mail is required')
            ->notEmpty('password', 'A password is required')
            ->notEmpty('role', 'A role is required')
            ->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'author']],
                'message' => 'Please enter a valid role'
            ])
            ->add('mail', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'すでに使われています']);

    }
}
