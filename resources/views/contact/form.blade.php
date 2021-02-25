@include('contact.phone')
@include('contact.email')

@section('contact-form')
<!-- Contact form -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">
                    @if(isset($contact))
                    Edit contact
                    @else
                    New contact
                    @endif
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(isset($contact))
                <form id="contactForm" class="needs-validation" novalidate method="post">
                    <input type="hidden" name="id" value="{{$contact->id}}">
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
        function showErrorModal(textStatus, errorThrown) {
            const modal = $('#errorModal');
            modal.find('#errorModalLabel').html(textStatus);
            modal.find('.modal-body').html(errorThrown);
            modal.modal('show');
        }

        function resetFormValidation(form) {
            form.removeClass('was-validated');
            form.find('.invalid-feedback').each(function() {
                $(this).html($(this).data('default'));
            });
            form.find(':input').each(function() {
                this.setCustomValidity('');
            });
        }

        function showInvalidField(form, data) {
            const input = form.find(`:input`).filter(function() {
                return this.value == data.value;
            });
            input.next('.invalid-feedback').html(data.message);
            input[0].setCustomValidity(data.message);
            form.removeClass('was-validated');
            form[0].checkValidity();
            form.addClass('was-validated');
        }

        function submitForm(form) {
            $.ajax({
                method: form.attr('method'),
                url: `/contacts/{{ $contact->id ?? ''}}`,
                data: form.serializeArray(),
            }).done((data) => {
                if (data.status) {
                    window.location = window.location;
                } else {
                    showInvalidField(form, data);
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                showErrorModal(textStatus, errorThrown);
            });
        }

        $(document).on('click', '.btn-add', function(event) {
            event.preventDefault();
            const group = $(this).parents('.addable-group');
            const input = $(this).parents('.input-group:first');
            $(input.clone()).appendTo(group);
            input.find('input').val('');
            group.find('.input-group:not(:last) .btn-add')
                .removeClass('btn-add btn-success')
                .addClass('btn-remove btn-danger')
                .html('Remove');
        }).on('click', '.btn-remove', function(event) {
            $(this).parents('.input-group:first').remove();
            event.preventDefault();
            return false;
        });

        $('#contactForm').submit(function(event) {
            resetFormValidation($(this));
            event.preventDefault();
            event.stopPropagation();

            if (!!this.checkValidity()) {
                submitForm($(this));
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