@section('contact-phone')
<div class="input-group mb-3">
    <input type="tel" name="phones[]" class="form-control" pattern="^(?:\+\d{1,4})?\d{9,}$" required>
    <div class="input-group-append">
        <button class="btn btn-success btn-add" type="button">Add</button>
    </div>
    <div class="invalid-feedback" data-default="Enter valid phone number">
        Enter valid phone number
    </div>
</div>
@endsection