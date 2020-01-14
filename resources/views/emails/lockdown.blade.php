<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Lockdown locker {{ $lockerGuid }}</title>
    <style>
        /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */

        /*All the styling goes here*/

        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

        body {
            background-color: #f6f6f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

        .body {
            background-color: #f6f6f6;
            width: 100%;
        }

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block;
            margin: 0 auto !important;
            /* makes it centered */
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
        <h1>B-locker</h1>
        <p style="font-size: 25px; margin:0;">Lockdown</p>
        <p style="color: red; "> {{$lockerGuid}}</p>
        <p style="padding-bottom: 10px;">Due to repeated failed unlock attempts, we have put your locker in lockdown. Click the link to uplift the lockdown.</p>
        <a href="{{ $url }}">Lift lockdown</a>
    </div>
</body>
