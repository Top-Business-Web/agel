@extends('vendor/layouts/master')

@section('title')
    Subscription Plans
@endsection

@section('page_name')
    Plans
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        /* Your existing CSS styles */
        p { color: black; }
        .pricing .price-table { display: flex; flex-direction: column; }

        @media screen and (max-width: 991.98px) {
            .pricing .price-table { max-width: 370px; margin: 0 auto; }
        }

        .pricing .price-table .pricing-panel {
            background-color: white !important;
            padding: 44px 50px 42px;
            border-radius: 8px;
            box-shadow: 0 5px 83px 0 rgba(40, 40, 40, 0.11);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        /* Rest of your CSS styles */
    </style>

    <section class="pricing pricing-1" id="pricing-1">
        <div class="container">
            <div class="row">
                @foreach($plans as $plan)
                    <div class="col-12 col-lg-4 mb-4">
                        <div class="price-table">
                            <div class="pricing-panel">
                                <div class="pricing-body">
                                    <div class="pricing-heading">
                                        <h4 class="pricing-title">{{$plan->name}}</h4>
                                    </div>
                                    <div class="pricing-price">
                                        <p>
                                            <span class="currency">${{$plan->price}}</span>
                                            <span class="time"> شهرياً</span>
                                        </p>
                                    </div>
                                    <div class="pricing-list">
                                        <p>{{ $plan->description }}</p>
                                        <ul class="advantages-list list-unstyled">
                                            @foreach($planDetails->where('plan_id',$plan->id)->get() as $planDetail)
                                                    @if($planDetail->is_unlimited == 1)
                                                        <li style="color: #0a0c0d">يمكنك إضافة عدد غير محدود من {{$planDetail->key}}</li>
                                                    @else
                                                        <li style="color: #0a0c0d">يمكنك إضافة عدد {{$planDetail->value}} من {{ $planDetail->key }}</li>
                                                    @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button"
                                        class="mt-4 btn btn-primary subscribe-btn"
                                        data-plan-id="{{ $plan->id }}">
                                    إشتراك
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="subscriptionForm" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="plan_id" id="selectedPlanId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="subscriptionModalLabel">إتمام الاشتراك</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="payment_receipt" class="form-control-label">صورة إيصال الدفع</label>
                                    <input type="file" class="dropify" name="payment_receipt" id="payment_receipt">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">حفظ وتقديم</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('ajaxCalls')
    <script>
        $(document).ready(function() {
            // When subscribe button is clicked
            $('.subscribe-btn').click(function() {
                const planId = $(this).data('plan-id');
                $('#selectedPlanId').val(planId);
                $('#subscriptionForm').attr('action', "{{ route('vendor.plans.store') }}");
                $('#subscriptionModal').modal('show');
            });

            // Form submission
            $('#subscriptionForm').submit(function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = $(this).attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري التحميل...');
                    },
                    success: function(response) {
                        $('#subscriptionModal').modal('hide');
                        toastr.success(response.message);
                        // Optional: redirect after success
                        // window.location.reload();
                    },
                    error: function(xhr) {
                        let errorMessage = 'حدث خطأ، يرجى المحاولة مرة أخرى';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessage += value + '<br>';
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false).html('حفظ وتقديم');
                    }
                });
            });
        });
    </script>
@endsection





{{--<section class="pricing pricing-1" id="pricing-1">--}}
{{--    <div class="container">--}}
{{--        @foreach($plans as $plan)--}}
{{--            <div class="col-12 col-lg-4 d-flex">--}}
{{--                @if($plan->id%3==0||$plan->id==1)--}}

{{--                    <div class="row">--}}
{{--                        @endif--}}

{{--                        <form>--}}
{{--                            <div class="price-table">--}}
{{--                                <div class="pricing-panel">--}}
{{--                                    <div class="pricing-body">--}}
{{--                                        <div class="pricing-heading">--}}
{{--                                            <h4 class="pricing-title">{{$plan->name}}</h4>--}}
{{--                                        </div>--}}
{{--                                        <div class="pricing-price">--}}
{{--                                            <p>--}}
{{--                                                <span class="currency">${{$plan->price}}</span>--}}
{{--                                                <span class="time"> شهرياً</span>--}}
{{--                                            </p>--}}
{{--                                        </div>--}}
{{--                                        <div class="pricing-list">--}}
{{--                                            <p>--}}
{{--                                                {{ $plan->description }}--}}
{{--                                            </p>--}}
{{--                                            <ul class="advantages-list list-unstyled">--}}
{{--                                                @foreach($planDetails as $planDetail)--}}
{{--                                                    @if($planDetail->plan_id == $plan->id)--}}
{{--                                                        @if($planDetail->is_unlimited == 1 )--}}
{{--                                                            <li>يمكنك إضافة عدد غير محدود--}}
{{--                                                                من {{$planDetail->key}}</li>--}}
{{--                                                        @else--}}
{{--                                                            <li>يمكنك إضافة--}}
{{--                                                                عدد {{$planDetail->value}}--}}
{{--                                                                من {{ $planDetail->key }}</li>--}}
{{--                                                        @endif--}}
{{--                                                    @endif--}}

{{--                                                @endforeach--}}

{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <button--}}
{{--                                        type="button"--}}
{{--                                        class="mt-4 btn btn-primary"--}}
{{--                                        data-plan-id="{{ $plan->id }}"--}}
{{--                                        data-bs-toggle="modal"--}}
{{--                                        data-bs-target="#exampleModal"--}}
{{--                                    >--}}
{{--                                        إشتراك--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            --}}{{--                        </div>--}}
{{--                        </form>--}}
{{--                        @if($plan->id%3==0||$plan->id==1)--}}

{{--                    </div>--}}
{{--                @endif--}}

{{--                @endforeach--}}


{{--            </div>--}}
{{--    </div>--}}
{{--</section>--}}
