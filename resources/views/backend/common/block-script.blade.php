<script>
    function contact(route, row_id, is_reload) {
        var url = route + '/' + row_id;
        var token = "{{ csrf_token() }}";
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, do it',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ff0000'
        }).then((confirmed) => {
            if (confirmed.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        _token: token
                    },
                    url: url,
                    success: function (response) {
                        Swal.fire(
                            response.title,
                            response.message,
                            response.status
                        ).then((confirmed) => {
                            if (is_reload || response.is_reload) {
                                location.reload();
                            } else {
                                $('.dataTable').DataTable().ajax.reload();
                            }
                        });
                    },
                    error: function (response) {
                        Swal.fire(
                            response.title,
                            response.message,
                            response.status
                        );
                    }
                });
            }
        });
    }
</script>
