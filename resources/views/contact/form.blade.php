@include('contact.phone')
@include('contact.email')

@section('contact-form')
<!-- Contact form -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">
                    New contact
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="contactForm" class="needs-validation" novalidate data-method="put">
                    <div class="form-group">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Name</span>
                            </div>
                            <input name="name" id="contactName" type="text" class="form-control" required>
                            <div class="invalid-feedback" data-default="Enter contact name">
                                Enter contact name
                            </div>
                        </div>
                    </div>
                    <hr>
                    <label for="contactPhones">Phones</label>
                    <div class="form-group addable-group" id="contactPhones">
                        @yield('contact-phone')
                    </div>
                    <hr>
                    <label for="contactEmails">Emails</label>
                    <div class="form-group addable-group" id="contactEmails">
                        @yield('contact-email')
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitButton">
                    Add
                </button>
            </div>
        </div>
    </div>
</div>
@endsection