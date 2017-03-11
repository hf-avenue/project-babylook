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

class MypagesController extends AppController {
    // 一覧表示
    public function index()
    {
        // ログイン中のユーザーID取得 TODO:共通関数化
        $user_id = $this->Auth->user('id');

        // ここから各モデルをロード
        $this->loadModel('Articles');

        // ユーザーIDでソート
        $articles = $this->Articles->find('all', array('conditions'=>array('Articles.user_id' => $user_id,)));
        $this->set(compact('articles'));
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