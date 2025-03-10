<form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
    @csrf
    @method('PUT')

    <input type="hidden" name="plan_id" value="{{ $plan->id }}">

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="name" class="form-control-label">الإسم</label>
                <input type="text" class="form-control" name="name" id="name"
                    value="{{ old('name', $plan->name) }}">
            </div>
        </div>


        <div id="plans_container">
            @foreach ($plan->details as $planDetail)
                <!-- Add a row for each plan detail -->
                <div class="row plan-row border p-3 mb-2">
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-control-label">اختر نوع</label>
                            <select class="form-control" name="plans[{{ $planDetail->id }}][key]" required>
                                <option value="employee" {{ $planDetail->key == 'employee' ? 'selected' : '' }}>الموظفين
                                </option>
                                <option value="branch" {{ $planDetail->key == 'branch' ? 'selected' : '' }}>الفروع
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-control-label">Value</label>
                            <input type="text" class="form-control" name="plans[{{ $planDetail->id }}][value]"
                                value="{{ old('plans.' . $planDetail->id . '.value', $planDetail->value) }}">
                        </div>
                    </div>

                    <div class="col-2 d-flex align-items-center">
                        <div class="form-group">
                            <label class="form-control-label">Unlimited</label>
                            <input type="hidden" name="plans[{{ $planDetail->id }}][is_unlimited]" value="0">
                            <input type="checkbox" class="unlimited-checkbox"
                                name="plans[{{ $planDetail->id }}][is_unlimited]" value="1"
                                {{ $planDetail->is_unlimited == 1 ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="col-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger remove-plan">
                            <i class="fe fe-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-success mt-2" id="add_plan">
            <i class="fe fe-plus"></i> إضافة خطة جديدة
        </button>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary" id="addButton">تعديل</button>
    </div>
</form>
