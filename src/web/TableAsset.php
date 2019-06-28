<?php

namespace jinxing\admin\web;

use Yii;
use yii\web\AssetBundle;

/**
 * Class TableAsset
 *
 * @package jinxing\admin\web
 */
class TableAsset extends AssetBundle
{
    /**
     * @var string 定义使用的目录路径
     */
    public $basePath = '@bower/jinxing-tables/';

    /**
     * @var string 定义使用的目录路径
     */
    public $sourcePath = '@bower/jinxing-tables/';

    /**
     * @var array 定义默认加载的js
     */
    public $js = [
        'meTables.min.js',
    ];

    /**
     * 注册 meTables 所需的js
     *
     * @param \yii\web\View $view 视图
     * @param string        $url  路径
     */
    public static function meTablesRegister($view, $url = '')
    {

        // 没有配置地址
        if (empty($url)) {
            list(, $url) = Yii::$app->assetManager->publish((new self)->sourcePath);
        }

        // 加载资源
        $resource = [
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.bootstrap.js',
            'js/jquery.validate.min.js',
            'js/commmon/validate.message.min.js',
        ];

        // 注入js
        foreach ($resource as $value) {
            $view->registerJsFile($url . '/' . $value, ['depends' => self::className()]);
        }
    }
}