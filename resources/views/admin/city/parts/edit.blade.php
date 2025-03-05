<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">

            <div class="col-12">
                <div id="map" style="height: 400px; width: 100%;"></div>



            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name') }} {{ trns('arabic') }}</label>
                    <input type="text" class="form-control" name="name[ar]" id="name" value="{{$obj->getTranslation('name', 'ar')}}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name') }} {{ trns('english') }}</label>
                    <input type="text" class="form-control" name="name[en]" id="name" value="{{$obj->getTranslation('name', 'en')}} ">
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="country_id" class="form-control-label">{{ trns('country') }}</label>
                    <select class="form-control" name="country_id" id="country_id">
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ $obj->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="map_url" class="form-control-label">{{ trns('location') }}</label>
                    <input type="text" class="form-control" name="location" id="map_url" readonly value="{{$obj->location}} ">
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

    });</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuTilAfnGfkZtIx0T3qf-eOmWZ_N2LpoY&callback=initMap">
</script>

<script>
    function initMap() {
        var defaultLocation = {lat: 24.7136, lng: 46.6753};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: defaultLocation
        });

        var marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true
        });

        google.maps.event.addListener(marker, 'dragend', function (event) {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            $('#map_url').val(lat + ',' + lng);
        });

        map.addListener('click', function (event) {
            marker.setPosition(event.latLng);
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            $('#map_url').val(lat + ',' + lng);
        });
    }


</script>

