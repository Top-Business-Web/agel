

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
input:focus{
    border: none;
    outline: none;
}
    </style>
</head>
<body class="d-flex align-items-center" style="background-image: linear-gradient(rgba(33,33,34,0.4), rgba(33,33,34,0.8)),url('{{ asset('bg.jpg') }}'); background-size: cover;
    background-repeat: no-repeat; height: 100vh">
<div class="container-fluid">

       <div class="row">
        <div class="col-lg-3 col-12"></div>
        <div class="col-lg-6 col-12">
        <div  class="signup-container" style="background-color: white; width: 100%;" >
        <div class="d-flex justify-content-center w-100">
    <img src="{{ asset('logo 1.png') }}" style="height: 90px;">
    </div>      
      <form class="signup-form" action="{{route('admin.login')}}" method="POST" id="LoginForm">
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
            <label class="inp d-flex">
            <span class="input-group-text" id="validationTooltipUsernamePrepend" style="border-radius: 0 2rem 2rem 0; background-color: #e8f0ff; border: none; font-size: 20px;">+966</span>
                <input type="email" name="input" class="input-text" placeholder="&nbsp;" id="inputField" style="background-color: rgb(232, 240, 254); border-radius: 2rem 0 0 2rem;">
                <span class="label" id="placeHolder">البريد الالكتروني</span>
                <span class="input-icon">
                    <i class="fa-solid fa-envelope"></i>
                </span>
            </label>
            <label class="inp">
                <input type="password" name="password" class="input-text" placeholder="&nbsp;" id="password" style="background-color: rgb(232, 240, 254);">
                <span class="label"> كلمة المرور</span>
                <span class="input-icon input-icon-password" data-password><i class="fa-solid fa-eye"></i></span>
            </label>
            <button class="btn btn-primary fs-3 p-3" id="loginButton" style="background-color: #3cb9c7; border-color: #3cb9c7;"> تسجيل</button>
                <div class="d-flex justify-content-end">
                    <a style="text-decoration: none; color: gray; font-size: 15px;" href="{{route('admin.resetPasswordForm')}}">نسيت كلمة المرور !</a>
                </div>
        </form>
        </div>
        </div>
        <div class="col-lg-3 col-12"></div>
       </div>


</div>
</body>
@include('admin.auth.js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const emailRadio = document.getElementById("verificationTypeEmail");
        const phoneRadio = document.getElementById("verificationTypePhone");
        const inputField = document.getElementById("inputField");
        const placeHolder = document.getElementById("placeHolder");
        const inputIcon = document.querySelector('.input-icon i');

        // جعل البريد الإلكتروني الخيار الافتراضي عند تحميل الصفحة
        emailRadio.checked = true;
        inputField.type = 'email';
        placeHolder.textContent = 'البريد الالكتروني';
        inputIcon.className = 'fa-solid fa-envelope';
        inputField.value = "";
        password.value = "";

        // تحديث الإدخال عند تغيير الاختيار
        document.querySelectorAll('input[name="verificationType"]').forEach((elem) => {
            elem.addEventListener("change", function (event) {
                if (event.target.value === 'email') {
                    inputField.type = 'email';
                    placeHolder.textContent = 'البريد الالكتروني';
                    inputIcon.className = 'fa-solid fa-envelope';
                } else if (event.target.value === 'phone') {
                    inputField.type = 'number';
                    placeHolder.textContent = 'رقم الجوال';
                    inputIcon.className = 'fa-solid fa-phone';
                }
            });
        });
    });
</script>



</html>
