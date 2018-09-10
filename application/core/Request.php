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
     * リクエストされたURLを取得する
     *   リクエストされたURLからホスト部分以降の値を返す
     *   例：「http://hostname.com/sample/gorira」 → 「/sample/gorira」
     * 
     * @return string 
     */
    public function getRequestUri()
    {

        return $_SERVER['REQUEST_URI'];

    }

    public function getBaseUrl()
    {

        $scriptName = $_SERVER['SCRIPT_NAME'];
        $requestUri = $this->getRequestUri();

        if ( 0 === strpos($requestUri, $scriptName) ) {

            return $scriptName;

        } else if ( 0 === strpos($requestUri, dirname($scriptName)) ) {

            return rtrim(dirname($scriptName), '/');

        }

        return '';

    }

    public function getPathInfo()
    {

        $baseUrl    = $this->getBaseUrl();
        $requestUri = $this->getRequestUri();

        if ( false != ($pos = strpos($requestUri, '?')) ) {

            $requestUri = substr($requestUri, 0, $pos);

        }

        $pathInfo = (string)substr($requestUri, strlen($baseUrl));

        return $pathInfo;

    }


}
