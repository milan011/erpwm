<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="cache-control" content="no-cache,no-store, must-revalidate" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="{{ mix('/css/appPc.css') }}">
    <title>淘车乐信息管理系统</title>

</head>
<body>
    <script src="{{URL::asset('static/tinymce4.7.5/tinymce.min.js')}}"></script>
    <div id="app"></div>
    
    <script src="{{ mix('js/appPc.js') }}"></script>
</body>
</html>