$(document).on('click', '.__js_qr_download', async function() {
  var dataValue = $(this).data('text');  // Retrieve data-text value
  console.log(dataValue);

  // Try using the Clipboard API
  try {
      await navigator.clipboard.writeText(dataValue);
      toastr.success("Copied");
  } catch (err) {
      toastr.error("Failed to copy text: " + err);
  }
});


$(document).on("submit", "#addChatWidgetForm", async (event) => {
  event.preventDefault();
  bounceButton("preloader", "save");
  try {
    const url = $("#addChatWidgetForm").attr("action");
    const data = new FormData($("#addChatWidgetForm")[0]);
    const response = await axios.post(url, data, {
      processData: false,
      contentType: false,
    });
    console.log(response);
    if (response.data.status) {
      toastr.success(response.data.message);
      $("#addChatWidgetForm")[0].reset(); // Reset the form
      window.location.href = response.data.redirect_to;
    } else {
      toastr.error(response.data.message);
    }
    // location.reload();
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
    bounceButton("preloader", "save");
  }
});
$(document).on("submit", "#update-chatwidget", async (event) => {
  event.preventDefault();
  bounceButton("preloader", "save");
  try {
    const url = $("#update-chatwidget").attr("action");
    const data = new FormData($("#update-chatwidget")[0]);
    const response = await axios.post(url, data, {
      processData: false,
      contentType: false,
    });
    console.log(response);
    if (response.data.status) {
      toastr.success(response.data.message);
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
    bounceButton("preloader", "save");
  }
});
$(document).on("submit", "#addChatWidgetContactForm", async (event) => {
  event.preventDefault();
  bounceButton("preloader", "save");

  try {
    const url = $("#addChatWidgetContactForm").attr("action");
    const data = new FormData($("#addChatWidgetContactForm")[0]);
    const response = await axios.post(url, data, {
      processData: false,
      contentType: false,
    });
    console.log(response);
    if (response.data.status) {
      toastr.success(response.data.message);
      $("#addChatWidgetContactForm")[0].reset(); // Reset the form
      $("#addChatWidgetContact").modal("hide");
      $("#append_contact").html(response.data.data);
      // window.location.href = response.data.redirect_to;
      reinitializeSortable();
    } else {
      toastr.error(response.data.message);
    }
    // location.reload();
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
    bounceButton("preloader", "save");
  }
});
$(document).on("submit", "#updateContactForm", async (event) => {
  event.preventDefault();
  bounceButton("preloader", "save");

  try {
    const url = $("#updateContactForm").attr("action");
    const data = new FormData($("#updateContactForm")[0]);
    const response = await axios.post(url, data, {
      processData: false,
      contentType: false,
    });
    console.log(response);
    if (response.data.status) {
      toastr.success(response.data.message);
      $("#updateContactForm")[0].reset(); // Reset the form
      $("#editContactModal").modal("hide");
      $("#append_contact").html(response.data.data);
      // window.location.href = response.data.redirect_to;
      reinitializeSortable();
    } else {
      toastr.error(response.data.message);
    }
    // location.reload();
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
    bounceButton("preloader", "save");
  }
});

$(document).on("submit", "#update-button", async (event) => {
  event.preventDefault();
  bounceButton("preloader", "save");
  try {
    const url = $("#update-button").attr("action");
    const data = new FormData($("#update-button")[0]);
    const response = await axios.post(url, data, {
      processData: false,
      contentType: false,
    });
    console.log(response);
    if (response.data.status) {
      toastr.success(response.data.message);
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
    bounceButton("preloader", "save");
  }
});

$(document).on("submit", "#update-settings", async (event) => {
  event.preventDefault();
  bounceButton("preloader", "save");
  try {
    const url = $("#update-settings").attr("action");
    const data = new FormData($("#update-settings")[0]);
    const response = await axios.post(url, data, {
      processData: false,
      contentType: false,
    });
    console.log(response);
    if (response.data.status) {
      toastr.success(response.data.message);
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
    bounceButton("preloader", "save");
  }
});

$(document).on("submit", "#update-box", async (event) => {
  event.preventDefault();
  bounceButton("preloader", "save");
  try {
    const url = $("#update-box").attr("action");
    const data = new FormData($("#update-box")[0]);
    const response = await axios.post(url, data, {
      processData: false,
      contentType: false,
    });
    console.log(response);
    if (response.data.status) {
      toastr.success(response.data.message);
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
    bounceButton("preloader", "save");
  }
});
$(document).on("click", ".__js_delete", function () {
  confirmationAlert($(this).data("url"), $(this).data("id"), "Yes, Delete It!");
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
            "deleted",
            response.data.status == true ? "success" : "error"
          );
          $("#append_contact").html(response.data.data);
          reinitializeSortable();
          // Swal.fire(...response.data.msg);
        })
        .catch((error) => {
          Swal.fire(...error.response.data);
          refreshDataTable();
        });
    }
  });
};

