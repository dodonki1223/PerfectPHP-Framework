<?php

/**
 * リクエスト情報の制御する機能を提供する
 *   リクエスト情報・URLに関する情報を制御する機能をまとめたクラスです
 */
class Request
{

    /**
     * HTTPメソッドがPOSTか
     *   HTTPメソッドがPOSTの時はTrueを返し、そうでない時はFalseを返す
     * 
     * @return boolean true/false
     */
    public function isPost()
    {

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

            return true;

        }

        return false;

    }

    /**
     * URLパラメーターを取得する
     *   $_GET変数からキーを指定して値を取得する。値を取得できなかった時は
     *   デフォルト値を返す
     * 
     * @param  string $name    キー名
     * @param  object $default キーの値が存在しない時のデフォルト値
     * @return string
     */
    public function getGet($name, $default = null) 
    {

        if ( isset($_GET[$name]) ) {

            return $_GET[$name];

        }

        return $default;

    }

    /**
     * HTTPメソッドのPOSTで送信された値を取得する
     *   $_POST変数からキーを指定して値を取得する。値を取得できなかった時は
     *   デフォルト値を返す
     * 
     * @param  string $name    キー名
     * @param  object $default キーの値が存在しない時のデフォルト値
     * @return object
     */
    public function getPost($name, $default = null)
    {

        if ( !empty($_SERVER['HTTP_HOST']) ) {

            return $_SERVER['HTTP_HOST'];

        }

        return $_SERVER['SERVER_NAME'];

    }

    /**
     * HTTPSでアクセスされたかどうか
     *   HTTPSでアクセスされた場合、$_SERVER['HTTPS']に"on"という文字が含まれるため
     *   それを元に判定する
     * 
     * @return boolean true/false
     */
    public function isSsl() 
    {

        if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ) {

            return true;

        }

        return false;

    }

    /**
     * リクエストURLを取得する
     *   リクエストされたURLからホスト部分以降の値を返す（REQUEST_URL）
     *   例：「http://hostname.com/foo/bar/list(index.phpは"/foo/bar"以下) → 「/foo/bar」
     *       「http://hostname.com/index.php/list?foo=bar」                → 「/index.php/list?foo=bar」
     *       「http://hostname.com/」                                      → 「/」
     * 
     * @return string 
     */
    public function getRequestUri()
    {

        return $_SERVER['REQUEST_URI'];

    }

    /**
     * ベースURLを取得する
     *   リクエストされたURLからベースURLを取得する
     *   リクエストされたURLからホスト部分より後ろから、フロントコントローラまでの値を
     *   $_SERVER[SCRIPT_NAME]、$_SERVER[REQUEST_URI]から取得し返す
     *   ※ベースURLとは？（このフレームワークでのみの呼び方です）
     *     ホスト部分より後ろから、フロントコントローラまでの値がベースURLになる
     *     例：「http://hostname.com/foo/bar/list(index.phpは"/foo/bar"以下) → 「/foo/bar」
     *         「http://hostname.com/index.php/list?foo=bar」                → 「/index.php」
     *         「http://hostname.com/」                                      → 「」
     *   ※SCRIPT_NAMEを使用した時に取得される値
     *     例：「http://hostname.com/foo/bar/list(index.phpは"/foo/bar"以下) → 「/foo/bar/index.php」
     *         「http://hostname.com/index.php/list?foo=bar」                → 「/index.php」
     *         「http://hostname.com/」                                      → 「/index.php」
     * 
     * @return string 
     */
    public function getBaseUrl()
    {

        // スクリプト名とリクエストURLを取得する
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $requestUri = $this->getRequestUri();

        // フロントコントローラがURLに含まれている時
        if ( 0 === strpos($requestUri, $scriptName) ) {

            return $scriptName;

        // フロントコントローラーが省略されている時（index.phpが省略）
        // 例：http://example.com/foo/bar/list（index.phpは"foo/bar以下"）
        } else if ( 0 === strpos($requestUri, dirname($scriptName)) ) {

            return rtrim(dirname($scriptName), '/');

        }

        return '';

    }

    /**
     * PahtInfoを取得する
     *   リクエストURLからベースURLを引いた文字列（PathInfo）を返す
     *   ただしURLパラメーターがある時はリクエストURLからURLパラメーターを除いたものを使用する
     *   ※PathInfoとは？
     *     ベースURLより後ろの値になる、ただしURLパラメーターは含まない
     *     例：「http://hostname.com/foo/bar/list(index.phpは"/foo/bar"以下) → 「/list」
     *         「http://hostname.com/index.php/list?foo=bar」                → 「/list」
     *         「http://hostname.com/」                                      → 「/」
     * 
     * @return string 
     */
    public function getPathInfo()
    {

        // ベースURLとリクエストURLを取得する
        $baseUrl    = $this->getBaseUrl();
        $requestUri = $this->getRequestUri();

        // リクエストされたURLに「？」があった時
        // ※strposで特定の単語が見つからなかった時は「False」を返す
        if ( false != ($pos = strpos($requestUri, '?')) ) {

            // リクエストURLから「？」の直前までのURLを取得
            $requestUri = substr($requestUri, 0, $pos);

        }

        // リクエストURLからベースURLを引いた文字列を取得
        $pathInfo = (string)substr($requestUri, strlen($baseUrl));

        return $pathInfo;

    }


}
