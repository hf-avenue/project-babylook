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

class UsersController extends AppController {

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'logout', 'twitter']);
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
    public function twitter()
    {

        // 必須ステータス設定
        $api_key = "mVmdVb2x8hYkTz2ddVlvnEGS8" ;	// API Key
        $api_secret = "cEUVZ3IDLXxk1ih3nK05WSfNt1LGQSz3TJvoaGawKup8UFOtDM" ;	// API Secret
        $callback_url = "http://192.168.205.10/project/babylook/users/twitter" ;	// Callback URL (このプログラムのURLアドレス)
        // アクセスキー設定
        $access_token_secret = "" ;
        $request_url = "https://api.twitter.com/oauth/request_token" ;
        $request_method = "POST" ;
        $signature_key = rawurlencode( $api_secret ) . "&" . rawurlencode( $access_token_secret ) ;
        // 転送データ作成
        $params = array(
            "oauth_callback" => $callback_url ,
            "oauth_consumer_key" => $api_key ,
            "oauth_signature_method" => "HMAC-SHA1" ,
            "oauth_timestamp" => time() ,
            "oauth_nonce" => microtime() ,
            "oauth_version" => "1.0" ,
        ) ;





        // 転送データをURLエンコードする
        foreach( $params as $key => $value ) {
            // コールバックURLはエンコードしない
            if( $key == "oauth_callback" ) {
                continue ;
            }

            // URLエンコード処理
            $params[$key] = rawurlencode($value);
        }
        ksort( $params );
        // 転送データを&で接続しURL化
        $request_params = http_build_query( $params , "" , "&" );
        $request_params = rawurlencode($request_params);
        $encoded_request_method = rawurlencode( $request_method ) ;
        $encoded_request_url = rawurlencode( $request_url ) ;
        // シグネチャ作成
        $signature_data = $encoded_request_method . "&" . $encoded_request_url . "&" . $request_params ;
        $hash = hash_hmac( "sha1" , $signature_data , $signature_key , TRUE ) ;
        $signature = base64_encode( $hash ) ;
        // リクエスト送信
        // パラメータの連想配列、[$params]に、作成した署名を加える
        $params["oauth_signature"] = $signature ;

        // パラメータの連想配列を[キー=値,キー=値,...]の文字列に変換する
        $header_params = http_build_query( $params , "" , "," ) ;

        // リクエスト用のコンテキストを作成する
        $context = array(
            "http" => array(
                "method" => $request_method , // リクエストメソッド (POST)
                "header" => array(			  // カスタムヘッダー
                    "Authorization: OAuth " . $header_params ,
                ) ,
            ) ,
        ) ;

        $curl = curl_init() ;
        curl_setopt( $curl, CURLOPT_URL , $request_url ) ;	// リクエストURL
        curl_setopt( $curl, CURLOPT_HEADER, true ) ;	// ヘッダーを取得する
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST , $context["http"]["method"] ) ;	// メソッド
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER , false ) ;	// 証明書の検証を行わない
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER , true ) ;	// curl_execの結果を文字列で返す
        curl_setopt( $curl, CURLOPT_HTTPHEADER , $context["http"]["header"] ) ;	// リクエストヘッダーの内容
        curl_setopt( $curl, CURLOPT_TIMEOUT , 5 ) ;	// タイムアウトの秒数
        $res1 = curl_exec( $curl ) ;
        $res2 = curl_getinfo( $curl ) ;
        curl_close( $curl ) ;
        // 取得したデータ
        $response = substr( $res1, $res2["header_size"] ) ;	// 取得したデータ(JSONなど)
        $header = substr( $res1, 0, $res2["header_size"] ) ;	// レスポンスヘッダー (検証に利用したい場合にどうぞ)
        $query = [] ;
        parse_str( $response, $query ) ;

        // 最終処理
        $http = new Client();
        $response = $http->get('https://api.twitter.com/oauth/request_token', [], [
            'auth' => [
                'type' => 'oauth',
                'consumerKey' => $api_key,
                'consumerSecret' => $api_secret,
                'token' => $query["oauth_token"],
                'tokenSecret' => $query["oauth_token_secret"],
                'realm' => 'tickets',
            ]
        ]);

        // todo https://syncer.jp/Web/API/Twitter/REST_API/#section-2-1  ユーザーを認証画面に飛ばす

        exit;



    }

}