$ = jQuery;
jQuery(document).ready(function ($) {
  $("#membership-form").on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    console.log("ajax_object.ajax_url", ajax_object.ajax_url);
    console.log("Form Data:", Array.from(formData.entries()));

    $.ajax({
      type: "POST",
      url: ajax_object.ajax_url + "?action=handle_membership_form",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (response.success) {
          window.location.href = response.data.redirect;
        }
        if (response.success) {
          $.toast({
            heading: "Success",
            text: response.data,
            icon: "success",
            position: "bottom-right",
            stack: false,
            hideAfter: 3000,
          });
          $("#membership-form")[0].reset();
        } else {
          $.toast({
            heading: "Error",
            text: response.data,
            icon: "error",
            position: "top-right",
            stack: false,
            hideAfter: 3000,
          });
        }
      },
      error: function (xhr, status, error) {
        $.toast({
          heading: "Error",
          text: "An error occurred: " + error,
          icon: "error",
          position: "bottom-right",
          stack: false,
          hideAfter: 3000,
        });
      },
    });
  });
});
