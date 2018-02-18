<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>电子表格文件上传实例</title>
</head>
<body>
<form action="{{url('/file/storeXls')}}" method="post"  enctype="multipart/form-data" >
    <input type="file" name="file1" accept="application/vnd.ms-excel"><br>
    <input type="submit" value="文件上传">
    {{ csrf_field() }}
</form>
</body>
</html>
