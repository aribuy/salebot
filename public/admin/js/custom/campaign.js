
$('#template_id').on('change', function(e) {
    let template_id = $(this).val();
    if (template_id.trim() !== '') {
        let url = get_template.replace('__template_id__', template_id);
        axios.get(url, {
                params: {
                    id: template_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(response => {
                console.log(response);
                $('#load-template').html(response.data);
            })
            .catch(error => {
                toastr.error(error.message);
            });
    } else {
        console.log('Template ID is empty');
    }
});

$('#load-template').on('change', '.body-match-select', function() {
    var selectedValue = $(this).val();
    if (selectedValue === 'input_value') {
        $(this).closest('.match-value-select').next('.body-value-input').show();
    } else {
        $(this).closest('.match-value-select').next('.body-value-input').hide();
    }
});
$(document).ready(function() {
    $('#send_scheduled').on('change', function() {
        $('#schedule_time_div').toggle($(this).prop('checked'));
        $('#schedule_time').prop('required', $(this).prop('checked'));
    });
    // Manually validate the field on form submit
    $('#campaign_store').on('submit', function(event) {
        if ($('#send_scheduled').prop('checked')) {
            if ($('#schedule_time').val().trim() === '') {
                event.preventDefault();
                toastr.error('Please select a schedule time.');
            }
        }
    });
});

$(document).on('click', '.__add_contact', function() {
    let id = $(this).data('id');
    $('[name^="contact_list_id"]').remove();
    let formId = $('#__contact_modal_form');
    $('<input>').attr({
        type: 'hidden',
        name: 'contact_list_id[]',
        value: id
    }).appendTo(formId);
    $('#contactModal').modal('show');
});

$(document).on('submit', '#__contact_modal_form', function(event) {
    event.preventDefault();
    const form = $(this);
    const url = form.attr('action');
    const data = form.serialize();
    axios.post(url, data)
        .then(response => {
            refreshDataTable();
            toastr.success(response.data.message);
            $('#__contact_modal_form')[0].reset();
            setTimeout(() => {
                $('#contactModal').modal("hide");
            }, 300);
        })
        .catch(error => {
            refreshDataTable();
            if (typeof error.response.data === 'string') {
                toastr.error(error.response.data);
            }
            var errors = error.response.data.errors || [];
            for (let key in errors) {
                let id = `#${key}`;
                $(id).addClass('is-invalid');
                $(id).siblings('.invalid-feedback').html(errors[key][0]);
                $(id).siblings('.invalid-feedback').show();
            }
        });
});
flatpickr("#created_at", {
    mode: "range",
    dateFormat: "Y-m-d",
});
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
$(document).on('click', '.__js_resend', function() {
    $('#campaign_id').val($(this).data("id"));
    $('#__js_resend_modal').modal('show');
})
$(document).ready(function() {
    $('#filterBTN').click(function() {
        $('#filterSection').toggleClass('show');
    });

    const advancedSearchMapping = (attribute) => {
        $('#dataTableBuilder').on('preXhr.dt', function(e, settings, data) {
            data[attribute.key] = attribute.value;
        });
    }

    $(document).on('change', '.filterable', function() {
        advancedSearchMapping({
            key: $(this).attr('id'),
            value: $(this).val(),
        });
    });

    $(document).on('click', '#reset', () => {
        $('.filterable').val('').trigger('change');
        $('#dataTableBuilder').DataTable().ajax.reload();
    });

    $(document).on('click', '#filter', () => {
        $('#checkAll').prop('checked', false).trigger('change');
        $('#dataTableBuilder').DataTable().ajax.reload();
    });
});