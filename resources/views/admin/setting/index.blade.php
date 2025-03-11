{{--@extends('admin/layouts/master')--}}

{{--@section('title')--}}
{{--    {{ config()->get('app.name') ?? '' }} | {{ trns('settings') }}--}}
{{--@endsection--}}
{{--@section('page_name')--}}
{{--    settings--}}
{{--@endsection--}}
{{--@section('content')--}}
{{--    <div class="card">--}}
{{--        <div class="card-body">--}}
{{--            <div class="row">--}}
{{--                <form id="addForm" class="addForm">--}}
{{--                    <div class="row">--}}

{{--                        <div class="col-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="title" class="form-control-label">{{ trns('title') }} {{ trns('ar') }}</label>--}}
{{--                                <input type="text" class="form-control" name="title[ar]" id="title">--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="title" class="form-control-label">{{ trns('title') }} {{ trns('en') }}</label>--}}
{{--                                <input type="text" class="form-control" name="title[en]" id="title">--}}
{{--                            </div>--}}
{{--                        </div>--}}


{{--                            <div class="col-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="logo" class="form-control-label">{{ trns('logo') }}</label>--}}
{{--                                    <input type="file" class="dropify" name="logo" id="logo">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="loader" class="form-control-label">{{ trns('loader') }}</label>--}}
{{--                                    <input type="file" class="dropify" name="loader" id="loader">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-12">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="fav_icon" class="form-control-label">{{ trns('fav icon') }}</label>--}}
{{--                                    <input type="file" class="dropify" name="fav_icon" id="fav_icon">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="facebook" class="form-control-label">{{ trns('facebook') }}</label>--}}
{{--                                    <input type="text" class="form-control" name="facebook" id="facebook">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="youtube" class="form-control-label">{{ trns('youtube') }}</label>--}}
{{--                                    <input type="text" class="form-control" name="youtube" id="youtube">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="instagram" class="form-control-label">{{ trns('instagram') }}</label>--}}
{{--                                    <input type="text" class="form-control" name="instagram" id="instagram">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="watsapp" class="form-control-label">{{ trns('watsapp') }}</label>--}}
{{--                                    <input type="text" class="form-control" name="watsapp" id="watsapp">--}}
{{--                                </div>--}}
{{--                            </div>--}}


{{--                            <div class="col-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="app_version" class="form-control-label">{{ trns('app version') }}</label>--}}
{{--                                    <input type="text" class="form-control" name="app_version" id="app_version">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="app_maintanance" class="form-control-label">{{ trns('app maintanance') }}</label>--}}
{{--                                    <input type="text" class="form-control" name="app_maintanance" id="app_maintanance">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="modal-footer">--}}
{{--                            <button type="button" class="btn btn-secondary"--}}
{{--                                data-bs-dismiss="modal">{أغلاق</button>--}}
{{--                            <button type="submit" class="btn btn-primary" id="addButton">حفظ
</button>--}}
{{--                        </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    @include('admin/layouts/myAjaxHelper')--}}
{{--@endsection--}}
{{--@section('ajaxCalls')--}}
{{--    <script>--}}
{{--        // editScript();--}}
{{--    </script>--}}
{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            $('#addForm').on('submit', function(event) {--}}
{{--                event.preventDefault();--}}
{{--                var formData = new FormData(this);--}}
{{--                formData.append('_token', '{{ csrf_token() }}');--}}
{{--                $.ajax({--}}
{{--                    method: "POST",--}}
{{--                    enctype: 'multipart/form-data',--}}
{{--                    url: "{{ route('setting.store') }}",--}}
{{--                    data: formData,--}}
{{--                    processData: false,--}}
{{--                    contentType: false,--}}
{{--                    cache: false,--}}

{{--                    success: function(data) {--}}
{{--                        if (data.status == 200) {--}}
{{--                            $('#create').modal('hide');--}}
{{--                            toastr.success(data.message);--}}

{{--                            xhr--}}
{{--                            clearModalContents();--}}
{{--                        }--}}
{{--                    },--}}
{{--                    error: function(xhr, status, error) {--}}
{{--                        var errors = xhr.responseJSON.errors;--}}
{{--                        $.each(errors, function(key, value) {--}}
{{--                            toastr.error(value);--}}
{{--                        });--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endsection--}}