$(document).on("click", ".__js_edit", function () {
  const url = $(this).data("url");
  axios
    .get(url, {
      params: {
        id: $(this).data("id"),
      },
    })
    .then((response) => {
      console.log(response);
      $("#edit_contact_body").html(response.data.data);
      $("#editContactModal").modal("show");
    })
    .catch((error) => {
      toastr.error(error.message);
    });
});

$(document).on("click", ".__js_get_embed_code", function () {
  const url = $(this).data("url");
  axios
    .get(url, {
      params: {
        id: $(this).data("id"),
      },
    })
    .then((response) => {
      console.log(response);
      $("#embade_code_body").html(response.data.data);
      $("#embadCodeModal").modal("show");
      initCodeCopy();
    })
    .catch((error) => {
      toastr.error(error.message);
    });
});
$(document).on("click", ".__js_reset_settings", function () {
  const url = $(this).data("url");
  Swal.fire({
    title: "Are you sure?",
    icon: "warning",
    showCancelButton: true,
  }).then((confirmed) => {
    if (confirmed.isConfirmed) {
      axios
        .post(url, {
          params: {
            id: $(this).data("id"),
          },
        })
        .then((response) => {
          Swal.fire(
            response.data.message,
            "",
            response.data.status == true ? "success" : "error"
          );
          refreshDataTable();
        })
        .catch((error) => {
          toastr.error(error.message);
          refreshDataTable();
        });
    }
  });
});

$(document).on("click", ".__js_update_status", function () {
  confirmationUpdateAlert(
    $(this).data("url"),
    $(this).data("status"),
    "Yes, Update It!"
  );
});

const confirmationUpdateAlert = (
  url,
  status,
  button_test = "Yes, Confirmed it!"
) => {
  Swal.fire({
    title: "Are you sure?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: button_test,
  }).then((confirmed) => {
    if (confirmed.isConfirmed) {
      axios
        .post(url, {
          status: status,
        })
        .then((response) => {
          refreshDataTable();
          Swal.fire(
            response.data.message,
            "",
            response.data.status == true ? "success" : "error"
          );
        })
        .catch((error) => {
          refreshDataTable();
        });
    }
  });
};

const refreshDataTable = () => {
  $("#dataTableBuilder").DataTable().ajax.reload();
};

const bounceButton = (preloaderClass, saveButtonClass) => {
  let preloader = $(`.${preloaderClass}`);
  let saveButton = $(`.${saveButtonClass}`);

  if (preloader.hasClass("d-none")) {
    preloader.removeClass("d-none");
    saveButton.addClass("d-none");
  } else {
    preloader.addClass("d-none");
    saveButton.removeClass("d-none");
  }
};

$(".copy-text").click(function () {
  var inputField = $(this).closest(".input-group").find("input");
  inputField.select();
  document.execCommand("copy");
  toastr.success("Copied");
});

function initializeSortable() {
  var sortable = new Sortable(document.getElementById("sortable-body"), {
    animation: 150,
    onUpdate: function (evt) {
      var item = evt.item;
      var items = sortable.toArray();
      $.ajax({
        url: url,
        type: "POST",
        headers: {
          "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
        data: {
          order: items,
        },
        success: function (response) {
          console.log(response);
        },
        error: function (xhr) {
          console.log(xhr.responseText);
        },
      });
    },
  });
}

// Reinitialize SortableJS after appending new contact data
function reinitializeSortable() {
  if (typeof Sortable !== "undefined") {
    initializeSortable();
  }
}

// Initialize SortableJS on page load
document.addEventListener("DOMContentLoaded", function () {
  initializeSortable();
});
