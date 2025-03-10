<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">



            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name') }}</label>
                    <input type="text" class="form-control" name="name" id="name"
                        value="{{ $obj->name }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="country_id" class="form-control-label">{{ trns('city') }}</label>
                    <select class="form-control" name="city_id" id="city_id">
                        <option value="">اختر المدينة</option>
                        @foreach ($cities as $citie)
                            <option value="{{ $citie->id }}" {{ $obj->city_id == $citie->id ? 'selected' : '' }}>
                                {{ $citie->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>



        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{ trns('update') }}</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>
