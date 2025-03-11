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
                    <label for="country_id" class="form-control-label">المدينه</label>
                    <select class="form-control" name="city_id" id="city_id">
                        <option value="">اختر المدينة</option>
                        @foreach ($cities as $citie)
                            <option value="{{ $citie->id }}">{{ $citie->name }}</option>
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
