<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            /* background: #f5f7fb; */
            color: #495057;
            font-size: 15px !important;
        }

        a {
            text-decoration: none;
        }

        .image-section {
            background: linear-gradient(rgba(0, 9, 64, 0.7),
                    rgba(160, 0, 48, 0.7)),
                url('https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=1600');
            background-size: cover;
            background-position: center;
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
        }

        .auth-card {
            width: 450px;
            padding: 40px;
        }

        .logo {
            max-width: 220px;
        }

        .form-control {
            border: none;
            border-bottom: 1px solid #ddd;
            border-radius: 0;
            box-shadow: none !important;
        }

        .btn-auth {
            background: linear-gradient(90deg, #000940 0%, #A00030 100%);
            color: #fff;
            border: none;
            height: 48px;
            /* font-size: 15px; */
        }

        .btn-auth:hover {
            color: #fff;
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
        }

        .btn-auth:hover {
            background: linear-gradient(90deg, #020d5f 0%, #c0003a 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(160, 0, 48, 0.35);
        }

        ::placeholder {
            color: #495057;
            letter-spacing: 1px;
            font-size: 15px;
        }
    </style>
</head>

<body>

    <div class="container p-0">
        <div class="row g-0 min-vh-100">

            <!-- Left Image -->
            {{-- <div class="col-lg-6 d-none d-lg-flex image-section">
            <div class="overlay-content">
                <h1>Forgot Password?</h1>

                <p class="mt-3">
                    Don't worry. Enter your registered email address
                    and we'll send you a verification OTP.
                </p>
            </div>
          
        </div> --}}
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <img src="{{ asset('uploads/pics/verify-otp.svg') }}" alt="forgot-password">
            </div>

            <!-- Right Form -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white">

                <div class="auth-card">

                    <div class="text-center mb-5">
                        <img src="{{ asset('uploads/logo/logo_dark.png') }}" class="logo" alt="Logo">
                    </div>

                    @if (session('message'))
                        <div class="alert alert-{{ session('status', 'success') }}">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- <h4 class="mb-4 text-center">
                        Reset Password
                    </h4> --}}

                    <form method="POST" action="{{ route('verify.otp.submit') }}">
                        @csrf

                        <div class="mb-4">
                            <input type="text" name="otp" maxlength="6" class="form-control text-center"
                                placeholder="Enter 6 Digit OTP" onkeyup="return this.value = this.value.replace(/[^0-9]/g,'')">
                        </div>

                        <button type="submit" class="btn btn-auth w-100">
                            Verify OTP
                        </button>
                    </form>

                    {{-- <div class="auth-footer">
                        <a href="{{ route('login') }}">
                            Back to Login
                        </a>
                    </div> --}}

                </div>

            </div>

        </div>
    </div>

</body>

</html>
