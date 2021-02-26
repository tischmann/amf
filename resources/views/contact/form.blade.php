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
                    Add
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        const contactModal = $('#contactModal');
        const errorModal = $('#errorModal');
        const modalLabel = $('#contactModalLabel');
        const contactForm = $('#contactForm');
        const submitButton = $('#submitButton');
        const contactName = $('#contactName');
        const contactPhones = $('#contactPhones');
        const contactEmails = $('#contactEmails');

        function showErrorModal(textStatus, errorThrown) {
            errorModal.find('#errorModalLabel').html(textStatus);
            errorModal.find('.modal-body').html(errorThrown);
            errorModal.modal('show');
        }

        function resetFormValidation() {
            contactModal.removeClass('was-validated');
            contactModal.find('.invalid-feedback').each(function() {
                $(this).html($(this).data('default'));
            });
            contactModal.find(':input').each(function() {
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

        function sendAjax(form, method, id = '') {
            $.ajax({
                method: method,
                url: `/contacts/${id}`,
                data: form.serializeArray(),
            }).done((data, textStatus, jqXHR) => {
                if (data.status) {
                    contactModal.modal('hide');
                    window.location = window.location;
                } else if (data.value) {
                    showInvalidField(form, data);
                } else if (data.message) {
                    contactModal.modal('hide');
                    showErrorModal('Error', data.message);
                } else {
                    contactModal.modal('hide');
                    showErrorModal('Error', 'Invalid response');
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                contactModal.modal('hide');
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

        contactForm.submit(function(event) {
            resetFormValidation();
            event.preventDefault();
            event.stopPropagation();

            if (!!this.checkValidity()) {
                sendAjax(contactForm, contactForm.data('method'), contactForm.data('id') ?? '');
                return true;
            }

            $(this).addClass('was-validated');
        });

        $('#contactModalAdd').click(function() {
            contactForm.data('method', 'put');
            modalLabel.html('New contact');
            submitButton.html('Add');
            contactName.val('');
            contactPhones.html(`@yield('contact-phone')`)
                .find('.input-group').remove();
            contactEmails.html(`@yield('contact-email')`)
                .find('.input-group').remove();
            contactModal.modal('show');
        });

        $('.contact-update').click(function() {
            contactForm.data('method', 'post');
            contactForm.data('id', $(this).data('id'));
            modalLabel.html('Edit contact');
            submitButton.html('Update');

            $.get({
                url: `/contacts/${$(this).data('id')}`
            }).done((data) => {
                if (data) {
                    contactName.val(data.name);

                    for (const phone of data.phones) {
                        contactPhones.html(`@yield('contact-phone')`)
                            .find(':input').val(phone);
                    }

                    for (const email of data.emails) {
                        contactEmails.html(`@yield('contact-email')`)
                            .find(':input').val(email);
                    }

                    contactModal.modal('show');
                } else {
                    showErrorModal('Warning', 'Contact not found');
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                showErrorModal(textStatus, errorThrown);
            });
        });

        $('.contact-delete').click(function() {
            sendAjax(contactForm, 'delete', $(this).data('id'));
        });

        contactModal.on('hidden.bs.modal', function(event) {
            contactForm.removeClass('was-validated')[0].reset();
        })

        submitButton.click(function() {
            contactForm.submit();
        });
    });
</script>
@endsection