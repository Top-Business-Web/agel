<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-6">
                <div class="form-group">
<<<<<<< HEAD
                    <label for="name" class="form-control-label">الاسم</label>
=======
                    <label for="name" class="form-control-label">الاسم
                    </label>
>>>>>>> 5e07fcb1d2b728e771b622a28b59b4ead2c1825a
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
<<<<<<< HEAD
                    <label for="country_id" class="form-control-label">المدينه</label>
                    <select class="form-control" name="city_id" id="city_id">
                        <option value="">اختر المدينة</option>
                        @foreach ($cities as $citie)
                            <option value="{{ $citie->id }}">{{ $citie->name }}</option>
=======
                    <label for="region_id" class="form-control-label">عنوان الفرع
                    </label>
                    <select class="form-control" name="region_id" id="region_id">
                        <option value="">اختر عنوان الفرع</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}-{{ $region->area->name }}-{{ $region->area->city->name }}</option>
>>>>>>> 5e07fcb1d2b728e771b622a28b59b4ead2c1825a
                        @endforeach
                    </select>
                </div>
            </div>

        </div>

        <div class="modal-footer">
<<<<<<< HEAD
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ</button>
=======
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ
            </button>
>>>>>>> 5e07fcb1d2b728e771b622a28b59b4ead2c1825a
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>
