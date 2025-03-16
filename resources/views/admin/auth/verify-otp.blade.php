<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('vendor.auth.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
    <style>
        .swal2-popup,
        .swal2-modal {
            font-size: 16px !important;
        }

        body.dark-mode {
            color: #c5c9e6;
            background-color: #212741;
        }
    </style>

    <style>
        body.dark-mode {
            background-color: #212741;
            color: #ffffff;
        }

        .swal2-popup,
        .swal2-modal {
            font-size: 16px !important;
        }

        .dark-mode .swal2-popup,
        .dark-mode .swal2-modal {
            background-color: #212741;
            color: #fff;
        }

        .dark-mode .input-text {
            background-color: #212741;
            color: #fff;
        }

        .dark-mode .btn-login,
        .dark-mode .btn-language {
            /*background-color: #444;*/
            color: #fff;
        }

        .dark-mode .input-icon i {
            color: #bbb;
        }

        .signup-container,
        .welcome-container {
            transition: background-color 0.3s, color 0.3s;
        }
    </style>

</head>

<body class="">
<div class="container">


    <main class="signup-container" style="margin-top: 40px">
        <h1 class="heading-primary">مرحبا <span class="span-blue">.</span></h1>
        @if($type == 'login')
            <p class="text-mute">من فضلك ادخل كود التأكيد</p>
        @elseif($status == 'register')
            <p class="text-mute">من فضلك ادخل كود التفعيل</p>
        @endif

        <form class="signup-form" action="{{route('otp.check')}}" method="post" id="LoginForm">
            @csrf
            <input type="hidden" name="email" value="{{$email}}">
            <label class="inp">
                <input type="text" name="otp" class="input-text" placeholder="&nbsp;">
                <span class="label">الكود</span>
                <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
            </label>
            @if($type == 'login'||$type == 'reset-password')
                <button class="btn btn-login" id="loginButton">تأكيد</button>
            @elseif($status == 'register')
            <button class="btn btn-login" id="loginButton"> تفعيل</button>
            @endif


        </form>
    </main>
    <div class="welcome-container" style="background-color: white !important;"
    >

        <img style="border-radius: 10%" src="{{asset('logo.webp')}}">
    </div>
</div>
</body>
@include('vendor.auth.js')
<script>
    document.getElementById('toggleDarkMode').addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');
    });
</script>
</html>
