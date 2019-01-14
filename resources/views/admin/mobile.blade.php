<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="cache-control" content="no-cache,no-store, must-revalidate" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="{{ mix('/css/appMobile.css') }}">
    <title>淘车乐信息管理系统</title>

</head>
<body>
    <div id="app">mobile</div>
    
    <script src="{{ mix('js/appMobile.js') }}"></script>
</body>
</html>