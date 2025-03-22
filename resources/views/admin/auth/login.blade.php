

    <!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.auth.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css">
    <link href="{{asset('assets/admin')}}/assets/plugins/bootstrap5/css/bootstrap.min.css" rel="stylesheet"/>

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
        .form-check .form-check-input {
    float: right;
    margin-right: -1.5em;
}
.form-check-input:checked {
    background-color: #3cb9c7;
    border-color: #3cb9c7;
}
    </style>
</head>
<body class="d-flex align-items-center" style="background-image: linear-gradient(rgba(33,33,34,0.4), rgba(33,33,34,0.8)),url('{{ asset('bg.jpg') }}'); background-size: cover;
    background-repeat: no-repeat; height: 100vh">
<div class="container-fluid">

       <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
        <div  class="signup-container" style="background-color: white; width: 100%;" >
        <div class="d-flex justify-content-center w-100">
    <img src="{{ asset('logo 1.png') }}" style="height: 90px;">
    </div>        <form class="signup-form" action="{{route('admin.login')}}" method="POST" id="LoginForm">
            @csrf
            @method('POST')
            <div class="gap-2">
                <div class="d-flex justify-content-evenly gap-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input mt-2" type="radio" value="email" name="verificationType"
                               id="verificationTypeEmail" checked>
                        <label class="form-check-label fs-4" for="verificationTypeEmail">التسجيل بالبريد الالكترونى</label>
                    </div>
                    <div class="form-check">

                        <input class="form-check-input mt-2" type="radio" value="phone" name="verificationType"
                               id="verificationTypePhone">

                        <label class="form-check-label fs-4" for="verificationTypePhone">التسجيل برقم الجوال</label>
                    </div>
                </div>
            </div>
            <label class="inp">
                <input type="email" name="input" class="input-text" placeholder="&nbsp;" id="inputField" style="background-color: rgb(232, 240, 254);">
                <span class="label" id="placeHolder">البريد الالكتروني</span>
                <span class="input-icon">
                    <i class="fa-solid fa-envelope"></i> <!-- Default icon -->
                </span>
            </label>
            <label class="inp">
                <input type="password" name="password" class="input-text" placeholder="&nbsp;" id="password" style="background-color: rgb(232, 240, 254);">
                <span class="label"> كلمة المرور</span>
                <span class="input-icon input-icon-password" data-password><i class="fa-solid fa-eye"></i></span>
            </label>
            <button class="btn btn-primary fs-3 p-3" id="loginButton" style="background-color: #3cb9c7; border-color: #3cb9c7;"> تسجيل</button>
        </form>
        </div>
        </div>
        <div class="col-lg-3"></div>
       </div>

        {{--        <form class="signup-form" action="{{route('admin.login')}}" method="post" id="LoginForm">--}}
        {{--            @csrf--}}
        {{--            <label class="inp">--}}
        {{--                <input type="email" name="input" class="input-text" placeholder="&nbsp;" id="inputField">--}}
        {{--                <label class="form-check-label" for="verificationTypePhone">Phone</label>--}}
        {{--                 <div>--}}
        {{--                    <p class="text-mute">نسيت كلمة المرور؟--}}
        {{--                        <a href="{{url('/reset-password')}}">إعادة تعيين</a>--}}
        {{--                    </p>--}}
        {{--                </div>--}}
        {{--                <button class="btn btn-login" id="loginButton">تسجيل</button>--}}
        {{--            </label>--}}
        {{--        </form>--}}

</div>
</body>
@include('admin.auth.js')
<script>
    document.getElementById('toggleDarkMode').addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');
    });
</script>
<script>
    document.querySelectorAll('input[name="verificationType"]').forEach((elem) => {
        elem.addEventListener("change", function (event) {
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

{{--<script>--}}
{{--    document.querySelectorAll('input[name="verificationType"]').forEach((elem) => {--}}
{{--        elem.addEventListener("change", function (event) {--}}
{{--            const inputField = document.getElementById('inputField');--}}
{{--            const placeHolder = document.getElementById('placeHolder');--}}
{{--            const inputIcon = document.querySelector('.input-icon i'); // Get the icon element--}}

{{--            if (event.target.value === 'email') {--}}
{{--                inputField.type = 'email';--}}
{{--                placeHolder.textContent = 'البريد الالكتروني'; // Update placeholder text--}}
{{--                inputIcon.className = 'fa-solid fa-envelope'; // Change icon to email--}}
{{--            } else if (event.target.value === 'phone') {--}}
{{--                inputField.type = 'number';--}}
{{--                placeHolder.textContent = 'رقم الجوال'; // Update placeholder text--}}
{{--                inputIcon.className = 'fa-solid fa-phone'; // Change icon to phone--}}
{{--            }--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
</html>
