<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>

        <!-- CDP 360 -->
        <script>
            var _paq = window._paq = window._paq || [];
            _paq.push(['enableLinkTracking']);
            (function() {
                var u="//360.tbcconnect.ge/";
                var i=11;
                _paq.push(['setTrackerUrl', u+'service']);
                _paq.push(['setSiteId', i]);
                var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                g.type='text/javascript'; g.async=true; g.src=u+'hoard.js'; s.parentNode.insertBefore(g,s);
                var pd=document, pg=pd.createElement('script'), ps=pd.getElementsByTagName('script')[0];
                pg.type='text/javascript'; pg.async=true; pg.src=u+'collector.js?id=' + i; s.parentNode.insertBefore(pg,ps);
            })();
        </script>
        <!-- End CDP 360 -->

        @auth
            @php $user = \Auth::user(); @endphp
            @if($user->tbcconnect_consent)
                <script>
                    {{-- Check If Prev Page Was Login --}}
                    @if(str_replace(url('/'), '', url()->previous()) === '/login')
                        _paq.push(['login', '{{ $user->email }}', {
                            name: '{{ $user->name  }}'
                        }]);
                    @else
                        _paq.push(['setCustomer', '{{ $user->email }}', {
                            name: '{{ $user->name  }}'
                        }]);
                    @endif
                </script>
            @endif
        @endif

    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
    </body>
</html>
