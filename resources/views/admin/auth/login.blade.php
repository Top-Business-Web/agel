<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.auth.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css">
    <link href="{{asset('assets/admin')}}/assets/plugins/bootstrap5/css/bootstrap.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
    <!-- <style>
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
    </style> -->

    <style>
        .area-login,
        .area-account {
            width: 500px;
            min-height: 600px;
            background-color: white;
            padding: 20px 40px;
            box-shadow: 0 5px 25px rgb(1 1 1 / 15%);
        }

        .form-register label,
        .form-login label {
            color: gray;
            font-size: 18px;
        }

        .area-forget {
            width: 500px;
            height: 400px;
            background-color: white;
            padding: 40px;
            box-shadow: 0 5px 25px rgb(1 1 1 / 15%);
        }

        .login .container,
        .register .container,
        .forget-pass .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .area-login input {
            /* padding: 15px; */
            border: 1px solid gainsboro;
        }

        .area-account input,
        .area-forget input {
            padding: 10px;
            border: 1px solid gainsboro;
        }

        .area-login input:focus,
        .area-account input:focus,
        .area-forget input:focus {
            border: 1px solid var(--main-color);
            outline: none;
        }

        .area-login .forget {
            color: gray;
            font-size: 20px;
        }

        .form-check {
            padding-left: 10px;
        }

        .btn-login,
        .btn-register {
            background-color: var(--main-color);
            padding: 15px;
            border: none;
            color: white;
            border-radius: 5px;
            font-size: 20px;
        }

        .btn-login:hover,
        .btn-register:hover {
            background-color: var(--second-color);
        }

        .area-login .test,
        .area-account .test {
            display: inline-block;
            width: 45.5%;
            border: 1px solid gainsboro;
        }

        .area-login a,
        .area-account a {
            color: var(--second-color);
        }

        .area-login a:hover,
        .area-account a:hover {
            color: var(--main-color);
        }

        .login-forget a {
            transition: all 0.5s ease;
        }

        .login-forget a:hover {
            color: var(--main-color);
        }

        .form-check .form-check-input {
            float: right;
            margin-right: -1.5em;
        }
        .content-list > div:not(:first-child){
    display: none;
}
.tabs-list .show{
    color: #0d6efd;
    font-weight: bold;
}
.tabs-list div{
    cursor: pointer;
}
    </style>

</head>

