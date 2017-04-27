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

        // 画像フォーマットチェック20170427
        $validator
            ->add('img',[
                'mimeType' => [
                    'rule' => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
                    'message' => '投稿可能な画像はjpg, png, gif のみです',
                    'allowEmpty' => TRUE,
                ],
            ] );

        // 画像サイズチェック20170421
        $validator
            ->add('img',[
                'fileSize' => [
                    'rule' => array('fileSize', 'isless', '10MB'),
                    'message' => '投稿可能なサイズは10MB以下のみです',
                    'allowEmpty' => TRUE,
                ],
            ] );
        return $validator;
    } // 'fileSize', '<=', '10MB'

}