@section('contact-search')
<!-- Search contact -->
<div class="input-group mb-3">
    <input type="text" class="form-control" placeholder="Search contact by name, email address or phone number" id="searchValue" value="{{ $search ?? '' }}">
    <div class="input-group-append">
        <button class="btn btn-secondary" type="button" id="resetSearchButton">Reset</button>
        <button class="btn btn-info" type="button" id="searchButton">Find</button>
    </div>
</div>
<script>
    $(function() {
        $('#resetSearchButton').click(function() {
            window.location = location.protocol + '//' + location.host + location.pathname;
        });

        $('#searchButton').click(function() {
            window.location = encodeURI(location.protocol + '//' + location.host + location.pathname + `?search=${$('#searchValue').val()}`);
        });
    });
</script>
@endsection