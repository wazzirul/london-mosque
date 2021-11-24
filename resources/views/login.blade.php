<html>
    <head>
        <title>{{config('l5-swagger.api.title')}}</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-londonmosque.png') }}"/>
        <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="vendor/css/ready.min.pro.css">
        <link rel="stylesheet" href="vendor/css/ready.css">
        <style>
            .img-fluid{
                max-height: 150px;
                width:auto;
                display: block;
                margin: 0 auto;
            }
            body{
                background-color : #e8f6f0 !important;
            }
            .form-floating-label .form-control.filled+.placeholder{
                color: #49cc90 !important;
            }
            .LoginForm--btn{
                color: #fff !important;
                background: #49cc90 !important;
                border-color: #49cc90 !important;
            }
        </style>
    </head>
    <body class="login">
        <div class="wrapper wrapper-login">
            <div class="container container-login animated fadeIn" style="display: block;">
                <img src="{{ asset('assets/img/LogoLondon-Mosque.png') }}" class="img-fluid">   
                <h3 class="text-center">Login API Documentation</h3>
                <div class="login-form">
                    @if($message = Session::get('failed'))
                    <div class="alert alert-danger alert-block">
                        <button class="close" type="button" data-dismiss="alert">x</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    <br/>
                    @endif

                    @if($message = Session::get('alert'))
                    <div class="alert alert-warning alert-block">
                        <button class="close" type="button" data-dismiss="alert">x</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    <br/>
                    @endif
                    <form action="/login-proses" method="post">
                        {{ csrf_field() }}
                    
                        <div class="form-group form-floating-label">
                            <input id="username" name="username" type="text" class="form-control input-border-bottom" required="">
                            <label for="username" class="placeholder LoginForm--label">Username</label>
                        </div>
                        <div class="form-group form-floating-label">
                            <input id="password" name="password" type="password" class="form-control input-border-bottom" required="">
                            <label for="password" class="placeholder LoginForm--label">Password</label>
                            <div class="show-password">
                                <i class="flaticon-interface"></i>
                            </div>
                        </div>
                        <div class="form-action">
                            <button type="submit" class="btn LoginForm--btn btn-rounded btn-login">Sign In</button>
                        </div>
                    </form>
            </div>
            </div>
            <!--END OF LOGIN PAGE END OF LOGIN PAGE END OF LOGIN PAGE END OF LOGIN PAGE-->
        
        </div>
    </body>
    
<script src="vendor/js/jquery-3.6.0.min.js"></script>
<script src="vendor/js/jquery-ui.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="vendor/js/ready-pro.js"></script>
</html>