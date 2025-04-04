@extends('admin.layouts.master')

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
                            <div class="col-md-4 mt-3">
                                <label class="labels">{{ __('Logo') }}</label>
                                <input type="file" class="form-control dropify" name="logo"
                                    data-default-file="{{ isset($settings) && $settings->where('key', 'logo')->first() ? asset('storage/settings/' . $settings->where('key', 'logo')->first()->value) : '' }}">
                            </div>

                            <!-- Fav Icon -->
                            <div class="col-md-4 mt-3">
                                <label class="labels">{{ __('Fav Icon') }}</label>
                                <input type="file" class="form-control dropify" name="fav_icon"
                                    data-default-file="{{ isset($settings) && $settings->where('key', 'fav_icon')->first() ? asset('storage/settings/' . $settings->where('key', 'fav_icon')->first()->value) : '' }}">
                            </div>

                            <!-- Loader -->
                            <div class="col-md-4 mt-3">
                                <label class="labels">{{ __('Loader') }}</label>
                                <input type="file" class="form-control dropify" name="loader"
                                    data-default-file="{{ isset($settings) && $settings->where('key', 'loader')->first() ? asset('storage/settings/' . $settings->where('key', 'loader')->first()->value) : '' }}">
                            </div>

                            <!-- App Version -->
                            <div class="col-md-12 mt-3">
                                <label class="labels">{{ __('App Version') }}</label>
                                <input type="text" class="form-control" name="app_version"
                                    value="{{ optional($settings->where('key', 'app_version')->first())->value ?? '' }}">
                            </div>

                            <!-- About -->
                            <div class="col-md-12 mt-3">
                                <label class="labels">{{ __('About') }}</label>
                                <textarea class="form-control" name="about">{{ optional($settings->where('key', 'about')->first())->value ?? 'لا يوجد بيانات' }}</textarea>
                            </div>

                            <!-- Phones Section -->
      <!-- Phones Section -->
<div id="plans_container">
    @foreach($settings->where('key', 'phone') as $phone)
        <div class="row phone-row border p-3 mb-2" id="phoneRow-{{ $loop->index }}">
            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">{{ __('Phone Number') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">+966</span>
                        <input type="number" class="form-control" name="phones[]" maxlength="11" 
                            value="{{ $phone->value ?? '' }}" required>
                    </div>
                </div>
            </div>

            <div class="col-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger remove-phone" data-id="{{ $loop->index }}">
                    <i class="fe fe-trash"></i> {{ __('Delete') }}
                </button>
            </div>
        </div>
    @endforeach
</div>



                        </div>

                        <!-- Phones Section -->
                        <div id="plans_container"></div>

                        <!-- Add Phone Button -->
                        <div class="mt-4 text-left">
                            <button type="button" class="btn btn-primary" id="addPhoneButton">{{ __('Add Phone') }}</button>
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
@endsection

@section('ajaxCalls')

<script>
        editScript();
    </script>


<script>
    $('.dropify').dropify();
    $('select').select2({
        minimumResultsForSearch: Infinity
    });
</script>

<script>
    $(document).ready(function() {
        let phoneCount = 0;

        $('#addPhoneButton').on('click', function() {
            phoneCount++;

            let phoneRow = `
            <div class="row phone-row border p-3 mb-2" id="phoneRow-${phoneCount}">
                <div class="col-6">
                    <div class="form-group">
                        <label for="phone" class="form-control-label">{{ __('Phone Number') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">+966</span>
                            <input type="number" class="form-control" name="phones[]" maxlength="11" required>
                        </div>
                    </div>
                </div>

                <div class="col-2 d-flex align-items-center">
                    <button type="button" class="btn btn-danger remove-phone" data-id="${phoneCount}">
                        <i class="fe fe-trash"></i> {{ __('Delete') }}
                    </button>
                </div>
            </div>`;

            $('#plans_container').append(phoneRow);
        });

        // Remove phone entry
        $(document).on('click', '.remove-phone', function() {
            let phoneId = $(this).data('id');
            $('#phoneRow-' + phoneId).remove();
        });
    });
</script>

@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/css/dropify.min.css">
