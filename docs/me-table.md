关于 meTables 配置说明
====================

[← 控制器配置](./controller.html) | [常见问题 →](./faq.html)

[TOC]

## 基础配置项

|配置名称 | 配置类型 | 默认值 | 说明信息|
|:------------------|:----------|:------|:---------------|
|title              | string    |       | 表格标题(新增和修改modal弹出标题、导出文件标题)|
|pk                 | string    | id    | 数据主键值(在批量删除数据时需要)|
|checkbox           | object    |       | 多选列(多选操作, 不显示设置为 null or false)|
|params             | object    | null  | 默认查询的参数，会在查询请求时发送给后端|
|number             | object    |       | 编号列(不显示设置为 null or false)|

## 请求地址配置

配置名称 | 配置类型 | 默认值 | 说明信息|
:------------------|:-----------|:-----------|:---------------|
urlPrefix          | string     |            | 请求路由前缀|
urlSuffix          | string     |            | 请求路由后缀|
url                | object     |            | 请求地址信息|
url.search         | string     | search     | 查询数据请求地址|
url.create         | string     | create     | 创建数据请求地址|
url.update         | string     | update     | 修改数据请求地址|
url.delete         | string     | delete     | 删除数据请求地址|
url.export         | string     | export     | 导出数据请求地址|
url.upload         | string     | upload     | 上传文件请求地址|
url.editable       | string     | editable   | 行内编辑请求地址|
url.deleteAll      | string     | delete-all | 批量删除请求地址|

如果需要修改某一个地址配置，如下配置:

```js
var m = meTables({
    urlPrefix: "/admin/",
    urlSuffix: ".html",
    url: {
        search: "me-search"  
    }
}); 
```

上面的配置信息，生成的请求地址如下:

> search: localhost/admin/me-search.html

> update: localhost/admin/update.html

**路由生成规则:urlPrefix + url.action + urlSuffix**

## 顶部按钮组的配置

配置名称 | 配置类型 | 默认值 | 说明信息
:------------------|:-----------|:-----------|:---------------
buttonSelector          | string     | #me-table-buttons                | jquery 的选择器，按钮组配置信息会以这个为容器，添加到里面$(buttonSelector).append('按钮组信息')
buttonType              | string     | append                           | 什么方式添加到选择容器中
buttons.create.icon     | string     | ace-icon fa fa-plus-circle blue  | 创建按钮的icon
buttons.create.className| string     | btn btn-white btn-primary btn-bold| 创建按钮的class 名称

默认有5个按钮组信息，如下:
1. create:      创建按钮
2. updateAll:   修改按钮
3. deleteAl:    删除按钮
4. refresh:     刷新按钮
5. export:      导出按钮

> 每个按钮都有如下按钮配置项: **icon, className** 

### 隐藏指定按钮

**如果想要不显示那个按钮，只需要那个按钮的配置设置为 `false` 或者 `null`,就好：**

```js
var m = meTables({
    buttons: {
        create: false,
        updateAll: false
    }
});
```

### 自定义按钮

**如果需要添加一个按钮，那么直接配置一个按钮信息:**

```js
var m = meTables({
    buttons: {
        // 自定义按钮配置
        customize: {
            icon: "ace-icon fa fa-plus-circle yellow",
            className: "btn btn-white btn-primary btn-bold",
            text: "自定义按钮",          // 按钮文字
            "data-func": "customize"  // 指定调用自己的那个函数
        }
    }
});
// 不过自定义按钮，需要给 m 添加函数 函数名称就是配置按钮中 data-func 指定的函数名称
$.extend(m, {
    customize: function () {
        alert("My custom button");
    }
});
```

## 表格操作一列的配置信息

|配置名称 | 配置类型 | 默认值 | 说明信息|
|:------------------|:-----------|:-----------|:---------------|
|operations.width        | string     | 120px               | 这一列的宽度|
|operations.buttons.see.title        | string  |                  | 按钮的`title`(缩小按钮需要显示title)|
|operations.buttons.see.button-title | string  |                  | 按钮的`title`|
|operations.buttons.see.className    | string  | btn-success      | 按钮的`className`用来控制样式|
|operations.buttons.see.cClass       | string  | me-table-detail  | 按钮的操作`class`用来处理点击事件|
|operations.buttons.see.icon         | string  | fa-search-plus   | 按钮的icon|
|operations.buttons.see.sClass       | string  | blue             | 按钮的颜色|

