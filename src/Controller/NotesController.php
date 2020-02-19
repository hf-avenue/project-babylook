<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/03/10
 * Time: 16:12
 * 投稿記事入出力
 */

namespace App\Controller;

use App\Controller;
use Cake\Routing\Router;
use Michelf\Markdown;
require_once '../vendor/Michelf/MarkdownExtra.inc.php';
use Michelf\MarkdownExtra;



class NotesController extends AppController {
    public $components = array('Paginator', 'Flash');
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
            $notes = $this->Notes->find('all')->contain(['Users']);

        } else {
            $notes = $this->Notes->find('all', array('conditions'=>array('Notes.user_id' => $user_id,)))->contain(['Users']);
        }
        $this->set(compact('notes'));
    }

    /**
     * @param null $id
     */
    public function view($id = null)
    {

        $note = $this->Notes->get($id);
        $user_id =$note->user_id;
        $user = $this->Notes->find('all', array('conditions'=>array('Notes.user_id' => $user_id)))->contain(['Users']);
        $note->body = MarkdownExtra::defaultTransform($note->body);
        $user =$user->first();
        $this->set(compact('note', 'user'));
    }

/**
 * @return mixed
 */
    public function add()
    {
        $note = $this->Notes->newEntity();
        if ($this->request->is('post')) {
            $note = $this->Notes->patchEntity($note, $this->request->getData());
            // 投稿者IDを追記
            $note->user_id = $this->Auth->user('id');
            // 保存処理
            if ($this->Notes->save($note)) {
                $this->Flash->success(__('Your note has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your note.'));
        }
        $this->set('note', $note);
    }

    public function vote($notes_id = null)
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
            $this->loadModel('Notescores');

            // エンティティ生成
            $scores = $this->Notescores->newEntity();

            // 時刻セット
            $timestamp = date('Y-m-d H:i:s');
            $scores->created = $timestamp;

            // 有効な投稿作品IDであるか確認
            $notes = $this->Notes->find()->where(['Notes.id' => $notes_id])->first();
            if ($notes==null){
                return false;
            }

            // 作品ID、評価者ID、投票者IDをセット
            $scores->exam_user_id = $user_id;
            $scores->target_user_id = $notes['user_id'];
            $scores->notes_id =  $notes['id'];

            // 保存するよ
            if ($this->Notescores->save($scores)) {
                // $this->Flash->success(__('投票ありがとうございます'));
            }
        }
    }

}