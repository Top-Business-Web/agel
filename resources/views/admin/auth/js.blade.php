<script src="{{asset('assets/admin')}}/assets/js/jquery-3.4.1.min.js"></script>
@toastr_js
@toastr_render
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
<script>
    function expand(lbl) {
        var elemId = lbl.getAttribute("for");
        document.getElementById(elemId).style.height = "45px";
        document.getElementById(elemId).classList.add("my-style");
        lbl.style.transform = "translateY(-45px)";
    }

    $("form#LoginForm").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $('#LoginForm').attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#loginButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                    ' ></span> <span style="margin-left: 4px;">أنتظر قليلا</span>').attr('disabled', true);

            },
            complete: function () {


            },
            success: function (data) {
                if (data.status === 204) {
                    swal.fire({
                        title: "اهلا بك",
                        icon: "success"
                    }).then(function () {
                        window.location.href = '{{route('adminHome')}}';
                    });
                }
                if (data.status === 205) {
                    toastr.error('من فضلك تأكد من رقم الجوال و أعد المحاوله');
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);

                }
                if (data.status === 206) {
                    toastr.error('هذا البريد الإلكتروني غير مسجل بالنظام');
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);

                }
                if (data === 200 ) {
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    window.location.href = '{{route('adminHome')}}';
                } else if (data === 500) {
                    toastr.error('لقد قمت بإدخال الكود الذي تم إرساله بشكل خاطئ');
                    window.location.href = '/';
                }


                if (data.status === 200) {
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    window.location.href = '{{ route('otp.verify', ['email' => '__EMAIL__','type'=>'login']) }}'.replace('__EMAIL__', encodeURIComponent(data.email));
                }
                // else {
                //     // toastr.error('خطأ في  بيانات الدخول');
                //     $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                // }

            },


            {{--success: function (data) {--}}
                {{--    if (data == 200) {--}}
                {{--        swal.fire({--}}
                {{--            title: "اهلا بك",--}}
                {{--            icon: "success"--}}
                {{--        }).then(function () {--}}
                {{--            window.location.href = '{{route('vendorHome')}}';--}}
                {{--        });--}}
                {{--        --}}{{--window.setTimeout(function () {--}}
                {{--        --}}{{--    window.location.href = '{{route('adminHome')}}';--}}
                {{--        --}}{{--}, 1000);--}}
                {{--    } else {--}}
                {{--        toastr.error('خطأ في  بيانات الدخول');--}}
                {{--        $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);--}}
                {{--    }--}}


                {{--    if (data.status === 200) {--}}

                {{--        swal.fire({--}}
                {{--            title: " من فضلك قم بإدخال الكود الذي تم إرساله على البريد الإلكتروني لتأكيد تسجيل الدخول",--}}
                {{--            icon: "success"--}}
                {{--        }).then(function () {--}}
                {{--            window.location.href = '{{ route('otp.verify', ['email' => '__EMAIL__','type'=>'login']) }}'.replace('__EMAIL__', encodeURIComponent(data.email));                    });--}}

                {{--    } else {--}}
                {{--        toastr.error('خطأ في  بيانات الدخول');--}}
                {{--        $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);--}}
                {{--    }--}}

                {{--},--}}
                {{--error: function (data) {--}}
                {{--    if (data.status === 500) {--}}
                {{--        $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);--}}
                {{--        toastr.error('هناك خطأ ما');--}}
                {{--    } else if (data.status === 422) {--}}
                {{--        $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);--}}
                {{--        var errors = $.parseJSON(data.responseText);--}}
                {{--        $.each(errors, function (key, value) {--}}
                {{--            if ($.isPlainObject(value)) {--}}
                {{--                $.each(value, function (key, value) {--}}
                {{--                    toastr.error(value);--}}
                {{--                });--}}

                {{--            } else {--}}
                {{--            }--}}
                {{--        });--}}
                {{--    } else {--}}
                {{--        $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);--}}

                {{--        toastr.error('خطأ في  بيانات الدخول');--}}
                {{--    }--}}
                {{--},//end error method--}}

            cache: false,
            contentType: false,
            processData: false
        });
    });

    $("form#RegisterForm").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $('#RegisterForm').attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#registerButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                    ' ></span> <span style="margin-left: 4px;">أنتظر قليلا</span>').attr('disabled', true);

            },
            complete: function () {


            },


            success: function (data) {

                if (data.status === 200) {

                    swal.fire({
                        title: "من فضلك قم بتفعيل حسابك",
                        icon: "success"
                    }).then(function () {
                        window.location.href = '{{ route('otp.verify', ['email' => '__EMAIL__','type'=>'register']) }}'.replace('__EMAIL__', encodeURIComponent(data.email));
                    });

                } else {
                    toastr.error('خطأ في  بيانات الدخول');
                    $('#registerButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                }

            },
            error: function (data) {
                if (data.status === 500) {
                    $('#registerButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                    toastr.error('هناك خطأ ما');
                } else if (data.status === 422) {
                    $('#registerButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function (key, value) {
                        if ($.isPlainObject(value)) {
                            $.each(value, function (key, value) {
                                toastr.error(value);
                            });

                        } else {
                        }
                    });
                } else {
                    $('#registerButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);

                    toastr.error('خطأ في  بيانات الدخول');
                }
            },//end error method

            cache: false,
            contentType: false,
            processData: false
        });
    });


</script>