默认提供三个按钮选项:
1. see:     查看详情
2. update:  修改数据
3. delete:  删除数据

> 每个按钮都有如下配置项字段: **`className`, `title`, `button-title`, `cClass`, `icon`, `sClass`** 

### 隐藏指定操作按钮

**隐藏指定按钮，配置那个按钮为`null` or `false` 就好**

```js
var m = meTables({
    operations: {
        buttons: {
            // 不显示这个按钮
            see: null,
            update: null
        }
    }
})

```

### 自定义显示隐藏按钮

**配置按钮`show:function(rows)`方法**

>`show`函数接受一个参数`rows`(这一行的数据)，需要返回`true`来确定显示

```js
var m = meTables({
    operations: {
        buttons: {
            // 配置show，根据情况显示删除 rows 表示这一行的数据
            delete: {
                show: function (rows) {
                    return rows.status === 1;
                }
            }
        }
    }
})
```

### 自定义操作按钮

>自定义按钮的监听`class`的规则为配置的`cClass` + `sTable`,这个是为了保证多个表格监听的唯一性

```js
var m = meTables({
    sTable: "#show-table", 	// 显示表格选择器
    operations: {
        buttons: {
            // 添加操作项，自定义按钮
            other: {
                title: "编辑权限",
                "button-title": "编辑权限",
                className: "btn-warning",
                cClass: "role-edit",
                icon: "fa-pencil-square-o",
                sClass: "yellow"
            }   
        }  
    }
});
// 需要自己定义点击按钮配置项 监听class 规则 cClass + sTable
$(document).on('click', '.role-edit-show-table', function () {
    
    // 获取到点击的这一行的数据
    var data = m.table.data()[$(this).data('row')];
    
    // 自定义处理方式
    if (data) {
        alert("My custom button");
    }
});
```

## jquery.dataTables.js 配置

配置名称 | 配置类型 | 默认值 | 说明信息
:------------------|:-----------|:-----------|:---------------
table              | object     |            | 就是 jquery.dataTables.js 的配置信息


### `columns` 的配置

