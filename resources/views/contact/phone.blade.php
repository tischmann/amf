@section('contact-phone')
<div class="input-group mb-3">
    <input type="tel" name="phones[]" class="form-control" required>
    <div class="input-group-append">
        <button class="btn btn-success btn-add" type="button">Add</button>
    </div>
    <div class="invalid-feedback">
        Enter valid phone number
    </div>
</div>
@endsection