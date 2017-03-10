<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/03/10
 * Time: 17:45
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ArticlesTable extends Table {

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        // TODO:実テーブル、バリデーション、templateに画像アップを実装
        $validator
            ->notEmpty('title')
            ->requirePresence('title')
            ->notEmpty('body')
            ->requirePresence('body');

        return $validator;
    }

}