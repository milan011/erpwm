<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="cache-control" content="no-cache,no-store, must-revalidate" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1 ,maximum-scale=1">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <title>淘车乐信息管理系统</title>

</head>
<body>
    <script src="{{URL::asset('static/tinymce4.7.5/tinymce.min.js')}}"></script>
    <div id="app"></div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
