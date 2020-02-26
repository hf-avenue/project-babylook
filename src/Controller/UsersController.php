<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/03/07
 * Time: 23:14
 */

namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Abraham\TwitterOAuth\TwitterOAuth;

class UsersController extends AppController {
    public $components = array('Paginator', 'Flash');
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'logout']);
    }

    public function index()
    {
        $this->set('users', $this->Users->find('all'));
    }

    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }

    // ユーザーアカウント取得
    public function add()
    {


        // ユーザーテーブル接続
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {

            // ユーザーテーブル保存
            if ($this->Users->save($user)) {
                $user_id = $user->id;
            }

            // プロフィール生成準備
            $this->loadModel('Profiles');
            $profiles = $this->Profiles->newEntity();
            $profiles->user_id = $user_id;
            $this->Profiles->save($profiles);
            $this->Flash->success(__('The user has been saved.'));
            return $this->redirect(['action' => 'login']);


            // エラーメッセージ
            $this->Flash->error(__('Unable to add the user.'));
        }
        $this->set('user', $user);



    }

    // 管理者アカウント取得(要アクセス制限)
    public function adminMake()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        $this->set('user', $user);
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl('/mypages'));
            }
            $this->Flash->error(__('Invalid mail or password, try again'));
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function profile($target_id = null)
    {
        // ログインチェック
        $user_id = $this->Auth->user('id');
        if ($user_id ==null)
        {
            return $this->redirect($this->Auth->redirectUrl('/users/login'));
        }

        // 存在しないユーザーIDは画像一覧に飛ばす
        if ($target_id == null)
        {
            return $this->redirect(['controller' => 'Articles', 'action' => 'index']);
        }

        // ここから各モデルをロード　(トロフィーを基準にした処理が必要ならここから先のロジックをコピーするか共通化)
        $this->loadModel('Articles');
        $this->loadModel('Scores');
        $this->loadModel('Trophies');
        $this->loadModel('Users');
        $this->loadModel('Notes');
        $this->loadModel('NoteScores');
        // 対象ユーザー名取得
        $users = $this->Users->find('all', array('conditions' => array('Users.id' =>$target_id)));
        $row = $users->first();
        $this->set('users',$row);

        // 対象ユーザーIDで投稿をソート
        $articles = $this->Articles->find('all', array('conditions'=>array('Articles.user_id' => $target_id,)));
        $this->set(compact('articles'));

        // 対象ユーザーIDでイイネされたスコアを集計
        $score_query = $this->Scores->find('all', array('conditions'=>array('Scores.target_user_id' => $target_id)));
        $my_score = $score_query->count();
        $this->set('my_score',$my_score);

        // 対象ユーザーIDに対してイイネしたスコアを集計
        $exam_query = $this->Scores->find('all', array('conditions'=>array('Scores.exam_user_id' => $target_id)));
        $exam_score = $exam_query->count();
        $this->set('exam_score', $exam_score);

        // トロフィー一覧を取得
        $trophies = $this->Trophies->find('all');
        $this->set('trophies',$trophies);
    }


}