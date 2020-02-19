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
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class MypagesController extends AppController {
    // 一覧表示
    public $components = array('Paginator', 'Flash');
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
        $this->loadModel('MissionMasters');
        $this->loadModel('UserMissionStatuses');
        $this->loadModel('UserProfiles');


        // ユーザー名取得
        $users = $this->Users->find('all', array('conditions' => array('Users.id' =>$user_id)));
        $row = $users->first();
        $this->set('users',$row);

        // プロフィール取得
        $profiles = $this->UserProfiles->find('all', array('conditions' => array('UserProfiles.user_id' =>$user_id)));
        $profile = $profiles->first();
        $this->set('profile',$profile);

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

        // ここからミッションのフラグ確認追加です(ミッションの発行はMypageに集約)
        // 進行中ミッションを取得
        $running_mission = $this->UserMissionStatuses->find('all', array('conditions'=>array('UserMissionStatuses.user_id' => $user_id,'UserMissionStatuses.mission_completed' => 0)));
        // 進行中ミッションがなければ、新ミッション発行
        $running= $running_mission->count();
        if ($running === 0){
            // 成功件数に応じてミッションを追加
            $mission_query = $this->UserMissionStatuses->find('all', array('conditions'=>array('UserMissionStatuses.user_id' => $user_id,'UserMissionStatuses.mission_completed' => 1 )));
            $mission_count = $mission_query->count();
            // 成功件数+1のミッションを発行する
            $mission = $this->UserMissionStatuses->newEntity();
            $mission->user_id = $user_id;
            $mission->mission_id = $mission_count +1 ;
            $this->UserMissionStatuses->save($mission);
        }
        // 結合させたミッション進行度とミッションマスタを出してあげる
        $missions = $this->UserMissionStatuses->find('all', array('conditions'=>array('UserMissionStatuses.user_id' => $user_id,)))->contain(['MissionMasters']);
        $this->set('missions',$missions);
        // ここまで

    }

    // プロフィールの自己紹介欄を入力する画面
    public function edit()
    {
        // ログインチェック
        $user_id = $this->Auth->user('id');
        if ($user_id ==null)
        {
            return $this->redirect($this->Auth->redirectUrl('/users/login'));
        }

        // モデル取得
        $this->loadModel('UserProfiles');
        $this->loadModel('UserMissionStatuses');
        $post_profile = $this->UserProfiles->newEntity();
        $mission_statuses = $this->UserMissionStatuses->newEntity();
        // データ取得
        $user_profiles = $this->UserProfiles->find('all', array('conditions'=>array('UserProfiles.user_id' => $user_id)));
        $profile = $user_profiles->first();
        $mission_statuses = $this->UserMissionStatuses->find('all', array('conditions'=>array('UserMissionStatuses.user_id' => $user_id, 'UserMissionStatuses.mission_id' =>1)));
        $mission = $mission_statuses->first();

        // 投稿だったら受付、そうでなければ表示
        if ($this->request->is('post') && $profile != NULL) {
            // update
            // 明示的にプライマリキーを指定する事で更新扱いになる
            $post_profile = $this->UserProfiles->patchEntity($post_profile, $this->request->getData());
            $post_profile->id = $profile->id;
            $post_profile->user_id = $this->Auth->user('id');
            if ($this->UserProfiles->save($post_profile)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
        } else if ($this->request->is('post') && $profile == NULL) {
            $connection = ConnectionManager::get('default');
            $connection->begin();
            try{
                // insert
                // bodyは暗黙の取得、user_idは明示的に入力、2テーブル更新なので明示的にトランザクション宣言
                $post_profile = $this->UserProfiles->patchEntity($post_profile, $this->request->getData());
                $post_profile->user_id = $this->Auth->user('id');
                //　プロフィール新規登録
                if (!$this->UserProfiles->save($post_profile)) {
                    throw new Exception(Configure::read("M.ERROR.INVALID"));
                }
                // プロフィール新規登録なので、ミッション１は完了扱いになる

                $id = $mission->id;

                $user_mission_statuses_table = TableRegistry::get('UserMissionStatuses');
                $user_mission_statuses = $user_mission_statuses_table->get($id);
                $user_mission_statuses->id = $id;
                $user_mission_statuses->mission_completed = 1;
                $user_mission_statuses->mission_progress = 1;

                if (!$user_mission_statuses_table->save($user_mission_statuses)){
                    throw new Exception(Configure::read("M.ERROR.INVALID"));
                }
            } catch(Exception $e){
                $this->Flash->error($e);
                $connection->rollback(); //ロールバック
                return $this->redirect(['action' => 'index']);
            }
            $connection->commit();
            // 成功したなら成功表示して画面遷移
            $this->Flash->success(__('Your article has been saved.'));
            return $this->redirect(['action' => 'index']);
        // postでなければ表示を行う
        } else if ($profile != NULL){
            $this->set('body', $profile->body);
        } else {
            $this->set('body', NULL);
        }
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