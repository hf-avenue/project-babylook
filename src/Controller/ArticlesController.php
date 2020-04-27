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
            $articles = $this->Articles->find('all')->contain(['Users']);

        } else {
            $articles = $this->Articles->find('all', array('conditions'=>array('Articles.user_id' => $user_id,)))->contain(['Users']);
        }
        $this->set(compact('articles'));
    }

    public function view($id = null)
    {
        $article = $this->Articles->get($id);
        $user_id =$article->user_id;
        $user = $this->Articles->find('all', array('conditions'=>array('Articles.user_id' => $user_id)))->contain(['Users']);
        $user =$user->first();
        $this->set(compact('article', 'user'));
    }

    /**
     * @return mixed
     * 記事、画像、サムネイルの同時投稿 TODO:メンバ関数・メンバ定数でリファクタリング
     */
    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            // 時刻は明示的に宣言する事
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            // 投稿者IDを追記
            $article->user_id = $this->Auth->user('id');
            // オリジナルファイル情報取得
            $file_status = $article->img;

            // 画像種別を追記
            $img_ext = pathinfo($file_status['name'], PATHINFO_EXTENSION);
            $article->img_ext = $img_ext;

            // 画像ユニーク連番作成
            $image_number = md5(uniqid(rand(), 1));
            $article->image_number = $image_number;
            // 画像の元名称を追記
            $article->original_name = $file_status['name'];
            // 画像サイズを追記
            $article->img_size = $file_status['size'];
            // オリジナルファイル転送先を設定
            $image_upload_path = WWW_ROOT."img/deliverable/".$image_number.".".$img_ext;
            // オリジナルファイルをユニーク名称で転送
            move_uploaded_file($file_status['tmp_name'], $image_upload_path);

            // サムネイルを生成
            list($original_width, $original_height) = getimagesize($image_upload_path);

            // サムネイルの横幅を指定 TODO:定数化
            $thumb_width = 150;

            // サムネイルの高さを算出 round関数で四捨五入
            $thumb_height = round( $original_height * $thumb_width / $original_width );

            // オリジナルファイルの取得
            $img_ext = mb_strtolower($img_ext);
            switch ($img_ext) {
                case "png":
                    $original_image = imagecreatefrompng($image_upload_path);
                    break;
                case "jpg":
                    $original_image = imagecreatefromjpeg($image_upload_path);
                    break;
                case "jpeg":
                    $original_image = imagecreatefromjpeg($image_upload_path);
                    break;
                case "gif":
                    $original_image = imagecreatefromgif ($image_upload_path);
            }
            $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);

            // サムネイル画像の作成
            imagecopyresized($thumb_image, $original_image, 0, 0, 0, 0,
                $thumb_width, $thumb_height,
                $original_width, $original_height);
            imagepng($thumb_image, WWW_ROOT."img/thumbnail/".$image_number.".png");


            // 保存処理
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);
    }

    /**
     * @param null $articles_id
     * @return \Cake\Network\Response|null
     */
    public function vote($articles_id = null)
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
            $this->loadModel('Scores');

            // エンティティ生成
            $scores = $this->Scores->newEntity();

            // 時刻セット
            $timestamp = date('Y-m-d H:i:s');
            $scores->created = $timestamp;


            // 有効な投稿作品IDであるか確認
            $articles = $this->Articles->find()->where(['Articles.id' => $articles_id])->first();
            if ($articles==null){
                return false;
            }

            // 作品ID、評価者ID、投票者IDをセット
            $scores->exam_user_id = $user_id;
            $scores->target_user_id = $articles['user_id'];
            $scores->articles_id =  $articles['id'];

            // 保存するよ
            if ($this->Scores->save($scores)) {
                // $this->Flash->success(__('投票ありがとうございます'));
            }
        }

        // $this->Flash->error(__('不正な投票です'));



    }

}
