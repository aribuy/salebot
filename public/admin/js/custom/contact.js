$(document).on('click', '.__js_delete', function () {
    confirmationAlert(
        $(this).data('url'),
        $(this).data('id'),
        'Yes, Delete It!'
    )
})
const confirmationAlert = (url, data_id, button_test = 'Yes, Confirmed it!') => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: button_test,
    }).then((confirmed) => {
        if (confirmed.isConfirmed) {
            axios.delete(url, { data_id: data_id })
                .then(response => {
                    refreshDataTable();
                    console.log(response);
                    Swal.fire(
                        response.data.msg,
                        response.data.status == true ?'deleted':'error',
                        response.data.status == true ? 'success':'error',
                    );
                })
                .catch(error => {
                    console.log(error);
                    Swal.fire(error.response.data);
                    refreshDataTable();
                })
        }
    });
};
const refreshDataTable = () => {
    $("#dataTableBuilder").DataTable().ajax.reload();
};

var contactId; // Define contactId variable globally
$(document).on('click', '.__template_modal', function() {
    // Get the 'data-id' attribute value
    contactId = $(this).data('id');
    $('#template_modal').modal('show');
});

// When sending template link is clicked
$(document).on('click', '.send-template-link', function() {
    // Get the contactId from the globally defined variable
    var templateContactId = contactId;
    // Get the templateId from the clicked link's data attribute
    var templateId = $(this).data('template');
    // Get the base URL from the href attribute
    var baseUrl = $(this).attr('href');
    // Construct the full URL with both template_id and contact_id
    var fullUrl = baseUrl + '&contact_id=' + templateContactId;
    // Set the updated URL to the href attribute
    $(this).attr('href', fullUrl);
});

$(document).ready(function() {
    $('.dropdown-submenu').on('click', function(event) {
        $('.dropdown-submenu ul').removeClass('show');
        $(this).find('ul').toggleClass('show');
        event.stopPropagation();
    });
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.dropdown-submenu').length) {
            $('.dropdown-submenu ul').removeClass('show');
        }
    });
});


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

$(document).on('click', '.common-key', function() {
    var anyChecked = false;

    $('.custom-control-input').each(function() {
        if ($(this).prop('checked')) {
            anyChecked = true;
            return false;
        }
    });

    if (anyChecked) {
        $('.custom-dropdown').removeClass('d-none');
    } else {
        $('.custom-dropdown').addClass('d-none');
    }
});


$(document).ready(function() {
    $(document).on('change', '#checkAll', function() {
        $('.all-item-input').prop('checked', this.checked);
        var check = $(this).prop('checked');

        $('.custom-control-input').each(function() {
            if (check) {
                anyChecked = true;
                return false;
            }
        });

        if (check) {
            $('.custom-dropdown').removeClass('d-none');
        } else {
            $('.custom-dropdown').addClass('d-none');
        }
    });
});

