<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Yuukke</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 15px;
            color: #495057;
            background: #f5f7fb;
        }

        .image-section {
            background: linear-gradient(rgba(0, 9, 64, 0.7),
                    rgba(160, 0, 48, 0.7)),
                url('https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=1600');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .overlay-content {
            color: #fff;
            text-align: center;
            max-width: 500px;
            margin: auto;
            padding: 40px;
        }

        .overlay-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .overlay-content p {
            font-size: 18px;
            opacity: 0.9;
        }

        .logo {
            width: 100%;
            max-width: 250px;
            height: auto;
        }


        .auth-card {
            width: 450px;
            padding: 40px;
        }

        .auth-title {
            text-align: center;
            font-weight: 700;
            margin-bottom: 35px;
            color: #2c2c54;
        }

        .form-control {
            border: none;
            border-bottom: 1px solid #ddd;
            border-radius: 0;
            padding-left: 0;
            box-shadow: none !important;
        }

        .btn-auth {
            background: linear-gradient(90deg, #000940 0%, #A00030 100%);
            color: #fff;
            height: 48px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            background: linear-gradient(90deg, #020d5f 0%, #c0003a 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(160, 0, 48, 0.35);
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
        }

        a {
            text-decoration: none;
        }

        ::placeholder {
            color: #495057;
            letter-spacing: 1px;
            font-size: 15px;
        }

        /* Mobile */
        @media (max-width: 991px) {
            .auth-card {
                width: 100%;
                max-width: 420px;
                padding: 30px;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            <!-- Left Image Section -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <img src="{{ asset('uploads/pics/p3.svg') }}" alt="Yuukke-login" class="img-fluid">
            </div>
            {{-- <div class="col-lg-6 d-none d-lg-flex image-section">
                <div class="overlay-content">

                    <h1>Create Your Account</h1>
                    <p>
                        Join the CRM platform and manage customers,
                        sales, leads and reports from a single dashboard.
                    </p>
                </div>
            </div> --}}

            <!-- Right Login Section -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white">
                <div class="auth-card">

                    {{-- <h2 class="auth-title">Sign In</h2> --}}
                    <div class="text-center">
                        <img src="{{ asset('uploads/logo/logo_dark.png') }}" class="logo mb-5" alt="Logo">
                    </div>

                    <form action="{{route('register.submit')}}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ $errors->first() }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="mb-4">
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                placeholder="Username">
                        </div>

                        <div class="mb-4">
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                placeholder="Email Address">
                        </div>

                        <div class="mb-4">
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>

                        <div class="mb-4">
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Confirm Password">
                        </div>

                        <input type="hidden" name="role" value="marketer">

                        <button type="submit" class="btn btn-auth w-100">
                            Create Account
                        </button>
                    </form>

                    <div class="auth-footer">
                        Already have an account?
                        <a href="{{ route('login') }}">Sign In</a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
</script>

</html>
