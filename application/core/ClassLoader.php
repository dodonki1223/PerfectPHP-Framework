<?php

/**
 * オートローダの機能を提供する
 *   オートロード（クラスが読み込まれていなかったら読み込むようにする
 *   仕組み）に関する処理をまとめたクラスです
 */
class ClassLoader
{

    /**
     * オートロード対象のディレクトリのフルパスを保持する変数
     *
     * @var array
     */ 
    protected $dirs;


    /**
     * PHPにオートローダクラスを登録する
	 *   spl_autoload_register関数を使用して「loadClass」メソッドを呼び出す
     *   ※spl_autoload_registerはautoload関数のキューを作成し、定義された順に
     *     それを実行していく
     */
    public function register()
    {

        spl_autoload_register(array($this , 'loadClass'));

    }

    /**
     * オートロードの対象となるディレクトリを登録する
	 *   オートロードの対象となるディレクトリを配列で受け取り、メンバ変数に
     *   セットする
     * 
     * @param array $dir オートロードの対象となるディレクトリのフルパスを保持した配列
     */
    public function registerDir($dir)
    {

        $this->dirs[] = $dir;

    }


    /**
     * クラスの読み込み
	 *   メンバ変数のディレクトリ群と引数で渡されたクラス名を元にファイルの存在と読
     *   み込み可能かチェックし読み込み可能な時はそのファイルを読み込む
     * 
     * @param  string $class クラス名
	 * @return null   
     */
    public function loadClass($class)
    {

        foreach ($this->dirs as $dir) {

            // クラスまでのフルパスを作成
            $file = $dir . '/' . $class . '.php';

            // ファイルが存在し、読み込み可能である時
            if (is_readable($file)) {

                // ファイルの読み込み
                require $file;
                return;

            }

        }

    }

}
