<!DOCTYPE html>
<html>

<head>
    {{-- META TAGS --}}
    @include('admin.components.meta')

    {{-- css stylesheet  --}}
    @include('admin.links.styles')
    @stack('styles')

</head>

<body>
    <div class="wrapper">

        @include('admin.components.sidebar')
        <div class="main-panel">

            @include('admin.components.header')

            <div class="container">
                <div class="page-inner">

                    {{-- @include('admin.components.alerts') --}}

                    @yield('content')

                </div>
            </div>

            {{-- @include('admin.components.footer') --}}
        </div>

    </div>




    {{-- js script  --}}
    @include('admin.links.scripts')
    @stack('scripts')

</body>

</html>
