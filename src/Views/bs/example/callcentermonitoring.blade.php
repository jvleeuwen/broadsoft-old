<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Example:Callcenter Monitoring}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet"></link>
        <style>
            .Sign-Out {
                text-decoration: line-through;
            }
            .Available {
                background-color: green;
                color: white;
            }
            .Unavailable{
                background-color: red;
                color: white;
            }
            .Sign-In{
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div id="wrapper">
            <div class="col-md-12">      
                <div id="row">
                    <nav class="navbar navbar-default">
                            <div class="container-fluid">
                                <ul class="nav navbar-nav">
                                    <li>
                                        <a href="{{ url('bs/example') }}"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> bs/example/index</a>
                                    @foreach($routes as $route)
                                        @foreach(array_pluck($route, 'prefix') as $prefix)
                                            @if($prefix == "bs/example")
                                                @if(str_contains(url($route->uri), '{slug}'))
                                                    <li>
                                                        
                                                        <a href="{{ str_replace('{slug}','voip',url($route->uri)) }}"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> {{{ $route->uri }}}</a>
                                                    </li>
                                                @else
                                                    <li>
                                                        
                                                        <a href="{{ url($route->uri) }}"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> {{{ $route->uri }}}</a>
                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </nav>
                </div>
                 <div class="col-md-12">      
                    <div id="row">
                    <h1>Callcenter Monitoring Example:</h1>

                    <div id='bs'>
                        <examplecallcentermonitoring callcenters="{{ json_encode($callcenters) }}"></examplecallcentermonitoring>
                    </div>
                </div>
            </div>
        </div>
        <script src="/js/broadsoft.js"></script>
    </body>
</html>