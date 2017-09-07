<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,900'
          rel='stylesheet'
          type='text/css'>
    {{-- <link href="{{ mix("css/app.css") }}" rel="stylesheet"/> --}}
    <meta name="google" value="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
</body>
<script src="{{ mix('js/broadsoft.js') }}"></script>
</html>