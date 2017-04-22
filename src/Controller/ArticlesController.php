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

class ArticlesController extends AppController {
    // 一覧表示
    public function index($user_id = null)
    {
        // 全ユーザー投稿記事
        if ($user_id == null)
        {
            $articles = $this->Articles->find('all');
        } else {

            $articles = $this->Articles->find('all', array('conditions'=>array('Articles.user_id' => $user_id,)));
        }

        $this->set(compact('articles'));
    }

    public function view($id = null)
    {
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
            // ファイル名を転送 todo:rename


$file_status = $article->img;

            move_uploaded_file($file_status['tmp_name'], WWW_ROOT."/img/deliverable/" .$file_status['name']);









            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);
    }
}