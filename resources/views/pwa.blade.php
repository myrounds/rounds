<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rounds</title>
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" sizes="180x180" href="img/icon.png">
    <link rel="icon" type="image/svg" sizes="192x192"  href="img/icon.png">
    <link rel="icon" type="image/svg" sizes="250x250" href="img/icon.png">
    <meta name="msapplication-TileImage" content="img/icon.png">
    <link href="https://fonts.googleapis.com/css?family=Heebo:400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.css' rel='stylesheet' />

    <link href="//cdn.muicss.com/mui-0.9.41/css/mui.min.css" rel="stylesheet" type="text/css" />
    <script src="//cdn.muicss.com/mui-0.9.41/js/mui.min.js"></script>
    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <script>
        jQuery(($) => {
            const $bodyEl = $('body'),
                $sidedrawerEl = $('#sidedrawer');


            const showSidedrawer = () => {
                // show overlay
                const options = {
                    onclose: function() {
                        $sidedrawerEl
                            .removeClass('active')
                            .appendTo(document.body);
                    }
                };

                const $overlayEl = $(mui.overlay('on', options));

                // show element
                $sidedrawerEl.appendTo($overlayEl);
                setTimeout(() => {
                    $sidedrawerEl.addClass('active');
                }, 20);
            };


            const hideSidedrawer = () => {
                $bodyEl.toggleClass('hide-sidedrawer');
            };


            $('.js-show-sidedrawer').on('click', showSidedrawer);
            $('.js-hide-sidedrawer').on('click', hideSidedrawer);

            const $titleEls = $('a', $sidedrawerEl);

            $titleEls
                .next()
                .hide();

            $titleEls.on('click', () => {
                $(this).next().slideToggle(200);
            });
        });
    </script>

</head>
<body>
<div id="app">
    <app></app>
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>