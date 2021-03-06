<?php

use yii\helpers\Url;
use jinxing\admin\widgets\MeTable;
use jinxing\admin\helpers\Helper;

// 定义标题和面包屑信息
$this->title = '上传文件';

$url = Helper::getAssetUrl();
$depends = ['depends' => 'jinxing\admin\web\AdminAsset'];
$this->registerCssFile($url . '/css/dropzone.css', $depends);
$this->registerJsFile($url . '/js/dropzone.min.js', $depends);
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        var myDropzone = null;
        $.extend(MeTables, {
            /**
             * 定义编辑表单(函数后缀名Create)
             * 使用配置 edit: {"type": "email", "id": "user-email"}
             * edit 里面配置的信息都通过 params 传递给函数
             */
            dropzoneCreate: function () {
                return '<div id="dropzone" class="dropzone"></div>';
            }
        });
        var m = meTables({
            title: "上传文件",
            number: false,
            table: {
                columns: [
                    {
                        title: "Id",
                        data: "id",
                        defaultOrder: "desc",
                        edit: {type: "hidden"}
                    },
                    {
                        title: "标题",
                        data: "title",
                        edit: {type: "text", required: true, rangeLength: "[2, 250]"},
                        sortable: false,
                        search: {name: "title"}
                    },
                    {
                        title: "文件访问地址",
                        data: "url",
                        edit: {type: "dropzone"},
                        sortable: false,
                        createdCell: function (td, data) {
                            var html = '';
                            if (data) {
                                try {
                                    data = JSON.parse(data);
                                    for (var i in data) {
                                        html += "<img class='layer-image' src='" + data[i] + "' width='40px' height='40px'> ";
                                    }
                                } catch (e) {
                                }
                            }
                            $(td).html(html);
                        }
                    },
                    {
                        title: "创建时间",
                        data: "created_at",
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "修改时间",
                        data: "updated_at",
                        createdCell: MeTables.dateTimeString
                    }
                ]
            }
        });

        var $form = null;
        $.extend(m, {
            // 显示的前置和后置操作
            afterShow: function (data, child) {
                if (!$form) $form = $("#edit-form");
                myDropzone.removeAllFiles();
                $("#dropzone").find("div.dz-image-preview").remove();
                $form.find("input[name='url[]']").remove();
                if (this.action === "update" && data["url"]) {
                    try {
                        var imgs = JSON.parse(data["url"]);
                        for (var i in imgs) {
                            var mockFile = {name: "Filename" + i, size: 12345};
                            myDropzone.emit("addedfile", mockFile);
                            myDropzone.emit("thumbnail", mockFile, imgs[i]);
                            myDropzone.emit("complete", mockFile);
                            addInput(mockFile.name, imgs[i]);
                        }
                    } catch (e) {
                        console.error(e)
                    }
                }
                return true;
            }
        });

        function addInput(name, url) {
            $form.append('<input type="hidden" data-name="' + name + '" name="url[]" value="' + url + '">');
        }

        $(function () {
            m.init();

            $form = $("#edit-form");

            // 新版本上传修改
            var csrfParam = $('meta[name=csrf-param]').attr('content') || "_csrf",
                csrfToken = $('meta[name=csrf-token]').attr('content'),
                params = {};
            params[csrfParam] = csrfToken;

            Dropzone.autoDiscover = false;

            try {
                myDropzone = new Dropzone("#dropzone", {
                    url: "<?=Url::toRoute(['uploads/upload', 'sField' => 'url'])?>",
                    // The name that will be used to transfer the file
                    paramName: "UploadForm[url]",
                    params: params,
                    maxFilesize: 2, // MB
                    addRemoveLinks: true,
                    dictDefaultMessage:
                        '<span class="bigger-150 bolder"><i class="ace-icon fa fa-caret-right red"></i> Drop files</span> to upload \
                        <span class="smaller-80 grey">(or click)</span> <br /> \
                        <i class="upload-icon ace-icon fa fa-cloud-upload blue fa-3x"></i>'
                    ,
                    dictResponseError: 'Error while uploading file!',
                    //change the previewTemplate to use Bootstrap progress bars
                    previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n<div class=\"dz-details\">\n<div class=\"dz-filename\"><span data-dz-name></span></div>\n<div class=\"dz-size\" data-dz-size></div>\n<img data-dz-thumbnail />\n</div>\n<div class=\"progress progress-small progress-striped active\"><div class=\"progress-bar progress-bar-success\" data-dz-uploadprogress></div></div>\n<div class=\"dz-success-mark\"><span></span></div>\n<div class=\"dz-error-mark\"><span></span></div>\n<div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>"
                    , init: function () {
                        this.on("success", function (file, response) {
                            if (response.code === 0) {
                                addInput(file.name, response.data.sFilePath);
                            } else {
                                this.removeFile(file);
                                layer.msg(response.msg, {icon: 5, time: 1000});
                            }
                        });

                        this.on("removedfile", function (file) {
                            $form.find("input[data-name='" + file.name + "']").remove();
                        })
                    }
                });
            } catch (e) {
                console.error(e);
            }

            // 图片显示
            $(document).on("click", ".layer-image", function () {
                var url = $(this).prop('src');
                layer.open({
                    type: 1,
                    title: false,
                    skin: 'layui-layer-nobg', //没有背景色
                    shadeClose: true,
                    content: '<img class="center-block" src="' + url + '" style="max-height:90%;max-width:90%">'
                });
            });
        });
    </script>
<?php $this->endBlock(); ?>