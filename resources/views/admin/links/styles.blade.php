<!-- Fonts and icons -->
<script src="{{ asset('yuukke/assets/js/plugin/webfont/webfont.min.js') }}"></script>
<script>
    WebFont.load({
        google: {
            families: ["Public Sans:300,400,500,600,700"]
        },
        custom: {
            families: [
                "Font Awesome 5 Solid",
                "Font Awesome 5 Regular",
                "Font Awesome 5 Brands",
                "simple-line-icons",
            ],
            urls: ["{{ asset('yuukke/assets/css/fonts.min.css') }}"],
        },
        active: function () {
            sessionStorage.fonts = true;
        },
    });
</script>

<!-- CSS Files -->
<link rel="stylesheet" href="{{ asset('yuukke/assets/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('yuukke/assets/css/plugins.min.css') }}" />
<link rel="stylesheet" href="{{ asset('yuukke/assets/css/main.min.css') }}" />

<!-- CSS Just for demo purpose, don't include it in your project -->
{{-- <link rel="stylesheet" href="{{ asset('yuukke/assets/css/demo.css') }}" /> --}}
{{-- select css --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{--  font-awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />