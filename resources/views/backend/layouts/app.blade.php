<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Enquiry- @yield('title')</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="{{ asset('admin_assets/css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body class="sb-nav-fixed">
        @include('backend.partials.header')

        <div id="layoutSidenav">


         @yield('navbar')


            <div id="layoutSidenav_content">

                @yield('content')


                @include('backend.partials.footer')

            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('admin_assets/js/scripts.js')  }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('admin_assets/assets/demo/chart-area-demo.js')}}"></script>
        <script src="{{asset('admin_assets/assets/demo/chart-bar-demo.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('admin_assets/js/datatables-simple-demo.js')}}"></script>
        <script src="{{asset('founder_assets/js/script.js')}}"></script>
        <script>
            function showImage(imageUrl) {
                document.getElementById('largeImage').src = imageUrl;
            }
        </script>

{{--        <script>--}}
{{--            document.addEventListener('DOMContentLoaded', function() {--}}
{{--                const form = document.getElementById('companyForm');--}}
{{--                const inputs = form.querySelectorAll('input, textarea');--}}

{{--                // Handle Enter key press--}}
{{--                form.addEventListener('keydown', function(e) {--}}
{{--                    if (e.key === 'Enter') {--}}
{{--                        e.preventDefault();--}}

{{--                        const activeElement = document.activeElement;--}}

{{--                        if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA')) {--}}
{{--                            // Check if this is the last field that should submit on Enter--}}
{{--                            if (activeElement.hasAttribute('data-submit-on-enter')) {--}}
{{--                                form.submit();--}}
{{--                            }--}}
{{--                            // Otherwise move to next field--}}
{{--                            else {--}}
{{--                                const nextFieldName = activeElement.getAttribute('data-next');--}}
{{--                                if (nextFieldName) {--}}
{{--                                    const nextField = form.querySelector(`[name="${nextFieldName}"]`);--}}
{{--                                    if (nextField) {--}}
{{--                                        nextField.focus();--}}
{{--                                    }--}}
{{--                                }--}}
{{--                            }--}}
{{--                        }--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        </script>--}}

    </body>
</html>
