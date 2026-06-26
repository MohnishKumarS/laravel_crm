<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            /* background: linear-gradient(rgba(0, 9, 64, 0.7),
                    rgba(160, 0, 48, 0.7)); */
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .error-card {
            max-width: 600px;
            width: 100%;
            text-align: center;
            background: #fff;
            border-radius: 20px;
            padding: 50px;
            /* box-shadow: 0 15px 40px rgba(0,0,0,.15); */
        }

        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #764ba2;
            line-height: 1;
        }

        .error-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .error-text {
            color: #6c757d;
            margin-bottom: 30px;
        }



        .emoji {
            font-size: 70px;
            margin-bottom: 20px;
        }

        .btn-auth {
            background: linear-gradient(90deg, #000940 0%, #A00030 100%);
            color: #fff;
            border: none;
            height: 48px;
            padding: 12px 30px;
            border-radius: 50px;
            /* font-size: 15px; */
        }

        .btn-auth:hover {
            background: linear-gradient(90deg, #020d5f 0%, #c0003a 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(160, 0, 48, 0.35);
        }
    </style>
</head>

<body>

    <div class="error-card">
        <div>
            <img src="{{ asset('uploads/pics/404-error.svg') }}" alt="404-error">
        </div>

        {{-- <div class="error-code">404</div> --}}

        <h1 class="error-title">Oops! Page Not Found</h1>

        <p class="error-text">
            The page you're looking for doesn't exist or has been moved.
        </p>

        <a href="{{ url('/') }}" class="btn btn-auth">
            Go to Homepage
        </a>
    </div>

</body>

</html>
