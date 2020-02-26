<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2020/02/26
 * Time: 11:51
 */

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class ProfilesTable extends Table {
    public function initialize(array $config)
    {
        $this->setPrimaryKey('user_id');
        // Usersテーブルのidとprofilesテーブルのuser_idは結合の為のキー
        $this->belongsTo('Users')->setForeignKey('user_id')->setJoinType('INNER');
    }
}