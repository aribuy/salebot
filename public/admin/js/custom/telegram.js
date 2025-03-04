$(document).ready(function () {
    $(document).on("click", ".__js_update", function () {
        confirmationAlert($(this).data("url"), $(this).data("id"), "Yes, Execute It!");
      });
      const confirmationAlert = (
        url,
        data_id,
        button_test = "Yes, Confirmed it!"
      ) => {
        Swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: button_test,
        }).then((confirmed) => {
          if (confirmed.isConfirmed) {
            axios
              .post(url, { data_id: data_id })
              .then((response) => {
                refreshDataTable();
                console.log(response);
                Swal.fire(
                  response.data.message,
                  response.data.status == true ? "success" : "error",
                  response.data.status == true ? "success" : "error"
                );
              })
              .catch((error) => {
                console.log(error);
                Swal.fire(error.response.data);
                refreshDataTable();
              });
          }
        });
      };
      const refreshDataTable = () => {
        $("#dataTableBuilder").DataTable().ajax.reload();
      };
});