define({ "api": [
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "./public/apidoc/main.js",
    "group": "D__laravel_template_with_vue_backend_public_apidoc_main_js",
    "groupTitle": "D__laravel_template_with_vue_backend_public_apidoc_main_js",
    "name": ""
  },
  {
    "type": "delete",
    "url": "/api/admin/:id",
    "title": "删除指定的管理员",
    "group": "admin",
    "success": {
      "examples": [
        {
          "title": "用户删除成功",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "用户删除失败",
          "content": "HTTP/1.1 404 ERROR\n{\n\"status\": \"error\",\n\"status_code\": 404\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/UserController.php",
    "groupTitle": "admin",
    "name": "DeleteApiAdminId"
  },
  {
    "type": "get",
    "url": "/api/admin",
    "title": "显示管理员列表",
    "group": "admin",
    "success": {
      "examples": [
        {
          "title": "返回管理员信息列表",
          "content": "HTTP/1.1 200 OK\n{\n \"data\": [\n    {\n      \"id\": 2 // 整数型  用户标识\n      \"name\": \"test\"  //字符型 用户昵称\n      \"email\": \"test@qq.com\"  // 字符型 用户email，管理员登录时的email\n      \"role\": \"admin\" // 字符型 角色  可以取得值为admin或editor\n      \"avatar\": \"\" // 字符型 用户的头像图片\n    }\n  ],\n\"status\": \"success\",\n\"status_code\": 200,\n\"links\": {\n\"first\": \"http://manger.test/api/admin?page=1\",\n\"last\": \"http://manger.test/api/admin?page=19\",\n\"prev\": null,\n\"next\": \"http://manger.test/api/admin?page=2\"\n},\n\"meta\": {\n\"current_page\": 1, // 当前页\n\"from\": 1, //当前页开始的记录\n\"last_page\": 19, //总页数\n\"path\": \"http://manger.test/api/admin\",\n\"per_page\": 15,\n\"to\": 15, //当前页结束的记录\n\"total\": 271  // 总条数\n}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/UserController.php",
    "groupTitle": "admin",
    "name": "GetApiAdmin"
  },
  {
    "type": "get",
    "url": "/api/admin/:id",
    "title": "显示指定的管理员",
    "group": "admin",
    "success": {
      "examples": [
        {
          "title": "返回管理员信息",
          "content": "HTTP/1.1 200 OK\n{\n\"data\": {\n  \"id\": 1,\n  \"name\": \"wmhello\",\n  \"email\": \"871228582@qq.com\",\n  \"role\": \"admin\",\n  \"avatar\": \"\"\n},\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/UserController.php",
    "groupTitle": "admin",
    "name": "GetApiAdminId"
  },
  {
    "type": "post",
    "url": "/api/admin",
    "title": "建立新的管理员",
    "group": "admin",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/UserController.php",
    "groupTitle": "admin",
    "name": "PostApiAdmin"
  },
  {
    "type": "post",
    "url": "/api/admin/:id/reset",
    "title": "重置指定管理员的密码",
    "group": "admin",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户密码</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回密码设置成功的结果",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/UserController.php",
    "groupTitle": "admin",
    "name": "PostApiAdminIdReset"
  },
  {
    "type": "post",
    "url": "/api/admin/upload",
    "title": "头像图片上传",
    "group": "admin",
    "header": {
      "examples": [
        {
          "title": "http头部请求:",
          "content": "{\n  \"content-type\": \"application/form-data\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "上传成功",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200，\n\"data\": {\n  \"url\" : 'uploads/3201278123689.png'\n }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "上传失败",
          "content": "HTTP/1.1 400 ERROR\n{\n\"status\": \"error\",\n\"status_code\": 400\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/UserController.php",
    "groupTitle": "admin",
    "name": "PostApiAdminUpload"
  },
  {
    "type": "put",
    "url": "/api/admin/:id",
    "title": "更新指定的管理员",
    "group": "admin",
    "header": {
      "examples": [
        {
          "title": "http头部请求:",
          "content": "{\n  \"content-type\": \"application/x-www-form-urlencoded\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数例子",
          "content": "{\n     name: 'test',\n     role: 'editor',\n     avatar: 'uploads/20174356.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回密码设置成功的结果",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/UserController.php",
    "groupTitle": "admin",
    "name": "PutApiAdminId"
  },
  {
    "type": "get",
    "url": "/api/user",
    "title": "获取当前登陆的用户信息",
    "group": "login",
    "success": {
      "examples": [
        {
          "title": "信息获取成功",
          "content": "HTTP/1.1 200 OK\n{\n\"data\": {\n   \"id\": 1,\n   \"name\": \"xxx\",\n   \"email\": \"xxx@qq.com\",\n   \"roles\": \"xxx\", //角色: admin或者editor\n   \"avatar\": \"\"\n },\n \"status\": \"success\",\n \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/UserController.php",
    "groupTitle": "login",
    "name": "GetApiUser"
  },
  {
    "type": "post",
    "url": "/api/login",
    "title": "用户登陆",
    "group": "login",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户email</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户密码</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "登陆成功",
          "content": "HTTP/1.1 200 OK\n{\n\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJS\",\n\"expires_in\": 900 // 过期时间\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "用户身份验证失败",
          "content": "HTTP/1.1 421 用户名或者密码输入错误\n{\n\"status\": \"login error\",\n\"status_code\": 421,\n\"message\": \"Credentials not match\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/Auth/LoginController.php",
    "groupTitle": "login",
    "name": "PostApiLogin"
  },
  {
    "type": "post",
    "url": "/api/logout",
    "title": "注销用户登陆",
    "group": "login",
    "success": {
      "examples": [
        {
          "title": "注销成功",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200,\n\"message\": \"logout success\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/Auth/LoginController.php",
    "groupTitle": "login",
    "name": "PostApiLogout"
  },
  {
    "type": "post",
    "url": "/api/token/refresh",
    "title": "Token刷新",
    "group": "login",
    "success": {
      "examples": [
        {
          "title": "刷新成功",
          "content": "HTTP/1.1 200 OK\n{\n\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJS\",\n\"expires_in\": 900 // 过期时间\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "刷新失败",
          "content": "HTTP/1.1 401 未认证\n{\n\"status\": \"login error\",\n\"status_code\": 401,\n\"message\": \"Credentials not match\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/Auth/LoginController.php",
    "groupTitle": "login",
    "name": "PostApiTokenRefresh"
  },
  {
    "type": "get",
    "url": "/api/getSession",
    "title": "获取学期信息",
    "group": "other",
    "success": {
      "examples": [
        {
          "title": "返回学期信息列表,",
          "content": "HTTP/1.1 200 OK\n{\n \"data\": [\n    {\n      \"id\": 2 // 整数型  学期标识\n      \"year\": 2016  //数字型 学年\n      \"team\": 2  //  数字型 学期\n    }\n  ],\n \"status\": \"success\",\n \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/SessionController.php",
    "groupTitle": "other",
    "name": "GetApiGetsession"
  },
  {
    "type": "delete",
    "url": "/api/role/:id",
    "title": "删除指定的角色信息",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>角色标识</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "信息获取成功:",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "删除失败，没有指定的角色:",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404，\n\"message\": \"删除失败\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/RoleController.php",
    "groupTitle": "role",
    "name": "DeleteApiRoleId"
  },
  {
    "type": "get",
    "url": "/api/role",
    "title": "显示学期列表",
    "group": "role",
    "success": {
      "examples": [
        {
          "title": "返回所有的角色",
          "content": "HTTP/1.1 200 OK\n {\n \"data\": [\n {\n \"id\": 2,\n \"name\": \"admin\",\n \"explain\": \"管理员\",\n \"remark\": null\n }\n ],\n \"status\": \"success\",\n \"status_code\": 200,\n \"links\": {\n \"first\": \"http://manger.test/api/role?page=1\",\n \"last\": \"http://manger.test/api/role?page=1\",\n \"prev\": null,\n \"next\": null\n },\n \"meta\": {\n \"current_page\": 1,\n \"from\": 1,\n  \"last_page\": 1,\n \"path\": \"http://manger.test/api/role\",\n \"per_page\": 15,\n \"to\": 30,\n \"total\": 5\n }\n }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/RoleController.php",
    "groupTitle": "role",
    "name": "GetApiRole"
  },
  {
    "type": "get",
    "url": "/api/role/:id",
    "title": "获取一条角色",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>角色标识</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "信息获取成功:",
          "content": "HTTP/1.1 200 OK\n{\n\"data\": [\n {\n \"id\": 2,\n \"name\": \"admin\",\n \"explain\": \"管理员\",\n \"remark\": null\n }\n],\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "指定的角色不存在:",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/RoleController.php",
    "groupTitle": "role",
    "name": "GetApiRoleId"
  },
  {
    "type": "patch",
    "url": "/api/role/:id",
    "title": "更新角色信息",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>角色标识 路由上使用</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>角色名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "explain",
            "description": "<p>角色描述</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "remark",
            "description": "<p>备注 可选</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求事例 建立学期 2017-2018上学期:",
          "content": "{\nname: 'admin',\nexplain: '管理员',\nremark: '管理员'\n}",
          "type": "object"
        }
      ]
    },
    "header": {
      "examples": [
        {
          "title": "请求头:",
          "content": "{ \"Content-Type\": \"application/x-www-form-urlencoded\" }",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "操作成功:",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错:",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/RoleController.php",
    "groupTitle": "role",
    "name": "PatchApiRoleId"
  },
  {
    "type": "post",
    "url": "/api/role",
    "title": "新建一条角色信息",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>角色名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "explain",
            "description": "<p>角色说明</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "remark",
            "description": "<p>角色备注 可选</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求事例 建",
          "content": "{\nname: 'app',\nexplain: '应用管理者'\n}",
          "type": "object"
        }
      ]
    },
    "header": {
      "examples": [
        {
          "title": "请求头:",
          "content": "{ \"Content-Type\": \"application/x-www-form-urlencoded\" }",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "操作成功:",
          "content": "HTTP/1.1 200 OK\n{\n\"status\": \"success\",\n\"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错:",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/RoleController.php",
    "groupTitle": "role",
    "name": "PostApiRole"
  },
  {
    "type": "delete",
    "url": "/api/session/:id",
    "title": "删除指定的学期信息",
    "group": "session",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>学期标识</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "信息获取成功:",
          "content": "HTTP/1.1 200 OK\n{\n \"status\": \"success\",\n \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "删除失败，没有指定的学期:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"error\",\n  \"status_code\": 404，\n  \"message\": \"删除失败\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/SessionController.php",
    "groupTitle": "session",
    "name": "DeleteApiSessionId"
  },
  {
    "type": "get",
    "url": "/api/session",
    "title": "显示学期列表",
    "group": "session",
    "success": {
      "examples": [
        {
          "title": "返回学期信息列表，分页显示，每页15条记录,",
          "content": "HTTP/1.1 200 OK\n{\n \"data\": [\n    {\n      \"id\": 2 // 整数型  学期标识\n      \"year\": 2016  //数字型 学年\n      \"team\": 2  //  数字型 学期\n      \"remark\": \"2016-2017下学期\" // 备注说明\n      \"one\": 20,  // 高一年级班级数\n      \"two\": 20,  // 高二年级班级数\n      \"three\": 20  // 高三年级班级数\n    }\n  ],\n \"status\": \"success\",\n \"status_code\": 200,\n \"links\": {\n \"first\": \"http://manger.test/api/session?page=1\",\n \"last\": \"http://manger.test/api/session?page=1\",\n \"prev\": null,\n \"next\": null\n },\n \"meta\": {\n \"current_page\": 1,   //当前页\n \"from\": 1,  // 当前记录\n \"last_page\": 1,    //最后一页\n \"path\": \"http://manger.test/api/session\",\n \"per_page\": 15,  //\n \"to\": 4,  //当前页最后一条记录\n \"total\": 4 // 总记录\n }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/SessionController.php",
    "groupTitle": "session",
    "name": "GetApiSession"
  },
  {
    "type": "get",
    "url": "/api/session/:id",
    "title": "获取指定学期信息",
    "group": "session",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>学期标识</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "信息获取成功:",
          "content": "HTTP/1.1 200 OK\n{\n \"data\": [\n    {\n      \"id\": 2 // 整数型  学期标识\n      \"year\": 2016  //数字型 学年\n      \"team\": 2  //  数字型 学期\n      \"remark\": \"2016-2017下学期\" // 备注说明\n      \"one\": 20,  // 高一年级班级数\n      \"two\": 20,  // 高二年级班级数\n      \"three\": 20  // 高三年级班级数\n    }\n  ],\n \"status\": \"success\",\n \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "指定的学期不能存在:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"error\",\n  \"status_code\": 404\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/SessionController.php",
    "groupTitle": "session",
    "name": "GetApiSessionId"
  },
  {
    "type": "patch",
    "url": "/api/session/:id",
    "title": "更新学期信息",
    "group": "session",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "id",
            "description": "<p>学期标识 路由上使用</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "year",
            "description": "<p>学年</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "allowedValues": [
              "1",
              "2"
            ],
            "optional": false,
            "field": "team",
            "description": "<p>学期(1=&gt;上学期 2=&gt;下学期)</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "one",
            "description": "<p>高一班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "two",
            "description": "<p>高二班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "three",
            "description": "<p>高三班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "remark",
            "description": "<p>备注 可选</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求事例 建立学期 2017-2018上学期:",
          "content": "  {\n     year: 2017,\n     team: 1,\n     remark: '2017-2018上学期',\n     one: 20,\n     two: 20,\n     three: 20\n\n}",
          "type": "object"
        }
      ]
    },
    "header": {
      "examples": [
        {
          "title": "请求头:",
          "content": "{ \"Content-Type\": \"application/x-www-form-urlencoded\" }",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "操作成功:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": \"success\",\n  \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"error\",\n  \"status_code\": 404,\n  \"message\": \"验证出错,请按要求填写\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/SessionController.php",
    "groupTitle": "session",
    "name": "PatchApiSessionId"
  },
  {
    "type": "post",
    "url": "/api/session",
    "title": "新建一个学期信息",
    "group": "session",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "year",
            "description": "<p>学年</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "allowedValues": [
              "1",
              "2"
            ],
            "optional": false,
            "field": "team",
            "description": "<p>学期(1=&gt;上学期 2=&gt;下学期)</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "one",
            "description": "<p>高一班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "two",
            "description": "<p>高二班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "three",
            "description": "<p>高三班级数</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "remark",
            "description": "<p>备注 可选</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求事例 建立学期 2017-2018上学期:",
          "content": "  {\n     year: 2017,\n     team: 1,\n     one: 20,\n     two: 20,\n     three: 20\n}",
          "type": "object"
        }
      ]
    },
    "header": {
      "examples": [
        {
          "title": "请求头:",
          "content": "{ \"Content-Type\": \"application/x-www-form-urlencoded\" }",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "操作成功:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": \"success\",\n  \"status_code\": 200\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"error\",\n  \"status_code\": 404,\n  \"message\": \"验证出错,请按要求填写\"\n}",
          "type": "json"
        },
        {
          "title": "重复提交:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"error\",\n  \"status_code\": 400,\n  \"message\": \"你提交的学期信息已经存在，无法新建\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/SessionController.php",
    "groupTitle": "session",
    "name": "PostApiSession"
  }
] });
