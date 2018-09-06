<?php

require 'core\ClassLoader.php';

// オートローダクラスに読み込み対象の「core」、「models」ディレクトリを登録する
$loader = new ClassLoader();
$loader->registerDir( dirname(__FILE__) . '/core' );
$loader->registerDir( dirname(__FILE__) . '/models' );
$loader->register();
