$(document).on("click", ".remove-lang-key", function () {
  confirmationAlert(
    $(this).data("url"),
    $(this).data("id"),
    $(this).data("key"),
    $(this).data("value"),
    "Yes, Delete It!",
    "#item_" + $(this).data("key")
  );
});

const confirmationAlert = (
  url,
  data_id,
  key,
  value,
  button_text = "Yes, Confirm it!",
  row_selector
) => {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: button_text,
  }).then((confirmed) => {
    if (confirmed.isConfirmed) {
      axios
        .post(url, {
          data_id: data_id,
          key: key,
          value: value,
        })
        .then((response) => {
          console.log(response);
          Swal.fire(
            response.data.message,
            response.data.status == true ? "Deleted" : "Error",
            response.data.status == true ? "success" : "error"
          );
          if (response.data.status == true) {
            $(row_selector).remove();
          }
        })
        .catch((error) => {
          console.log(error);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: error.response.data.message,
          });
        });
    }
  });
};

$(document).on("submit", "#store_lang_key_form", async (event) => {
  event.preventDefault();
  debounceButton();
  try {
    const url = $("#store_lang_key_form").attr("action");
    const data = new FormData($("#store_lang_key_form")[0]);
    const response = await axios.post(url, data, {
      processData: false,
      contentType: false,
    });
    console.log(response);
    if (response.data.status) {
      toastr.success(response.data.message);
      $("#store_lang_key_form")[0].reset(); // Reset the form
      // window.location.href = response.data.redirect_to;
    } else {
      toastr.error(response.data.message);
    }
    location.reload();
  } catch (error) {
    console.log(error.response);
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

$(document).on("submit", "#search_replace_form", async (event) => {
  event.preventDefault();
  // Show confirmation dialog
  const confirmed = await Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, proceed!",
  });
  if (!confirmed.isConfirmed) {
    return; // Exit if the user cancels the action
  }
  debounceButton();
  try {
    const url = $("#search_replace_form").attr("action");
    const data = new FormData($("#search_replace_form")[0]);
    const response = await axios.post(url, data, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
    console.log(response);
    if (response.data.status) {
      toastr.success(response.data.message);
      $("#search_replace_form")[0].reset(); // Reset the form
      $("#searchAndReplaceModal").modal("hide"); // Close the modal
      location.reload();

      // window.location.href = response.data.redirect_to;
    } else {
      toastr.error(response.data.message);
    }

  } catch (error) {
    console.log(error.response);

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