<body class="">

    <div class="login bg-white pt-5 pb-5">
        <div class="container">
            <div class="area-login">
                <div class="text-center mt-3 mb-4">
                    <h1 class="color-ornge mb-2 fw-bold">Welcome to Lotel</h1>
                    <p class="fs-5">Sign in to manage your property.</p>
                </div>
                <form class="row g-3">
                    <div class="col-12 tabs">
                    <div class="tabs-list d-flex justify-content-evenly">
                        <div class="show" data-content=".content-one">
                            التسجيل بالايميل
                        </div>
                        <div data-content=".content-two">
                           التسجيل برقم التليفون
                        </div>
                    </div>
                   <div class="content-list">
                   <div class="content-one">
                   <div>
                        <label for="validationDefault01" class="form-label">الايميل</label>
                        <input type="text" class="form-control" style="width: 100%;" id="validationDefault01" value="Mark" required>
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control" id="validationDefault02" value="Otto" required>
                    </div>
                   </div>
                   <div class="content-two">
                   <div>
                        <label for="validationDefault01" class="form-label">رقم التليفون</label>
                        <input type="number" class="form-control" style="width: 100%;" id="validationDefault01" value="Mark" required>
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control" id="validationDefault02" value="Otto" required>
                    </div>
                   </div>
                   </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    </div>
                </form>
                <div class="d-flex justify-content-end mt-4 mb-2">
                    <a class="text-decoration-none forget" href="forgetpassword.html">Forget Password?</a>
                </div>

                <div class="d-flex justify-content-center mt-5">
                    <p class="text-black-50 me-1 fs-5">Don't Have an Account?</p>
                    <a href="register.html" class="text-decoration-none fs-5">Create property account</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        {{-- <div class="language-switcher">--}}
        {{-- <a href="{{ LaravelLocalization::getLocalizedURL(lang() == 'en' ? 'ar' : 'en', null, [], true) }}"--}}
        {{-- class="btn btn-language" style="background-color: #0285CE;">{{ lang() == 'en' ? trns('Arabic') : trns('English') }}</a>--}}
        {{-- </div>--}}
        {{-- <div class="dark-switcher">--}}
        {{-- <a id="toggleDarkMode" class="btn btn-language">{{ trns('dark_mode') }}</a>--}}
        {{-- </div>--}}

        <main class="signup-container" style="margin-top: 40px">
            <!-- <h1 class="heading-primary">مرحبا بعودتك<span class="span-blue">.</span></h1> -->

            {{-- <form class="signup-form" action="{{route('admin.login')}}" method="POST" id="LoginForm">--}}
            {{-- @csrf--}}
            {{-- @method('POST')--}}
            {{-- <label class="inp">--}}
            {{-- <input type="text" name="input" class="input-text" placeholder="&nbsp;">--}}
            {{-- <span class="label">إدخل البريد الإلكتروني أو رقم الهاتف</span>--}}
            {{-- <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>--}}
            {{-- </label>--}}
            {{-- <label class="inp">--}}
            {{-- <input type="password" name="password" class="input-text" placeholder="&nbsp;" id="password">--}}
            {{-- <span class="label"> كلمة المرور</span>--}}
            {{-- <span class="input-icon input-icon-password" data-password><i class="fa-solid fa-eye"></i></span>--}}
            {{-- </label>--}}
            {{-- <button class="btn btn-login" id="loginButton"> تسجيل الدخول</button>--}}
            {{-- </form>--}}



            <form class="signup-form" action="{{route('admin.login')}}" method="post" id="LoginForm">
                @csrf
                <label class="inp">
                    <input type="email" name="input" class="input-text" placeholder="&nbsp;" id="inputField">
                    <span class="label" id="placeHolder">البريد الالكتروني</span> <!-- Default placeholder -->
                    <span class="input-icon">
                        <i class="fa-solid fa-envelope"></i> <!-- Default icon -->
                    </span>
                </label>
                <label class="inp">
                    <input type="password" name="password" class="input-text" placeholder="&nbsp;" id="password">
                    <span class="label">كلمة المرور</span>
                    <span class="input-icon input-icon-password" data-password>
                        <i class="fa-solid fa-eye"></i>
                    </span>
                </label>
                {{-- <input class="inp">--}}
                {{-- <input type="text" name="input" class="input-text" placeholder="&nbsp;">--}}
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted">تسجيل الدخول عبر </span>
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="email" name="verificationType" id="verificationTypeEmail" checked>
                            <label class="form-check-label" for="verificationTypeEmail">Email</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="phone" name="verificationType" id="verificationTypePhone">
                            <label class="form-check-label" for="verificationTypePhone">Phone</label>
                        </div>
                    </div>
                    <p class="text-mute">نسيت كلمة المرور؟
                        <a href="{{url('/reset-password')}}">إعادة تعيين</a>
                    </p>
                </div>
                <button class="btn btn-login" id="loginButton">تسجيل </button>

            </form>
        </main>
        <!-- <div class="welcome-container" style="background-color: white !important;">

        <img style="border-radius: 10%" src="{{asset('logo.webp')}}">
    </div> -->
    </div>
</body>

@include('admin.auth.js')
<script>
    document.getElementById('toggleDarkMode').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
    });
</script>

<script>
    document.querySelectorAll('input[name="verificationType"]').forEach((elem) => {
        elem.addEventListener("change", function(event) {
            const inputField = document.getElementById('inputField');
            const placeHolder = document.getElementById('placeHolder');
            const inputIcon = document.querySelector('.input-icon i'); // Get the icon element

            if (event.target.value === 'email') {
                inputField.type = 'email';
                placeHolder.textContent = 'البريد الالكتروني'; // Update placeholder text
                inputIcon.className = 'fa-solid fa-envelope'; // Change icon to email
            } else if (event.target.value === 'phone') {
                inputField.type = 'number';
                placeHolder.textContent = 'رقم الجوال'; // Update placeholder text
                inputIcon.className = 'fa-solid fa-phone'; // Change icon to phone
            }
        });
    });
</script>

<script>
    $('.tabs-list div').on('click', function (){

$(this).addClass('show').siblings().removeClass('show');

$('.content-list > div').hide();

$($(this).data('content')).fadeIn();
});
</script>

</html>