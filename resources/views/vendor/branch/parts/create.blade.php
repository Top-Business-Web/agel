<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الاسم</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                        <label for="region_id" class="form-control-label">عنوان الفرع</label>
                        <select class="form-control" name="region_id" id="region_id">
                            <option value="">اختر عنوان الفرع</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}-{{ $region->area->name }}
                                    -{{ $region->area->city->name }}</option>
                            @endforeach
                        </select>
                </div>
            </div>


        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ</button>

        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>
