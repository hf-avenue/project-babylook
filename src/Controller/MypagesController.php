<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/03/10
 * Time: 16:12
 * マイページ
 */

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\MissionMastersTable;
use App\Model\Table\UserMissionStatusesTable;


class MypagesController extends AppController {
    // 一覧表示
    
    public function index()
    {
        // ログインチェック
        $user_id = $this->Auth->user('id');
        if ($user_id ==null)
        {
            return $this->redirect($this->Auth->redirectUrl('/users/login'));
        }



        // ここから各モデルをロード　(トロフィーを基準にした処理が必要ならここから先のロジックをコピーするか共通化)
        $this->loadModel('Articles');
        $this->loadModel('Scores');
        $this->loadModel('Trophies');
        $this->loadModel('Users');
        $this->loadModel('MissionMasters'); //TODO:後でモデル定義　2017/06/09
        $this->loadModel('UserMissionStatuses'); //TODO:後でモデル定義　2017/06/09

        // ユーザー名取得
        $users = $this->Users->find('all', array('conditions' => array('Users.id' =>$user_id)));
        $row = $users->first();
        $this->set('users',$row);

        // ログインユーザーIDで投稿をソート
        $articles = $this->Articles->find('all', array('conditions'=>array('Articles.user_id' => $user_id,)));
        $this->set(compact('articles'));

        // ログインユーザーIDでイイネされたスコアを集計
        $score_query = $this->Scores->find('all', array('conditions'=>array('Scores.target_user_id' => $user_id)));
        $my_score = $score_query->count();
        $this->set('my_score',$my_score);

        // ログインユーザーIDに対してイイネしたスコアを集計
        $exam_query = $this->Scores->find('all', array('conditions'=>array('Scores.exam_user_id' => $user_id)));
        $exam_score = $exam_query->count();
        $this->set('exam_score', $exam_score);

        // トロフィー一覧を取得
        $trophies = $this->Trophies->find('all');
        $this->set('trophies',$trophies);

        // ここからミッションのフラグ確認追加です todo:アカウント作成時に最初のミッションを作成　2017/06/09 イイネと同じで別取得した方が早い
        // 結合させたミッション進行度とミッションマスタを出してあげる
        $missions = $this->UserMissionStatuses->find('all', array('conditions'=>array('UserMissionStatuses.user_id' => $user_id,)))->contain(['MissionMasters']);

        $this->set('missions',$missions);




        // ここまで

    }

    public function view($id = null)
    {
        $this->loadModel('Articles');
        $article = $this->Articles->get($id);
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            // 投稿者IDを追記
            $article->user_id = $this->Auth->user('id');
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);
    }
}