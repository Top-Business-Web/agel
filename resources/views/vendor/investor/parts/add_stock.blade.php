<div class="modal-body">
    <form method="POST" id="addFormStock" class="addFormStock" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">

            <input type="hidden" name="investor_id" value="{{ $investorId }}">
            <div class="col-6">
                <div class="form-group">
                    <label for="operation" class="form-control-label">نوع العملية</label>
                    <select class="form-control" name="operation" id="operation" >
                        <option value="" selected disabled>اختر نوع العملية</option>
                        <option value="1">أضافة</option>
                        <option value="0">أنقاص</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="category_id" class="form-control-label">الصنف</label>
                    <select class="form-control" name="category_id" id="category_id" >
                        <option value="" selected disabled>اختر الصنف</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="branch_id" class="form-control-label">الفرع</label>
                    <select class="form-control" name="branch_id" id="branch_id" >
                        <option value="" selected disabled>اختر الفرع</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="quantity " class="form-control-label">
                        الكمية
                    </label>
                    <input type="number" class="form-control" name="quantity" id="quantity" >
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="total_price_add" class="form-control-label">
                        السعر
                    </label>
                    <input type="number" class="form-control" name="total_price_add" id="total_price_add" >
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="total_price_sub" class="form-control-label">
                        السعر
                    </label>
                    <input type="number" class="form-control" name="total_price_sub" id="total_price_sub" >
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="vendor_commission" class="form-control-label">أجمالي العمولة للمكتب
                    </label>
                    <input type="number" class="form-control" name="vendor_commission" id="vendor_commission" >
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="investor_commission" class="form-control-label">أجمالي العمولة للمستثمر
                    </label>
                    <input type="number" class="form-control" name="investor_commission" id="investor_commission"
                           >
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="sell_diff" class="form-control-label">
                        فروقات اعاده البيع
                    </label>
                    <input type="number" class="form-control" name="sell_diff" id="sell_diff" >
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="text">
                <span>الكميه = </span>
                <span id="quantity_display">0</span>
            </div>
            <br>
            <div class="text">
                <span>السعر = </span>
                <span id="price_display">0</span>
            </div>
            <br>

            <div class="text">
                <span> العمولات = </span>
                <span id="commission_display">0</span>
            </div>
            <br>

            <div class="text">
                <span>فروقات اعاده البيع = </span>
                <span id="sell_diff_display">0</span>
            </div>
            <br>

            <div class="text">
                <span>الإجمالي = </span>
                <span id="total_display">0</span>
            </div>
            <br>

            <div class="text">
                <span>سعر الوحده = </span>
                <span id="division_result">0</span>
            </div>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButtonStock">حفظ
            </button>
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#addStock .modal-content')

    });
</script>
<script>
    $(document).ready(function () {
        function toggleFields() {
            var operation = $("#operation").val();
            $(".form-group").closest('.col-6').fadeOut(200);
            $("#operation").closest('.col-6').fadeIn(300);

            setTimeout(function () {
                if (operation === "1") { // إضافة
                    $("#category_id").closest('.col-6').fadeIn(300);
                    $("#branch_id").closest('.col-6').fadeIn(300);
                    $("#quantity").closest('.col-6').fadeIn(300);
                    $("#total_price_add").closest('.col-6').fadeIn(300);
                    $("#vendor_commission").closest('.col-6').fadeIn(300);
                    $("#investor_commission").closest('.col-6').fadeIn(300);
                    $("#sell_diff").closest('.col-6').fadeIn(300);
                    $(".footer").fadeIn(300); // إظهار الفوتر

                } else if (operation === "0") { // إنقاص
                    $("#category_id").closest('.col-6').fadeIn(300);
                    $("#branch_id").closest('.col-6').fadeIn(300);
                    $("#quantity").closest('.col-6').fadeIn(300);
                    $("#total_price_sub").closest('.col-6').fadeIn(300);
                    $(".footer").fadeOut(200); // إخفاء الفوتر
                }
            }, 200);

            // إخفاء الفوتر عند تحميل الصفحة
            $(".footer").hide();
        }

        function updateTotals() {
            let quantity = parseFloat($("#quantity").val()) || 0;
            let totalPrice = parseFloat($("#total_price_add").val()) || 0;
            let vendorCommission = parseFloat($("#vendor_commission").val()) || 0;
            let investorCommission = parseFloat($("#investor_commission").val()) || 0;
            let sellDiff = parseFloat($("#sell_diff").val()) || 0;

            let totalCommission = vendorCommission + investorCommission;
            let grandTotal = totalPrice - totalCommission - sellDiff;

            let divisionResult = (quantity !== 0) ? (grandTotal / quantity).toFixed(2) : 0;

            $("#quantity_display").text(quantity);
            $("#price_display").text(totalPrice);
            $("#commission_display").text(totalCommission);
            $("#sell_diff_display").text(sellDiff);
            $("#total_display").text(grandTotal);
            $("#division_result").text(divisionResult);
        }

        $(".form-group input").on("input", updateTotals);

        $(".form-group").closest('.col-6').hide();
        $("#operation").closest('.col-6').show();
        $("#operation").change(toggleFields);
        toggleFields();
    });


    // add script
    $(document).one('submit', '#addFormStock', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $('#addFormStock').attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#addButtonStock').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                    ' ></span> <span style="margin-left: 4px;">أنتظر قليلًا...</span>').attr('disabled', true);
            },
            success: function (data) {
                if (data.status == 200) {
                    $('#dataTable').DataTable().ajax.reload();
                    toastr.success('تمت العملية بنجاح');
                } else if(data.status == 405){
                    toastr.error(data.mymessage);
                }
                else
                    toastr.error('حدث خطأ ما');
                $('#addButtonStock').html(`اضافه`).attr('disabled', false);
                $('#addStock').modal('hide')
            },
            error: function (data) {
                if (data.status === 500) {
                    toastr.error('');
                } else if (data.status === 422) {
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function (key, value) {
                        if ($.isPlainObject(value)) {
                            $.each(value, function (key, value) {
                                toastr.error(value, 'خطأ');
                            });
                        }
                    });
                } else
                    toastr.error('حدث خطأ ما');
                $('#addButtonStock').html(`اضافة`).attr('disabled', false);
            },//end error method

            cache: false,
            contentType: false,
            processData: false
        });
    });
</script>





