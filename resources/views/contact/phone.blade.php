@section('contact-phone')
<div class="input-group input-group-sm mb-2">
    <input type="tel" name="phones[]" class="form-control" pattern="^(\+?\d){7,13}$" required>
    <div class="input-group-append">
        <button class="btn btn-success btn-add" type="button">Add</button>
    </div>
    <div class="invalid-feedback" data-default="Invalid phone number">
        Invalid phone number
    </div>
</div>
@endsection