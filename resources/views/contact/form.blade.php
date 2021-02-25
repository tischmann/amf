@include('contact.phone')
@include('contact.email')

@section('contact-form')
<!-- Contact form -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Add new contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(isset($contact))
                <form id="contactForm" class="needs-validation" novalidate method="post">
                    <input id="contactId" type="hidden" value="{{$contact->id}}">
                    @else
                    <form id="contactForm" class="needs-validation" novalidate method="put">
                        @endif
                        @csrf
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Name</span>
                                </div>
                                <input name="name" id="contactName" type="text" class="form-control" required>
                                <div class="invalid-feedback">
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
                    @if(isset($contact))
                    Update
                    @else
                    Add
                    @endif
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $(document).on('click', '.btn-add', function(event) {
            event.preventDefault();
            const group = $(this).parents('.addable-group');
            const currentItem = $(this).parents('.input-group:first');
            const newItem = $(currentItem.clone()).appendTo(group);
            currentItem.find('input').val('');
            group.find('.input-group:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('Remove');
        }).on('click', '.btn-remove', function(event) {
            $(this).parents('.input-group:first').remove();
            event.preventDefault();
            return false;
        });

        $('#contactForm').submit(function(event) {
            $(this).removeClass('was-validated');
            event.preventDefault();
            event.stopPropagation();

            if (!!this.checkValidity()) {
                $.ajax({
                    method: $(this).attr('method'),
                    url: `/contact/{{ $contact->id ?? '' }}`,
                    data: $(this).serializeArray(),
                }).done(function(data) {
                    if (data.status) {
                        window.location = window.location;
                    } else {
                        alert(`${data.message}`);
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    alert(`${textStatus}: ${errorThrown}`);
                });;

                return true;
            }

            $(this).addClass('was-validated');
        });

        $('#contactModal').on('hidden.bs.modal', function(event) {
            $('#contactForm').removeClass('was-validated')[0].reset();
        })

        $('#submitButton').click(function() {
            $('#contactForm').submit();
        });
    });
</script>
@endsection