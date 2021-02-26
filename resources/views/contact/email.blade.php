@section('contact-email')
<div class="input-group input-group-sm mb-2">
    <input type="email" name="emails[]" class="form-control" required>
    <div class="input-group-append">
        <button class="btn btn-success btn-add" type="button">Add</button>
    </div>
    <div class="invalid-feedback" data-default="Invalid email address">
        Invalid email address
    </div>
</div>
@endsection