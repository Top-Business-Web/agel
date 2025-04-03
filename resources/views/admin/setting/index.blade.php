@extends('admin.layouts.master')

<!-- @section('title')
    {{ config('app.name') }} | {{ $bladeName }}
@endsection -->



@section('content')
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')

        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <div class="col-md-12 border-right">
                    <div class="p-3 py-5 mt-3">
                        <div class="row">
                            <!-- Logo -->
                            <!-- <div class="col-md-4 mt-3">
                                <label class="labels">{{ __('Logo') }}</label>
                                <input type="file" class="form-control dropify" name="logo">
                            </div> -->

                            <!-- Fav Icon -->
                            <!-- <div class="col-md-4 mt-3">
                                <label class="labels">{{ __('Fav Icon') }}</label>
                                <input type="file" class="form-control dropify" name="fav_icon">
                            </div> -->

                            <!-- Loader -->
                            <!-- <div class="col-md-4 mt-3">
                                <label class="labels">{{ __('Loader') }}</label>
                                <input type="file" class="form-control dropify" name="loader">
                            </div> -->
                        </div> 

                        <!-- App Version -->
                        <div class="col-md-12 mt-3">
                            <label class="labels">{{ __('App Version') }}</label>
                            <input type="text" class="form-control" name="app_version"
                                   value="{{ optional($settings->where('key', 'app_version')->first())->value ?? '' }}">
                        </div>

                        <!-- About (EN) -->
                        <div class="col-md-12 mt-3">
                            <label class="labels">{{ __('About') }}</label>
                            <textarea class="form-control" name="about">
                                {{ optional($settings->where('key', 'about')->first())->value ?? 'لا يوجد بيانات' }}
                            </textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-5 text-right mr-5">
                            <button type="submit" class="btn btn-primary" id="updateButton">{{ __('Update') }}</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('admin.layouts.myAjaxHelper')

@section('ajaxCalls')
    <script>
        editScript();
    </script>
@endsection
@endsection
