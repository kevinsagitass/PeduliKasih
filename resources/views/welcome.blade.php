<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Peduli Kasih</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .top-left {
                position: absolute;
                left: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .desc {
                background-image: url("background.jpg");
                width: 100%;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
            }

            .content {
                width: 100%;
            }

            .fa {
                padding: 20px;
                font-size: 30px;
                width: 30px;
                margin: 10px;
                text-align: center;
                text-decoration: none;
                border-radius: 50%;
                }

                /* Set a specific color for each brand */
                /* Facebook */
                .fa-facebook {
                background: #3B5998;
                color: white;
                }

                .fa-youtube {
                background: #bb0000;
                color: white;
                }

                .fa-instagram {
                background: #125688;
                color: white;
                }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="top-left links">
                <a href="{{url('/')}}">Peduli Kasih</a>
            </div>
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="row" style="border-bottom: 5px solid black">
                    <div class="col" style="margin-top: 5%">
                        <img src="{{asset('banner.png')}}" alt="Peduli Kasih Banner" style="width: 100%">
                    </div>
                </div>
                <div class="row desc">
                    <div class="col-md-6">
                        <h1 style="margin: 0; padding-top: 10px">Peduli Kasih</h1>
                    </div>
                    <div class="col-md-6">
                        <h3>Volunteer Made Easy With Our Peduli Kasih Apps</h3>
                        <h3>Peduli Kasih Helps Promotor to Promote Their Events and Volunteers to Join Event</h3>
                        <h1>Come and Join Us</h1>
                        <a href="#" class="fa fa-facebook"></a>
                        <a href="#" class="fa fa-instagram"></a>
                        <a href="#" class="fa fa-youtube"></a>
                    </div>
                </div>
                <div class="row">
                </div>
            </div>
        </div>
    </body>
</html>
