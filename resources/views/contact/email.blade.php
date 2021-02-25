@section('contact-email')
<div class="input-group mb-3">
    <input type="email" name="emails[]" class="form-control" required>
    <div class="input-group-append">
        <button class="btn btn-success btn-add" type="button">Add</button>
    </div>
    <div class="invalid-feedback">
        Enter valid email address
    </div>
</div>
@endsection