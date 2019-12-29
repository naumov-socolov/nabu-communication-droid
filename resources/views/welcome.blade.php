<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Nabu Communication Droid</title>

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>

        <link rel="stylesheet" href="{{ mix('css/solar_system.css') }}">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Nabu Communication Droid
                    <img src="https://res.cloudinary.com/drmyhljip/image/upload/v1577633403/nabu_communication_droid/droid_povait.svg" width="62px">
                </div>

                <div>
                    Send message to distant Solar System:
                    <div id="app">
                        <solar-systems></solar-systems>
                    </div>
                </div>
                <div class="logs links">
                    See detailed: <a href="/logs">Message Logs</a>
                </div>
            </div>
        </div>
    </body>
</html>
