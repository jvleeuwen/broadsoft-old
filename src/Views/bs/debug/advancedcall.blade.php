<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Debug:AdvancedCall</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet"></link>
        <style>
            body {
                /*background-color:#FDFDFD;*/
                /*color: #FFFFFF;*/
            }
            pre {
                background-color: ghostwhite;
                color: #777;
                border: 1px solid #777;
                padding: 10px 20px;
                margin: 20px; 
                }
        </style>
    </head>
    <body>
        <div id="app">
            <div id="wrapper">
                 <div class="col-md-12">      
                    <div id="row">
                        <nav class="navbar navbar-default">
                            <div class="container-fluid">
                                <ul class="nav navbar-nav">
                                    <li>
                                        <a href="{{ url('bs/debug') }}"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> bs/debug/index</a>
                                    @foreach($routes as $route)
                                        @foreach(array_pluck($route, 'prefix') as $prefix)
                                            @if($prefix == "bs/debug")
                                                <li>
                                                    
                                                    <a href="{{ url($route->uri) }}"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> {{{ $route->uri }}}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div class="col-md-12">      
                        <div id="row">
                            <div id='bs'>
                                <h1>Debug Advanced Call Event</h1>
                                <small>* latest 25 messages, showing latest first</small>
                                <debugadvancedcall></debugadvancedcall>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="/js/broadsoft.js"></script>
</html>