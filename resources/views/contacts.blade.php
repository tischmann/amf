@extends('layout')

@include('contact.search')
@include('contact.contact')
@include('contact.form')
@include('phone.form')
@include('email.form')

@section('content')

@yield('phone-add-form')

@yield('email-add-form')

<div class="container">
    <!-- Button pane -->
    <div class="row">
        <div class="col-md-2 py-3">
            <!-- Add contact button -->
            <button type="button" class="btn btn-success w-100" id="contactModalAdd">
                Add
            </button>
        </div>
        <div class="col-md-10 py-3">
            @yield('contact-search')
        </div>
    </div>
    <!-- Table -->
    <table class="table table-sm table-bordered" id="contactsTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="text-center align-middle">#</th>
                <th scope="col" class="text-center align-middle">Name</th>
                <th scope="col" class="text-center align-middle">Phones</th>
                <th scope="col" class="text-center align-middle">Emails</th>
                <th scope="col" class="text-center align-middle" colspan="2"></th>
            </tr>
        </thead>
        <tbody id="contactsTableTbody">
            @yield('contact')
        </tbody>
    </table>
    {{ $contacts->appends(Request::except('page'))->onEachSide(5)->links() }}
</div>
<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showErrorModal(textStatus, errorThrown) {
            $('#errorModal').find('#errorModalLabel').html(textStatus);
            $('#errorModal').find('.modal-body').html(errorThrown);
            $('#errorModal').modal('show');
        }

        function resetFormValidation() {
            $('#contactModal').removeClass('was-validated');
            $('#contactModal').find('.invalid-feedback').each(function() {
                $(this).html($(this).data('default'));
            });
            $('#contactModal').find(':input').each(function() {
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

        function contactFormCallback(data, textStatus, jqXHR) {
            if (data.status) {
                $('#contactModal').modal('hide');
                window.location = window.location;
            } else if (data.value) {
                showInvalidField(form, data);
            } else if (data.message) {
                $('#contactModal').modal('hide');
                showErrorModal('Error', data.message);
            } else {
                $('#contactModal').modal('hide');
                showErrorModal('Error', 'Invalid response');
            }
        }

        function sendContactAjax(form, method, callback = function() {}, id = '') {
            $.ajax({
                method: method,
                url: `/contacts/${id}`,
                data: form.serializeArray(),
            }).done((data, textStatus, jqXHR) => {
                callback(data, textStatus, jqXHR);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $('#contactModal').modal('hide');
                showErrorModal(textStatus, errorThrown);
            });
        }

        function addInputCallback(button, event = null) {
            if (event) event.preventDefault();
            const group = button.parents('.addable-group');
            const input = button.parents('.input-group:first');
            $(input.clone()).appendTo(group);
            input.find('input').val('');
            group.find('.input-group:not(:last) .btn-add')
                .removeClass('btn-add btn-success')
                .addClass('btn-remove btn-danger')
                .html('Remove');
        }

        function removeInputCallback(button, event = null) {
            button.parents('.input-group:first').remove();
            if (event) event.preventDefault();
            return false;
        }

        $(document).on('click', '.btn-add', function(event) {
            addInputCallback($(this), event);
        });

        $(document).on('click', '.btn-remove', function(event) {
            removeInputCallback($(this), event);
        });

        $('#contactForm').submit(function(event) {
            resetFormValidation();
            event.preventDefault();
            event.stopPropagation();

            if (!!this.checkValidity()) {
                sendContactAjax($('#contactForm'), $('#contactForm').data('method'),
                    function(data, textStatus, jqXHR) {
                        contactFormCallback(data, textStatus, jqXHR);
                    },
                    $('#contactForm').data('id') ?? '');
                return true;
            }

            $(this).addClass('was-validated');
        });

        $('#contactModalAdd').click(function() {
            $('#contactForm').data('method', 'put');
            $('#contactModalLabel').html('New contact');
            $('#submitButton').html('Add');
            $('#contactName').val('');
            const phone = $('#contactPhones').find('.input-group').first().clone();
            const email = $('#contactEmails').find('.input-group').first().clone();
            phone.val('').find('.btn').removeClass('btn-remove btn-danger').addClass('btn-add btn-success').html('Add');
            addInputCallback(phone);
            email.val('').find('.btn').removeClass('btn-remove btn-danger').addClass('btn-add btn-success').html('Add');
            addInputCallback(email);
            $('#contactPhones').find('.input-group').remove();
            $('#contactPhones').append(phone);
            $('#contactEmails').find('.input-group').remove();
            $('#contactEmails').append(email);
            $('#contactModal').modal('show');
        });

        $('.contact-update').click(function() {
            $('#contactForm').data('method', 'post');
            $('#contactForm').data('id', $(this).data('id'));
            $('#contactModalLabel').html('Edit contact');
            $('#submitButton').html('Update');

            $.get({
                url: `/contacts/${$(this).data('id')}`
            }).done((data) => {
                if (data) {
                    $('#contactName').val(data.name);
                    const clonedPhone = $('#contactPhones').find('.input-group').first().clone();
                    $('#contactPhones').find('.input-group').remove();

                    for (const phone of data.phones) {
                        const item = clonedPhone.clone();
                        item.find(":input").val(phone.phone);
                        item.find('.btn').removeClass('btn-add btn-success').addClass('btn-remove btn-danger').html('Remove');
                        removeInputCallback(item);
                        $('#contactPhones').append(item);
                    }

                    const lastPhone = $('#contactPhones').find('.input-group').last();

                    $('#contactPhones').find('.input-group').last().find('.btn').removeClass('btn-remove btn-danger')
                        .addClass('btn-add btn-success')
                        .html('Add');

                    addInputCallback(lastPhone);

                    const clonedEmail = $('#contactEmails').find('.input-group').first().clone();
                    $('#contactEmails').find('.input-group').remove();

                    for (const email of data.emails) {
                        const item = clonedEmail.clone();
                        item.find(":input").val(email.email);
                        item.find('.btn').removeClass('btn-add btn-success').addClass('btn-remove btn-danger').html('Remove');
                        removeInputCallback(item);
                        $('#contactEmails').append(item);
                    }

                    const lastEmail = $('#contactEmails').find('.input-group').last();

                    $('#contactEmails').find('.input-group').last().find('.btn').removeClass('btn-remove btn-danger')
                        .addClass('btn-add btn-success')
                        .html('Add');

                    addInputCallback(lastEmail);

                    $('#contactModal').modal('show');
                } else {
                    showErrorModal('Warning', 'Contact not found');
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                showErrorModal(textStatus, errorThrown);
            });
        });

        $('.contact-delete').click(function() {
            sendContactAjax($('#contactForm'),
                'delete',
                function(data, textStatus, jqXHR) {
                    contactFormCallback(data, textStatus, jqXHR);
                },
                $(this).data('id')
            );
        });

        $('#contactModal').on('hidden.bs.modal', function(event) {
            $('#contactForm').removeClass('was-validated')[0].reset();
        });

        $('#submitButton').click(function() {
            $('#contactForm').submit();
        });

        function phoneDelete(button) {
            $.ajax({
                method: 'delete',
                url: `/phones/${button.data('id')}`
            }).done((data, textStatus, jqXHR) => {
                if (data.status) {
                    $(`.phone-delete[data-id="${button.data('id')}"]`).parents('.input-group').remove();
                } else if (data.message) {
                    showErrorModal('Error', data.message);
                } else {
                    showErrorModal('Error', 'Invalid response');
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                showErrorModal(textStatus, errorThrown);
            });
        }

        function emailDelete(button) {
            $.ajax({
                method: 'delete',
                url: `/emails/${button.data('id')}`
            }).done((data, textStatus, jqXHR) => {
                if (data.status) {
                    $(`.email-delete[data-id="${button.data('id')}"]`).parents('.input-group').remove();
                } else if (data.message) {
                    showErrorModal('Error', data.message);
                } else {
                    showErrorModal('Error', 'Invalid response');
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                showErrorModal(textStatus, errorThrown);
            });
        }

        $('.phone-delete[data-id]').click(function() {
            phoneDelete($(this));
        });

        $('.email-delete[data-id]').click(function() {
            emailDelete($(this));
        });

        $('#phoneForm').submit(function(event) {
            $(this).removeClass('was-validated');
            event.preventDefault();
            event.stopPropagation();

            if (!!this.checkValidity()) {
                $.ajax({
                    method: 'put',
                    url: `/phones/${$(this).data('id')}`,
                    data: $(this).serializeArray()
                }).done((data, textStatus, jqXHR) => {
                    if (data.status) {
                        for (const phone of data.phones) {
                            const add = $(`<div class="input-group input-group-sm mb-1">
                                <input type="tel" class="form-control" pattern="^(\+?\d){7,13}$" value="${phone.phone}" required>
                                <div class="input-group-append">
                                    <button class="btn btn-primary phone-update" type="button" data-id="${phone.id}">Update</button>
                                    <button class="btn btn-danger phone-delete" type="button" data-id="${phone.id}">Remove</button>
                                </div>
                                <div class="invalid-feedback" data-default="Invalid phone number">
                                    Invalid phone number
                                </div>
                            </div>`);
                            add.find('.phone-delete[data-id]').click(function() {
                                phoneDelete($(this));
                            });
                            $(document).find(`.phone-add[data-id="${$(this).data('id')}"]`).before(add);
                        }
                    } else if (data.message) {
                        showErrorModal('Error', data.message);
                    } else {
                        showErrorModal('Error', 'Invalid response');
                    }

                    $('#phoneModal').modal('hide');
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    showErrorModal(textStatus, errorThrown);
                });
                return true;
            }

            $(this).addClass('was-validated');
        });

        $('#emailForm').submit(function(event) {
            $(this).removeClass('was-validated');
            event.preventDefault();
            event.stopPropagation();

            if (!!this.checkValidity()) {
                $.ajax({
                    method: 'put',
                    url: `/emails/${$(this).data('id')}`,
                    data: $(this).serializeArray()
                }).done((data, textStatus, jqXHR) => {
                    if (data.status) {
                        for (const email of data.emails) {
                            const add = $(`<div class="input-group input-group-sm mb-1">
                                <input type="email" class="form-control" value="${email.email}" required>
                                <div class="input-group-append">
                                    <button class="btn btn-primary email-update" type="button" data-id="${email.id}">Update</button>
                                    <button class="btn btn-danger email-delete" type="button" data-id="${email.id}">Remove</button>
                                </div>
                                <div class="invalid-feedback" data-default="Invalid email address">
                                    Invalid email address
                                </div>
                            </div>`);
                            add.find('.email-delete[data-id]').click(function() {
                                emailDelete($(this));
                            });
                            $(document).find(`.email-add[data-id="${$(this).data('id')}"]`).before(add);
                        }
                    } else if (data.message) {
                        showErrorModal('Error', data.message);
                    } else {
                        showErrorModal('Error', 'Invalid response');
                    }

                    $('#emailModal').modal('hide');
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    showErrorModal(textStatus, errorThrown);
                });
                return true;
            }

            $(this).addClass('was-validated');
        });

        $('#phoneModal').on('hidden.bs.modal', function(event) {
            $('#phoneForm').removeClass('was-validated')[0].reset();
        });

        $('#addPhones').click(function() {
            $('#phoneForm').submit();
        });

        $('.phone-add').click(function() {
            $('#phoneModal').find('.btn-remove').each(function() {
                $(this).parents('.input-group').remove();
            });
            $('#phoneForm').data('id', $(this).data('id'));
            $('#phoneModal').modal('show');
        });

        $('#emailModal').on('hidden.bs.modal', function(event) {
            $('#emailForm').removeClass('was-validated')[0].reset();
        });

        $('#addEmails').click(function() {
            $('#emailForm').submit();
        });

        $('.email-add').click(function() {
            $('#emailModal').find('.btn-remove').each(function() {
                $(this).parents('.input-group').remove();
            });
            $('#emailForm').data('id', $(this).data('id'));
            $('#emailModal').modal('show');
        });

        $('.table-phone').submit(function(event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).find('.phone-update[data-id]').click();
        });

        $('.table-email').submit(function(event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).find('.email-update[data-id]').click();
        });

        $('.email-update[data-id]').click(function(event) {
            resetEmailsPhonesValidation();
            sendAjaxPhoneEmail($(this), `/emails`, {
                email: $(this).parents('.input-group').find(':input').val()
            });
        });

        $('.phone-update[data-id]').click(function(event) {
            resetEmailsPhonesValidation();
            sendAjaxPhoneEmail($(this), `/phones`, {
                phone: $(this).parents('.input-group').find(':input').val()
            });
        });

        function resetEmailsPhonesValidation() {
            $('form.table-email, form.table-phone')
                .removeClass('was-validated')
                .find('.invalid-feedback').each(function() {
                    $(this).html($(this).data('default'));
                });
        }

        function sendAjaxPhoneEmail(button, url, data) {
            const form = button.parents('form.needs-validation');
            form.removeClass('was-validated');

            if (!!form[0].checkValidity()) {
                const group = button.parents('.input-group');
                const input = group.find(':input');
                $.ajax({
                    method: 'post',
                    url: `${url}/${button.data('id')}`,
                    data: data
                }).done((data, textStatus, jqXHR) => {
                    if (data.status) {
                        // Do nothing
                    } else if (data.message) {
                        group.find('.invalid-feedback').html(data.message);
                        input[0].setCustomValidity(data.message);
                        form.addClass('was-validated');
                    } else {
                        showErrorModal('Error', 'Invalid response');
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    showErrorModal(textStatus, errorThrown);
                });
            } else {
                form.addClass('was-validated');
            }
        }
    });
</script>
@yield('contact-form')
@endsection