[更多信息](http://www.datatables.club/reference/option/)

配置名称 | 配置类型 | 说明信息
:------------------|:-----------|:---------------
title        | string     | 这一列的 th 表头信息                 
data         | string     | 这一列的数据字段
render       | function   | render 函数 [详情说明查看](http://www.datatables.club/reference/option/columns.render.html)         
createdCell  | function   | createdCell 函数 [详情说明查看](http://www.datatables.club/reference/option/columns.createdCell.html)

简单说明:
```js
var m = meTables({
    table: {
        columns: [
            {
                title: "id",
                data: "id",
                render: function (data) {
                    return data === 1 ? "yes" : "no";
                }
            },
            {
                title: "name",
                data: "name",
                createdCell: function (td, data) {
                    $(td).html(data === 1 ? "username": "name");
                }
            }
        ]
    }
});
```

### `meTables` 提供的列的配置信息

配置名称 | 配置类型 | 默认值 | 说明信息
:------------------|:-----------|:-----------|:---------------
hide         | boolean     | false      | 这一列是否隐藏 true 表示 隐藏    
bHide        | boolean     | false      | 这一列是否隐藏 true 表示 隐藏 (hide Alias)
isHide       | boolean     | false      | 这一列是否隐藏 true 表示 隐藏 (hide Alias)        
export       | boolean     | false      | 这一列是否导出
bExport      | boolean     | false      | 这一列是否导出(export Alias)
isExport     | boolean     | false      | 这一列是否导出(export Alias)
view         | boolean     | true       | 这一列是否在详情里面显示出来 false 表示不显示
bViews       | boolean     | true       | 这一列是否在详情里面显示出来 false 表示不显示 (view Alias)
isViews      | boolean     | true       | 这一列是否在详情里面显示出来 false 表示不显示 (view Alias)
defaultOrder | string      | null       | 默认排序方式 (asc or desc)
search       | object      | undefined  | 搜索表单配置信息
edit         | object      | undefined  | 编辑表单配置信息
value        | object      | undefined  | 为搜索和编辑表单提供数据支持

Configuration example:
```js
var m = meTables({
    table: {
        columns: [
            {
                title: "id",
                data: "id",
                render: function (data) {
                    return data === 1 ? "yes" : "no";
                },
                defaultOrder: "desc",
                search: {type: "text"},
                edit: {type: "hidden"}
            },
            {
                title: "name",
                data: "name",
                createdCell: function (td, data) {
                    $(td).html(data === 1 ? "username": "name");
                },
                
                /**
                 * 为编辑表单select 提供下拉选项，如下配置生成编辑表单
                 * <select name="username" required=true number=true>
                 *     <option value="1">管理员</option>
                 *     <option value="2">用户</option>
                 * </select>    
                 */
                value: {"1": "管理员", "2": "用户"},
                edit: {type: "select", required: true, number: true}
            }
        ]
    }
});
```

#### 搜索表单type 类型 目前只支持输入框和下拉表单选项:

1. text
2. select

**可以自定义搜索表单类型:**
```js
$.extend(MeTables, {
    /**
     * 定义搜索表达(函数后缀名SearchCreate)
     * 使用配置 search: {"type": "email", "id": "search-email"}
     * search 里面配置的信息都通过 params 传递给函数
     */
    "emailSearchCreate": function(params) {
        return '<input type="text" name="' + params.name +'">';
    }
});
```

#### 编辑表单type 支持如下配置类型:

1. text
2. select
3. radio
4. checkbox
5. hidden
6. file
7. textarea
8. password

**可以自定义类型:**
```js
$.extend(MeTables, {
    /**
     * 定义编辑表单(函数后缀名Create)
     * 使用配置 edit: {"type": "email", "id": "user-email"}
     * edit 里面配置的信息都通过 params 传递给函数
     */
    "emailCreate": function(params) {
        return '<input type="email" name="' + params.name + '"/>';
    }
});
```

#### edit 和 search 里面配置的字段信息，都会通过html 属性字段生成到对应表单上面，
edit 中可以定义验证字段信息，具体可以查看 [jquery.validate.js的配置信息](https://jqueryvalidation.org/documentation/)
```js
var m = meTables({
    table: {
        columns: [
            {
                title: "id",
                data: "id",
                // 验证字段必须输入，且长度为 2 到 100 个字符
                edit: {type: "text", required: true, rangelength: "[2, 100]"}
            }
        ]
    }
});
```

### 说明： 

1. edit 和 search 中的 name 如果没有定义的化，会通过外层配置的 data 属性决定，所以一般可以不用写
2. edit 和 search 中的 type 默认为 text, 如果类型需要为 text 的话，可以省略不写

## 事件配置

|事件函数名称            | 说明|
|----------------------|------------|
|`beforeShow(data)`     | 在弹出 modal 之前触发|
|`afterShow(data)`      | 在弹出 modal 之后触发|
|`beforeSave(data)`     | 在编辑之前触发|
|`afterShow(data)`      | 在编辑之后触发|

1. **`beforeShow`,`afterShow` 事件，只有在修改的情况下 data 数据为编辑的数据**
2. **`beforeSave`,`afterSave` 事件，在创建、修改、删除、多删除 data 数据为对应的表单数据**

>上面四个事件函数，如果返回 === `false`, 都将会阻止程序继续执行

```js

var table = meTables({});
$.extend(table, {
    beforeShow: function(data) {
        alert(this.action); // this.action 只会有: "update" or "create" 表示是修改和创建时触发
        if (this.action === "update") {
            console.info(data);
        }
    },
    afterShow: function(data) {
        alert(this.action); // this.action 只会有: "update" or "create" 表示是修改和创建时触发
        if (this.action === "update") {
            console.info(data); // When modified, data is the data of the table row
        }
    },
    
    beforeSave: function(data) {
        alert(this.action); // this.action Can be: "update" or "create" or "delete" or "delete-all"
    },
    afterSave: function(data) {
        alert(this.action); // this.action Can be: "update" or "create" or "delete" or "delete-all"
    }
});
```

### 事件配置说明：

1. 如果想修改和新增表单显示的字段不一致的话，可以在 `beforeShow` 和 `afterShow` 中控制比如控制某个表单的显示隐藏

```js
var m = meTables({
    table: {
        columns: [
            {
                title: "用户名称",
                data: "username",
                edit: {
                    id: "username"
                }
            }
            
        ]
    }
});
// 用户名字段在创建的时候显示出来，修改的时候隐藏起来
$.extend(m, {
    beforeShow: function() {
        if (this.action === "update") {
            $("#username").hide();
        } else  {
            $("#username").show();
        }
    }
});
```

## 文件上传

[后端控制器配置参考](./controller.html#文件上传)

```js
var m = meTables({
    title: "管理员信息",
    // 第一步：需要配置一个上传文件选择器数组，一个表单可以配置多个上传文件处理
    fileSelector: ["#file"],
    table: {
        columns: [
            {
                title: "头像",
                data: "face",
                bHide: true,
                // 第二步： 配置字段为上传文件表单
                edit: {
                    type: "file",
                    options: {
                        id: "file",
                        name: "UploadForm[face]", // 这个 name 用来后台接收上传文件字段名称
                        "input-name": "face",
                        "input-type": "ace_file",
                        "file-name": "face"
                    }
                }
            }
            
        ]
    }
});
// 第三步：处理上传文件显示问题
// 1. 新增处理时候：上传过后，下次再打开不能显示之前的上传文件
// 2. 编辑处理时候：这条数据已经上传了文件，那么需要显示出来
$.extend(m, {
    beforeShow: function (data) {
        
        $("#file").ace_file_input("reset_input");
        
        // 修改复值
        if (this.action === "update" && !empty(data.face)) {
            $("#file").ace_file_input("show_file_list", [data.face]);
        }
    }
});
```

## 全局配置修改

```js
MeTables.defaultOptions.title = "测试"; // 修改标题 
```

参考所有配置项

## 所有配置项

```js
// 设置默认配置信息
  meTables.defaultOptions = {
    title: '',                  // 表格的标题
    language: 'zh-cn',          // 使用语言
    pk: 'id',		            // 行内编辑pk索引值
    sModal: '#table-modal',     // 编辑Modal选择器
    sTable: '#show-table', 	// 显示表格选择器
    sFormId: '#edit-form',		// 编辑表单选择器
    sMethod: 'POST',			// 查询数据的请求方式
    params: null,				// 请求携带参数
    searchHtml: '',				// 搜索信息额外HTML
    searchType: 'middle',		// 搜索表单位置
    searchForm: '#search-form',	// 搜索表单选择器
    searchInputEvent: 'blur',   // 搜索表单input事件,设置为空不监听
    searchSelectEvent: 'change',// 搜索表单select事件,设置为空不监听
    filters: 'filters',         // 查询参数

    // 请求相关
    isSuccess: function (json) {
      return json.code === 0
    },

    // 获取消息
    getMessage: function (json) {
      return json.msg
    },

    // 搜索信息
    search: {
      render: true,

      type: 'append',

      // 搜索表单按钮信息
      button: {
        submit: {
          class: 'btn btn-info btn-sm',
          icon: 'ace-icon fa fa-search bigger-110',
          style: 'margin-left: 5px;padding-top: 1px; padding-bottom:1px',
          text: meTables.getLanguage('search'),
        },
        reset: {
          class: 'btn btn-warning btn-sm',
          style: 'margin-left: 10px;padding-top: 1px; padding-bottom:1px',
          icon: 'ace-icon fa fa-undo bigger-110',
          text: meTables.getLanguage('reset'),
        },
      },
    },

    fileSelector: [],			// 上传文件选择器

    // 编辑表单信息
    form: {
      method: 'post',
      class: 'form-horizontal',
      name: 'edit-form',
      autocomplete: 'off',
    },

    // 编辑表单验证方式
    formValidate: {
      errorElement: 'div',
      errorClass: 'help-block',
      focusInvalid: false,
      highlight: function (e) {
        $(e).closest('.form-group').removeClass('has-info').addClass('has-error')
      },
      success: function (e) {
        $(e).closest('.form-group').removeClass('has-error')//.addClass('has-info');
        $(e).remove()
      },
    },

    // 表单编辑其他信息
    editFormParams: {				// 编辑表单配置
      bMultiCols: false,          // 是否多列
      iColsLength: 1,             // 几列
      aCols: [3, 9],              // label 和 input 栅格化设置
      sModalClass: '',			// 弹出模块框配置
      sModalDialogClass: '',		// 弹出模块的class
    },

    // 关于详情的配置
    bViewFull: false, // 详情打开的方式 1 2 打开全屏
    oViewConfig: {
      type: 1,
      shade: 0.3,
      shadeClose: true,
      maxmin: true,
      area: ['50%', 'auto'],
    },

    detailTable: {                   // 查看详情配置信息
      bMultiCols: false,
      iColsLength: 1,
    },

    // 关于地址配置信息
    urlPrefix: '',
    urlSuffix: '',
    url: {
      search: 'search',
      create: 'create',
      update: 'update',
      delete: 'delete',
      export: 'export',
      upload: 'upload',
      editable: 'editable',
      deleteAll: 'delete-all',
    },

    // dataTables 表格默认配置对象信息
    table: {
      // "fnServerData": fnServerData,		// 获取数据的处理函数
      // "sAjaxSource":      "search",		// 获取数据地址
      'bLengthChange': true, 			// 是否可以调整分页
      'bAutoWidth': false,           	// 是否自动计算列宽
      'bPaginate': true,			    // 是否使用分页
      'iDisplayStart': 0,
      'iDisplayLength': 10,
      'bServerSide': true,		 	// 是否开启从服务器端获取数据
      'bRetrieve': true,
      'bDestroy': true,
      // "processing": true,		    // 是否使用加载进度条
      // "searching": false,
      'sPaginationType': 'full_numbers',     // 分页样式
      // "order": [[1, "desc"]]       // 默认排序，
      // sDom: "t<'row'<'col-xs-6'li><'col-xs-6'p>>"
    },

    // 开启行处理
    editable: null,
    editableMode: 'inline',

    // 默认按钮信息
    buttonHtml: '',
    // 按钮添加容器
    buttonSelector: '#me-table-buttons',
    // 按钮添加方式
    buttonType: 'append',
    // 默认按钮信息
    buttons: {
      create: {
        'data-func': 'create',
        text: meTables.getLanguage('create'),
        icon: 'ace-icon fa fa-plus-circle blue',
        className: 'btn btn-white btn-primary btn-bold',
      },
      updateAll: {
        'data-func': 'updateAll',
        text: meTables.getLanguage('updateAll'),
        icon: 'ace-icon fa fa-pencil-square-o orange',
        className: 'btn btn-white btn-info btn-bold',
      },
      deleteAll: {
        'data-func': 'deleteAll',
        text: meTables.getLanguage('deleteAll'),
        icon: 'ace-icon fa fa-trash-o red',
        className: 'btn btn-white btn-danger btn-bold',
      },
      refresh: {
        'data-func': 'refresh',
        text: meTables.getLanguage('refresh'),
        icon: 'ace-icon fa  fa-refresh',
        className: 'btn btn-white btn-success btn-bold',
      },
      export: {
        'data-func': 'export',
        text: meTables.getLanguage('export'),
        icon: 'ace-icon glyphicon glyphicon-export',
        className: 'btn btn-white btn-warning btn-bold',
      },
    }

    // 需要序号
    , number: {
      title: meTables.getLanguage('meTables', 'number'),
      data: null,
      view: false,
      render: function (data, type, row, meta) {
        if (!meta || $.isEmptyObject(meta)) {
          return false
        }

        return meta.row + 1 + meta.settings._iDisplayStart
      },
      sortable: false,
    }

    // 需要多选框
    , checkbox: {
      data: null,
      sortable: false,
      class: 'center text-center',
      title: '<label class="position-relative">' +
        '<input type="checkbox" class="ace" /><span class="lbl"></span></label>',
      view: false,
      createdCell: function (td, data, array, row) {
        $(td).html('<label class="position-relative">' +
          '<input type="checkbox" class="ace" data-row="' + row + '" value="' + row + '"/>' +
          '<span class="lbl"></span>' +
          '</label>')
      },
    }

    // 操作选项
    , operations: {
      title: meTables.getLanguage('meTables', 'operations'),
      width: '120px',
      defaultContent: '',
      sortable: false,
      data: null,
      buttons: {
        see: {
          title: meTables.getLanguage('meTables', 'operations_see'),
          className: 'btn-success',
          cClass: 'me-table-detail',
          icon: 'fa-search-plus',
          sClass: 'blue',
          'min-icon': 'fa-search-plus',
        },
        update: {
          title: meTables.getLanguage('meTables', 'operations_update'),
          className: 'btn-info',
          cClass: 'me-table-update',
          icon: 'fa-pencil-square-o',
          sClass: 'green',
          'min-icon': 'fa-pencil-square-o',
        },
        delete: {
          title: meTables.getLanguage('meTables', 'operations_delete'),
          className: 'btn-danger',
          cClass: 'me-table-delete',
          icon: 'fa-trash-o',
          sClass: 'red',
          'min-icon': 'fa-trash-o',
        },
      },
    },
};
```

[← 控制器配置](./controller.html) | [常见问题 →](./faq.html)