<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{{ $subject }}</title>

        <style>
            .body {
                background-color: #f6f6f6;
                width: 100%;
            }

            .container {
                display: block;
                margin: 0 auto !important;
                max-width: 480px;
                padding: 10px;
                width: 480px;
            }

            .header {
                height: 50px;
                background-color: white;
                width: 100%;
                font-size: 25px;
                font-weight: bold;
            }

            a {
                width: 100%;
                font-weight: bold;
                border-radius: 50px;
                padding: 10px;
                text-decoration: none;
                height: 35px;
                color: white !important;
                background-color: #39BC6E;
                font-size: 15px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>{{ config('app.name') }}</h1>
            <p style="font-size: 25px; margin: 0;">{{ $title }}</p>
            <p style="color: red;">{{ $lockerClaim->locker->guid }}</p>
            <p style="padding-bottom: 10px;">Set your locker pass code</p>
            <a href="{{ $setupUrl }}">Set pass code</a>
        </div>
    </body>
</html>
