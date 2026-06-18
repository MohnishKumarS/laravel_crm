<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:linear-gradient(135deg,#4f46e5,#4338ca);
             color: #495057;
            font-size: 15px;
        }

        .auth-card{
            width:450px;
            background:#fff;
            border-radius:10px;
            padding:40px;
            box-shadow:0 10px 30px rgba(0,0,0,.15);
        }

        .auth-title{
            text-align:center;
            font-weight:700;
            margin-bottom:30px;
            color:#2c2c54;
        }

        .form-control{
            border:none;
            border-bottom:1px solid #ddd;
            border-radius:0;
            box-shadow:none !important;
        }

        .btn-auth{
            background:#4f46e5;
            color:#fff;
            height:48px;
            border:none;
        }

        .btn-auth:hover{
            background:#4338ca;
            color:#fff;
        }

        .auth-footer{
            text-align:center;
            margin-top:20px;
        }
        ::placeholder {
            color: #495057;
            font-size: 15px;
            letter-spacing: 1px
        }
    </style>
</head>
<body>

<div class="auth-card">
    <h2 class="auth-title">Create Account</h2>

    <form>
        <div class="mb-3">
            <input type="text" class="form-control" placeholder="Full Name">
        </div>

        <div class="mb-3">
            <input type="email" class="form-control" placeholder="Email Address">
        </div>

        <div class="mb-3">
            <input type="password" class="form-control" placeholder="Password">
        </div>

        <div class="mb-4">
            <input type="password" class="form-control" placeholder="Confirm Password">
        </div>

        <button type="submit" class="btn btn-auth w-100">
            Register
        </button>
    </form>

    <div class="auth-footer">
        Already have an account?
        <a href="{{ route('login') }}">Sign In</a>
    </div>
</div>

</body>
</html>