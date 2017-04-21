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

        $validator
            ->notEmpty('title')
            ->requirePresence('title')
            ->notEmpty('body')
            ->requirePresence('body');
        // 画像フォーマットチェック20170421
        $validator
            ->allowEmpty('img_ext')
            ->add('img_ext', ['list' => [
                'rule' => ['inList', ['jpg','jpeg', 'png', 'gif']],
                'message' => '投稿可能な画像はjpg, png, gif のみアップロード可能です.',
            ]]);
        // 画像フォーマットチェック20170421
        $validator
            ->integer('img_size')
            ->allowEmpty('img_size')
            ->add('img_size', 'comparison', [
                'rule' => ['comparison', '<', 10485760],
                'message' => 'ファイルが大きすぎます(MaxSize:10M)',
            ]);
        return $validator;
    }

}