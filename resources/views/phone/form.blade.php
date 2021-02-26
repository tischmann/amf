@include('contact.phone')

@section('phone-add-form')
<!-- Phone form -->
<div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="phoneModalLabel">
                    New phone
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="phoneForm" class="needs-validation" novalidate data-method="put">
                    <div class="form-group addable-group">
                        @yield('contact-phone')
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addPhones">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection