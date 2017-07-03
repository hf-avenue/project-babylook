<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/03/10
 * Time: 16:12
 * 投稿記事入出力
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;


class NovelsController extends AppController {

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }


    // 一覧表示
    public function index($user_id = null)
    {
        // 全ユーザー投稿記事
        if ($user_id == null)
        {
            $novels = $this->Novels->find('all')->contain(['Users']);

        } else {
            $novels = $this->Novels->find('all', array('conditions'=>array('Novels.user_id' => $user_id,)))->contain(['Users']);
        }
        $this->set(compact('novels'));
    }

    public function view($id = null)
    {
        $novel = $this->Novels->get($id);
        $user_id =$novel->user_id;
        $user = $this->Novels->find('all', array('conditions'=>array('Novels.user_id' => $user_id)))->contain(['Users']);
        $user =$user->first();
        $this->set(compact('novel', 'user'));

    }

    /**
     * @return mixed
     */
    public function add()
    {
        $novel = $this->Novels->newEntity();
        if ($this->request->is('post')) {
            // 時刻は明示的に宣言する事
            $novel = $this->Novels->patchEntity($novel, $this->request->getData());
            // 投稿者IDを追記
            $novel->user_id = $this->Auth->user('id');

            // 保存処理
            if ($this->Novels->save($novel)) {
                $this->Flash->success(__('Your novels has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your novels.'));
        }
        $this->set('novel', $novel);
    }



    public function vote($novels_id = null)
    {
        // 画面表示は行わない
        $this->autoRender = false;
        // ログインチェック
        $user_id = $this->Auth->user('id');
        if ($user_id ==null)
        {
            return $this->redirect($this->Auth->redirectUrl('/users/login'));
        }
        // postなら投票ロジック起動

        if ($this->request->is('post')){
            // ここから別モデルをロード
            $this->loadModel('NovelScores');

            // エンティティ生成
            $scores = $this->NovelScores->newEntity();

            // 時刻セット
            $timestamp = date('Y-m-d H:i:s');
            $scores->created = $timestamp;


            // 有効な投稿作品IDであるか確認
            $novels = $this->Novels->find()->where(['Novels.id' => $novels_id])->first();
            if ($novels==null){
                return false;
            }

            // 作品ID、評価者ID、投票者IDをセット
            $scores->exam_user_id = $user_id;
            $scores->target_user_id = $novels['user_id'];
            $scores->novels_id =  $novels['id'];

            // 保存するよ
            if ($this->Scores->save($scores)) {
                // $this->Flash->success(__('投票ありがとうございます'));
            }
        }
    }

}