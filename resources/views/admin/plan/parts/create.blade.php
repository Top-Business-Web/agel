<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">السعر</label>
                    <input type="number" class="form-control" name="price" id="price" step="0.01">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">المدة بالأيام</label>
                    <input type="number" class="form-control" name="period" id="period">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الخصم</label>
                    <input type="number" class="form-control" name="discount" id="discount">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">الوصف</label>
                    <textarea rows="3" type="text" class="form-control" name="description" id="description"> </textarea>
                </div>
            </div>


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">الصوره</label>
                    <input type="file" class="dropify" name="image" id="image">
                </div>
            </div>

        </div>

        <div class="col-12">
            <div id="plans_container"></div>
            <button type="button" class="btn btn-success mt-2" id="add_plan">
                <i class="fe fe-plus"></i> إضافة تفاصيل
            </button>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ</button>
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });


    $(document).ready(function() {
        let planCount = 0;
        let selectedOptions = new Set(); // Store selected options

        $('#add_plan').on('click', function() {
            planCount++;

            let selectKey = `
            <select class="form-control plan-select" name="plans[${planCount}][key]" required>
                <option value="">اختر نوع</option>
                <option value="employee">الموظفين</option>
                <option value="branch">الفروع</option>
            </select>
        `;

            let newPlan = `
            <div class="row plan-row border p-3 mb-2">
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-control-label">اختر نوع</label>
                        ${selectKey}
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label class="form-control-label">Value</label>
                        <input type="text" class="form-control plan-value" name="plans[${planCount}][value]">
                    </div>
                </div>

                <div class="col-2 d-flex align-items-center">
                    <div class="form-group">
                        <label class="form-control-label">غير محدود</label>
                        <input type="hidden" name="plans[${planCount}][is_unlimited]" value="0">
                        <input type="checkbox" class="unlimited-checkbox" name="plans[${planCount}][is_unlimited]" value="1">
                    </div>
                </div>

                <div class="col-2 d-flex align-items-center">
                    <button type="button" class="btn btn-danger remove-plan">
                        <i class="fe fe-trash"></i>
                    </button>
                </div>
            </div>
        `;

            $('#plans_container').append(newPlan);
            updateSelectOptions(); // Update options to disable already selected values
        });

        // Handle option selection
        $('#plans_container').on('change', '.plan-select', function() {
            let selectedValue = $(this).val();
            if (selectedValue) {
                selectedOptions.add(selectedValue);
            }
            updateSelectOptions();
        });

        // Handle unlimited checkbox toggle
        $('#plans_container').on('change', '.unlimited-checkbox', function() {
            let inputField = $(this).closest('.plan-row').find('.plan-value');
            if ($(this).is(':checked')) {
                inputField.prop('disabled', true).val(''); // Disable input when checked
            } else {
                inputField.prop('disabled', false);
            }
        });

        // Handle removing a plan
        $('#plans_container').on('click', '.remove-plan', function() {
            let removedValue = $(this).closest('.plan-row').find('.plan-select').val();
            if (removedValue) {
                selectedOptions.delete(removedValue);
            }
            $(this).closest('.plan-row').remove();
            updateSelectOptions();
        });

        // Function to update select options
        function updateSelectOptions() {
            $('.plan-select').each(function() {
                let currentValue = $(this).val();
                $(this).find('option').each(function() {
                    let optionValue = $(this).val();
                    if (optionValue && selectedOptions.has(optionValue) && optionValue !== currentValue) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });
        }
    });
</script>
