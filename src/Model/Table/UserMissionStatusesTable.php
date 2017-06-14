<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/03/10
 * Time: 17:45
 */

namespace App\Model\Table;

use Cake\ORM\Table;


class UserMissionStatusesTable extends Table {


    public function initialize(array $config)
    {
        // MasterテーブルのidとStatusesテーブルのuser_idは結合の為のキー
        $this->belongsTo('MissionMasters')->setForeignKey('user_id')->setJoinType('INNER');
        $this->addBehavior('Timestamp');
    }






}