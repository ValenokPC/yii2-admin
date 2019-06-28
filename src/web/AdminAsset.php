<?php

namespace jinxing\admin\web;

use Yii;

/**
 * Class AdminAsset 后台资源加载类
 * @package backend\assets
 */
class AdminAsset extends AppAsset
{
    /**
     * @var array 定义默认加载的js
     */
    public $js = [
        'js/ace-elements.min.js',
        'js/ace.min.js',
        'js/common/tools.min.js',
        'js/layer/layer.js',
    ];

    /**
     * 注册 meTables 所需的js
     *
     * @param \yii\web\View $view 视图
     */
    public static function meTablesRegister($view)
    {
        // 没有配置地址
        list(, $url) = Yii::$app->assetManager->publish((new self)->sourcePath);

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

        $view->registerAssetBundle(TableAsset::className());
    }
}