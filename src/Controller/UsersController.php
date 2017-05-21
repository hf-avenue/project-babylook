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

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'logout', 'twitterLogin']);
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

    public function add()
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
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
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

    // twitterでのアカウント設定
    public function twitterLogin()
    {

        session_start();
        define( 'CONSUMER_KEY', 'mVmdVb2x8hYkTz2ddVlvnEGS8' );
        define( 'CONSUMER_SECRET', 'cEUVZ3IDLXxk1ih3nK05WSfNt1LGQSz3TJvoaGawKup8UFOtDM' );
        define( 'OAUTH_CALLBACK', 'http://192.168.205.10/project/babylook/users/twitter_login' );
        // twitter側でoauth_tokenを取得していればアクセストークン取得後にSNSログイン処理、出来ていなければリクエストトークンを取得してtwitter認証画面へ
        if((array_key_exists('oauth_token', $_SESSION))){
            $request_token = [];
            $request_token['oauth_token'] = $_SESSION['oauth_token'];
            $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
            //Twitterから返されたOAuthトークンがセッション上のものと一致するかをチェック
            if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
                die( 'Error!' );
            }
            // todo:このあたりのバグっぽいところ検証。1時間やってだめだったら、素直にリクエストトークン取得と、コネクト画面は別に作る。 http://qiita.com/sofpyon/items/982fe3a9ccebd8702867
            $access_token = $_SESSION['access_token'];
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
            $user = $connection->get("account/verify_credentials");
            var_dump($user);
            session_destroy();
            exit;
        }

        // TODO:http://atomicbox.tank.jp/website/cakephp/1290/ , https://twitteroauth.com/　この二つのマニュアルを見てログイン実装
        // まずリクエストトークンを取ってtwitter認証画面にリダイレクトさせるだけ

        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
        //必要な情報をセッションに入れる
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        $_SESSION['access_token'] = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
        //Twitter.com 上の認証画面のURLを取得( この行についてはコメント欄も参照 )
        $url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));
        //Twitter.com の認証画面へリダイレクト
        session_regenerate_id();
        header( 'location: '. $url );
        exit;
    }

}