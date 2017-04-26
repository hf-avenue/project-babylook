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

    /**
     * @return mixed
     * 記事、画像、サムネイルの同時投稿 TODO:メンバ関数・メンバ定数でリファクタリング
     */
    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
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
}