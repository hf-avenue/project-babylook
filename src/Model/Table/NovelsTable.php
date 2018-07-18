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

class NovelsTable extends Table {

    public function initialize(array $config)
    {
        // UsersテーブルのidとNovelsテーブルのuser_idは結合の為のキー
        $this->belongsTo('Users')->setForeignKey('user_id')->setJoinType('INNER');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('title', 'A title is required')
            ->notEmpty('body', 'A body is required');
    }

}