<!DOCTYPE html>
<html>
    <head>
        @include('layouts.partial.head')
        @yield('header-style')
    </head>
    <body class="body {{ request()->route()->getName() == 'index' ? 'home-page' : 'inner-page' }}" id="body">
        @include('layouts.partial.nav')
        <div id="loading">
            <i class="icon-spinner10 spinner mx-auto" id="loading-image"></i><br>
        </div>
        @include('layouts.flash-message')
        <div id="google_translate_element"></div>
        @yield('content')
        @include('layouts.partial.footer')
        @yield('footer_script')

        <script>
            function googleTranslateElementInit() {
                new google.translate.TranslateElement(
                    {pageLanguage: 'en'},
                    'google_translate_element'
                );
            }
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

        {{-- {{ TawkTo::widgetCode() }} --}}
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/6172b51986aee40a5737dfbf/1fik16rh4';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
        $(window).load(function(){
            $(".goog-logo-link").empty();
            $('.goog-te-gadget').html($('.goog-te-gadget').children());
        })
    </script>
    <!--End of Tawk.to Script-->
    </body>
</html>
