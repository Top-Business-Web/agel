<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name') }}</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('price') }}</label>
                    <input type="text" class="form-control" name="price" id="price">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('period') }}</label>
                    <input type="text" class="form-control" name="period" id="period">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('discount') }}</label>
                    <input type="number" class="form-control" name="discount" id="discount">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('description') }}</label>
                    <textarea rows="3" type="text" class="form-control" name="description" id="description"> </textarea>
                </div>
            </div>


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('image') }}</label>
                    <input type="file" class="dropify" name="image" id="image">
                </div>
            </div>

        </div>

        <div class="col-12">
            <div id="plans_container"></div>
            <button type="button" class="btn btn-success mt-2" id="add_plan">
                <i class="fe fe-plus"></i> إضافة خطة جديدة
            </button>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-primary" id="addButton">{{ trns('save') }}</button>
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

        $('#add_plan').on('click', function() {
            planCount++;

            let selectKey = `
            <select class="form-control" name="plans[${planCount}][key]" required>
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
                        <input type="text" class="form-control" name="plans[${planCount}][value]" >
                    </div>
                </div>

                <div class="col-2 d-flex align-items-center">
                    <div class="form-group">
                        <label class="form-control-label">Unlimited</label>
                        <!-- Hidden input يضمن إرسال 0 إذا لم يتم تحديد checkbox -->
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
        });

        $('#plans_container').on('click', '.remove-plan', function() {
            $(this).closest('.plan-row').remove();
        });
    });
</script>