$(document).on('click', '.blacklist', function() {
    let selector = $('.common-key:checked');
    let ids = [];
    $.each(selector, function() {
        let val = $(this).val();
        ids.push(val);
    });
    $.ajax({
        url: blacklistUrl,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            ids: ids,
            is_blacklist: 1
        },
        success: function(response) {
            if (response.status === 200) {
                refreshDataTable();
                // setTimeout(function() {
                //     window.location.reload();
                // }, 2000);
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
})

$(document).on('click', '.remove_blacklist', function() {
    let selector = $('.common-key:checked');
    let ids = [];
    $.each(selector, function() {
        let val = $(this).val();
        ids.push(val);
    });
    $.ajax({
        url: removeBlacklistUrl,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            ids: ids,
            is_blacklist: 0
        },
        success: function(response) {
            refreshDataTable();
            if (response.status === 200) {
                // setTimeout(function() {
                //     window.location.reload();
                // }, 2000);
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            refreshDataTable();

            console.log(xhr.responseText);
        }
    });
})

$(document).on('click', '.remove_list', function() {
    let selector = $('.common-key:checked');
    let ids = [];
    $.each(selector, function() {
        let val = $(this).val();
        ids.push(val);
    });
    $.ajax({
        url: removelistUrl,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            ids: ids,
        },
        success: function(response) {
            refreshDataTable();

            if (response.status === 200) {
                // setTimeout(function() {
                //     window.location.reload();
                // }, 2000);
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            refreshDataTable();

            console.log(xhr.responseText);
        }
    });
})


$(document).ready(function() {
    $('.dropdown-menu').on('click', '.add_list', function() {
        let listId = $(this).data('list-id');
        let selector = $('.common-key:checked');
        let ids = [];
        $.each(selector, function() {
            let val = $(this).val();
            ids.push(val);
        });
        $.ajax({
            url: addListUrl,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                ids: ids,
                contact_list_id: listId,
            },
            success: function(response) {
                refreshDataTable();

                if (response.status === 200) {
                    // setTimeout(function() {
                    //     window.location.reload();
                    // }, 2000);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                refreshDataTable();

                toastr.error('An error occurred while processing your request.');
            }
        });
    });
});


$(document).ready(function() {
    $('.dropdown-menu').on('click', '.add_segment', function() {
        let segmentId = $(this).data('segment-id');
        let selector = $('.common-key:checked');
        let ids = [];
        $.each(selector, function() {
            let val = $(this).val();
            ids.push(val);
        });
        $.ajax({
            url: addSegmentUrl,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                _token: '{{ csrf_token() }}',
                ids: ids,
                segment_id: segmentId,
            },
            success: function(response) {
                refreshDataTable();

                if (response.status === 200) {
                    // setTimeout(function() {
                    //     window.location.reload();
                    // }, 2000);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                refreshDataTable();

                toastr.error('An error occurred while processing your request.');
            }
        });
    });
});

$(document).on('click', '.remove_segment', function() {
    let selector = $('.common-key:checked');
    let ids = [];
    $.each(selector, function() {
        let val = $(this).val();
        ids.push(val);
    });
    $.ajax({
        url: removeSegmentUrl,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            ids: ids,
        },
        success: function(response) {
            refreshDataTable();
            if (response.status === 200) {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            refreshDataTable();

            console.log(xhr.responseText);
        }
    });
})


$(document).on('click', '.__view_details', (e) => {
    let contact_id = $(e.currentTarget).data('id').toString(); // Convert to string
    if (contact_id.trim() !== '') {
        let url = get_contact.replace('__contact_id__', contact_id); // Ensure placeholder matches
        axios.get(url, {
                params: {
                    id: contact_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(response => {
                console.log(response);
                $('#contactViewModalBody').html(response.data.data);
                $('#contactViewModal').modal('show');
                $('.form-select').each(function() { 
                    $(this).select2({ dropdownParent: $(this).parent()});
                })
                $('#birthdate').flatpickr({
                    dateFormat: "Y-m-d",
                    static: true,
                    allowInput: true 
                });
            })
            .catch(error => {
                toastr.error(error.message);
            });
    } else {
        console.log('Contact ID is empty');
    }
});

$(document).on("submit", "#contact-details-update", async (event) => {
    event.preventDefault();
    debounceButton();
    try {
      const url = $("#contact-details-update").attr("action");
      const data = new FormData($("#contact-details-update")[0]);
      const response = await axios.post(url, data, {
        processData: false,
        contentType: false,
      });
      console.log(response);
      if (response.data.status) {
        toastr.success(response.data.message);
        $("#contact-details-update")[0].reset(); // Reset the form
        $('#contactViewModal').modal('hide');
        refreshDataTable();
      } else {
        toastr.error(response.data.message);
      }
    } catch (error) {
      console.log(error);
      if (typeof error.response.data === "string") {
        toastr.error(error.response.data.message);
      } else {
        const errors = error.response.data.errors || {};
        for (const key in errors) {
          const id = `#${key}`;
          console.log(id);
          $(id).addClass("is-invalid");
          $(id).siblings(".invalid-feedback").html(errors[key][0]);
          $(id).siblings(".invalid-feedback").show();
        }
        toastr.error(error.response.data.message);
      }
    } finally {
      debounceButton();
    }
  });