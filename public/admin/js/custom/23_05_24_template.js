$(document).ready(function () {
    $("#template_name").on("input", function () {
      const input = $(this).val();
      const sanitizedInput = input
        .replace(/[^\w\s]/gi, "")
        .replace(/\s+/g, "_")
        .toLowerCase();
      $(this).val(sanitizedInput);
      const count = sanitizedInput.length;
      $("#nameCharCount").text("Characters: " + count + " / 512");
    });
  
    $("#quick_reply").on("input", function () {
      var count = $(this).val().length;
      $("#buttonCharCount").text("Characters: " + count + " / 25");
    });
  
    function toggleHeaderSections(selectedValue) {
      // Hide all sections initially
      $(
        "#headerTextSection, #headerImageSection, #headerAudioSection, #headerVideoSection, #headerDocumentSection"
      ).hide();
      // $("#message-header").empty();
  
      // Show the appropriate section based on the selected value
      switch (selectedValue) {
        case "TEXT":
          $("#headerTextSection").show();
          break;
        case "IMAGE":
          $("#headerImageSection").show();
          break;
        case "AUDIO":
          $("#headerAudioSection").show();
          break;
        case "VIDEO":
          $("#headerVideoSection").show();
          break;
        case "DOCUMENT":
          $("#headerDocumentSection").show();
          break;
        default:
          // If 'none' or any other value, hide all sections
          break;
      }
    }
  
  
  
    // Function to detect variables or specific patterns in the header text
    function detectPatterns(text) {
      const regex = /{{\s*\d+\s*}}/g; // Regex to detect {{variable}} patterns
      const matches = text.match(regex); // Find all matches
      return matches || []; // Return the array of matches or an empty array
    }
    // Function to remove all but the first variable pattern
    function retainFirstVariable(text) {
      const patterns = detectPatterns(text);
      if (patterns.length > 1) {
        const firstPattern = patterns[0];
        const cleanedText = text.replace(regex, (match, index) => {
          return index === 0 ? match : "";
        });
        return cleanedText;
      }
      return text;
    }
  
    $(document).on("input", ".live_preview_header", function () {
      try {
          updateHeaderTransformedText();
      } catch (error) {
          console.error("An error occurred:", error);
      }
  });
  
  $(document).ready(function() {
      try {
          updateHeaderTransformedText();
      } catch (error) {
          console.error("An error occurred:", error);
      }
  });
  
  function updateHeaderTransformedText() {
    let new_header = $("#header_text").val();
    $(".live_preview_header").each(function () {
        const variableId = $(this).attr('id').split('_')[1];
        const variableValue = $(this).val();
        const variablePattern = new RegExp(`\\{\\{${variableId}\\}\\}`, 'g');
        if (variableValue.trim() !== '') {
            const simplifiedPattern = /\{\{1\}\}/g;
            new_header = new_header.replace(simplifiedPattern, variableValue);
        }
    });
    $("#message-header").html(new_header);
  }
  
    // Function to update the sample header content based on detected patterns
    function updateSampleHeaderContent(patterns) {
      const sampleHeader = $("#sample-header");
      const sampleHeaderContent = $("#sample-header-contant");
      if (patterns.length > 0) {
        sampleHeader.show();
        sampleHeaderContent.empty();
        if (patterns.length > 1) {
          toastr.error(
            "Only one variable is allowed in the header text. Extra variables are removed."
          );
        } 
        patterns.forEach((pattern) => {
          const inputField = `
              <div class="form-group">
                  <label for="pattern_${pattern}">Input for variable ${pattern}<span class="text-danger">*</span></label>
                  <input type="text" class="form-control live_preview_header"  id="pattern_${pattern}" name="header_variable[]" placeholder="Enter value for variable ${pattern}" required><div class="invalid-feedback text-danger"></div>
              </div>`;
          sampleHeaderContent.append(inputField); // Add the new input to the container
        });
      } else {
        sampleHeader.hide();
      }
    }
  
    $("#header_text")
      .on("input", function () {
        const newHeaderText = $(this).val();
        $("#message-header").text(newHeaderText);
        const count = newHeaderText.length;
        $("#headerCharCount").text("Characters: " + count + " / 60");
        let headerText = newHeaderText;
        headerText = retainFirstVariable(headerText);
        const detectedPatterns = detectPatterns(headerText);
        updateSampleHeaderContent(detectedPatterns);
        $(this).val(headerText);
      })
      .trigger("input");
  
      function transformText(text) {
        // Strikethrough for text like: ~Welcome~
        text = text.replace(/~(.*?)~/g, '<del>$1</del>');
          // Italic for text like: _Use code {{2}} at checkout._
        text = text.replace(/_(.*?)_/g, '<i>$1</i>');
          // Bold for text like: *Sale ends on 30th June 2024.*
        text = text.replace(/\*(.*?)\*/g, '<b>$1</b>');
          // Monospace for text like: ```Welcome```
        text = text.replace(/```(.*?)```/g, '<code>$1</code>');
          // Replace newlines with <br>
        text = text.replace(/\n/g, "<br>");
          return text;
    }
  
      // Function to detect variables in the message body
      function detectVariables(text) {
        const variablePattern = /\{\{(\d+)\}\}/g; // Regular expression for variables like {{1}}, {{2}}, etc.
        const matches = [];
        let match;
        while ((match = variablePattern.exec(text)) !== null) {
          if (!matches.includes(match[1])) {
            matches.push(match[1]);
          }
        }
        return matches; // Return array of variable numbers
      }
    
  
    $("#message_body").on("input", function () {
      const newMessageBody = $(this).val().replace(/\n/g, "<br>");
      const transformedText = transformText(newMessageBody);
      $("#_message_body_text").html(transformedText);
  
      const count = newMessageBody.length;
      $("#charCount").text(`Characters: ${count} / 1024`);
      const detectedVariables = detectVariables(newMessageBody);
      updateSampleBodyContent(detectedVariables);
    });
  
      function updateSampleBodyContent(variables) {
        const sampleBody = $("#sample-body"); 
        const sampleBodyContent = $("#sample-body-contant");
        if (variables.length > 0) {
          sampleBody.show();
          sampleBodyContent.empty();
          variables.forEach((variable) => {
            const inputField = `
                <div class="form-group">
                    <label for="variable_${variable}">Value for variable {{${variable}}}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control live_preview" id="variable_${variable}" name="body_variable[]" placeholder="Enter value for variable {{${variable}}}" required><div class="invalid-feedback text-danger"></div>
                </div>`;
            sampleBodyContent.append(inputField);
          });
        } else {
          sampleBody.hide();
        }
      }
  
  // Event delegation for input event on .live_preview elements
  $(document).on("input", ".live_preview", function () {
    updateTransformedText();
  });
  $(document).ready(function() {
    // Trigger updateTransformedText on page load
    updateTransformedText();
  });
  
  
  function updateTransformedText() {
    let newMessageBody = $("#message_body").val().replace(/\n/g, "<br>");
    let transformedText = transformText(newMessageBody);
    $(".live_preview").each(function () {
        const variableId = $(this).attr('id').split('_')[1];
        const variableValue = $(this).val();
        const variablePattern = new RegExp(`\\{\\{${variableId}\\}\\}`, 'g');
        if (variableValue.trim() !== '') {
            transformedText = transformedText.replace(variablePattern, variableValue);
        }
    });
    $("#_message_body_text").html(transformedText);
  }
  
    // $("#message_body").trigger("input"); // Ensure initial detection and updating
    function hasVariables(text) {
      const variablePattern = /\{\{.*?\}\}/g;
      return variablePattern.test(text);
    }
  
    function validateFooterText() {
      const footerText = $("#footer_text").val(); // Get the current footer text
      const notice = $("#footerVariableNotice"); // The notice to display if variables are detected
      if (hasVariables(footerText)) {
        $("#footer_text").val(""); // Clear the text if variables are found
        notice.show(); // Display the notice
      } else {
        notice.hide(); // Hide the notice if no variables are found
      }
    }
    $("#footer_text").on("input", function () {
      const newFooterText = $(this).val();
      $("#_footer_text").text(newFooterText);
      const count = newFooterText.length;
      $("#footerCharCount").text("Characters: " + count + " / 60");
      validateFooterText();
    });
    $("#footer_text").trigger("input");
  
    // Display a notice section
    $("<div>", {
      id: "footerVariableNotice", // ID for the notice
      class: "alert alert-warning", // Bootstrap alert class
      text: "Variables are not supported in the footer.", // Notice message
      style: "display:none;", // Initially hidden
    }).insertAfter("#footer_text"); // Insert after the footer text field
  
    // When the document is ready, set the correct initial state
    var initialSelectedValue = $("#header_type").val();
    toggleHeaderSections(initialSelectedValue);
  
    $("#header_type").on("change", function () {
      var selectedValue = $(this).val();
      toggleHeaderSections(selectedValue);
    });
  
    // Function to toggle sections based on the current button type
    function toggleButtonTypeSections(buttonType) {
      if (buttonType === "CTA") {
        $("#call-to-action-section").show();
        $("#quick_reply-section").hide(); // Hide quick reply section when CTA is selected
        $("#append-quick-reply").empty();
      } else if (buttonType === "QUICK_REPLY") {
        $("#call-to-action-section").hide();
        $("#quick_reply-section").show(); // Show quick reply section when quick reply is selected
        $("#append-button").empty();
      } else {
        $("#call-to-action-section").hide();
        $("#quick_reply-section").hide(); // Hide both if none is selected
        $("#append-button").empty();
        $("#append-quick-reply").empty();
      }
    }
    // var initialButtonType = $('input[name="button_type"]:checked').val();
    // toggleButtonTypeSections(initialButtonType);
    // $('input[name="button_type"]').on("change", function () {
    //   var selectedButtonType = $(this).val();
    //   toggleButtonTypeSections(selectedButtonType);
    // });
    // Event listener for removing a card
    // $(document).on("click", ".remove-card", function () {
    //   var card = $(this).closest(".card"); // Get the closest card
    //   var cardId = card.attr("id"); // Get the card's unique ID
    //   card.remove(); // Remove the card
    //   $("#" + cardId + "_preview").remove();
    // });
  
    // Initial update of "Add Button" options when the document is ready
  $(document).ready(function () {
    updateAddButtons();
  });
  
  
  // Function to enable or disable the "Add Button" option based on the maximum card count
  function toggleAddButton(actionType) {
    var maxCards = $("a[data-action='" + actionType + "']").data("max"); // Get the maximum number of cards allowed
    var cardCount = $("#append-button .card[data-action='" + actionType + "']").length; // Get the current card count for the given actionType
    var addButton = $("a[data-action='" + actionType + "']"); // Get the corresponding "Add Button" option
  
    if (cardCount >= maxCards) {
        addButton.addClass("disabled"); // Disable the "Add Button" option
    } else {
        addButton.removeClass("disabled"); // Enable the "Add Button" option
    }
  }
  
  function updateAddButtons() {
    // Loop through each action type
    ["visit_website", "call_phone_number", "copy_offer_code", "quick_reply"].forEach(function (actionType) {
        toggleAddButton(actionType); // Update the "Add Button" option for the current action type
    });
  }
  
    $(document).on("click", ".remove-card", function () {
      var card = $(this).closest(".card"); // Get the closest card
      var cardId = card.attr("id"); // Get the card's unique ID
      card.remove(); // Remove the card
      $("#" + cardId + "_preview").remove();
  
      // Enable the corresponding "Add Button" option if the max limit is not reached
      var actionType = card.data("action");
      var maxAllowed = $(".add_call_to_action[data-action='" + actionType + "']").data("max");
      var cardCount = $("#append-button .card[data-action='" + actionType + "']").length;
  
      if (cardCount < maxAllowed) {
          $(".add_call_to_action[data-action='" + actionType + "']").removeClass("disabled");
      }
      updateAddButtons(); // Update the "Add Button" options after removing a card
  
  });
  
    let cardIdCounter = 0;
    function generateUniqueId() {
      return Math.random().toString(36).substr(2, 9); // Create a unique ID with random numbers and letters
    }
  
  
  $(".add_call_to_action").on("click", function () {
      var cardCount = $("#append-button .card").length; // Get the current card count
      var maxCards = 10; // Define the maximum number of cards allowed
      if (cardCount >= maxCards) {
         toastr.error("You can only add up to 10 buttons in total.");
         return;
      }
      cardIdCounter++;
      var uniqueId = generateUniqueId(); // Create a unique identifier for this card
      // const uniqueId = "card_" + cardIdCounter;
      var actionType = $(this).data("action");
      var maxButtonsAllowed = parseInt($(this).data("max"));
      var currentButtonCount = $("#append-button .c-card[data-action='" + actionType + "']").length;
      if (currentButtonCount >= maxButtonsAllowed) {
         toastr.error("You have reached the maximum limit for adding " + actionType.replace(/_/g, ' ') + " buttons.");
         return;
      }
      var content = "";
      if (actionType === "visit_website") {
         content = `<div class="card mt-2 c-card" data-action="visit_website" id="${uniqueId}">
         <div class="card-body">
             <div class="row">
                 <div class="col-xl-3 col-lg-6 col-sm-6">
                 <div class="bar-icon d-inline-flex"><i class="las la-bars"></i></div>
                 <div class="type_of_action">
                 <label for="type_of_action" class="d-block">Type of Action<span class="text-danger">*</span></label>
                     <select name="type_of_action[]" id="type_of_action" class="form-select" required>
                         <option value="URL">Visit Website</option>
                     </select><div class="invalid-feedback text-danger"></div>
                 </div>
                 </div>
                 <div class="col-xl-3 col-lg-6 col-sm-6">
                     <label for="button_text" class="d-block">Button Text<span class="text-danger">*</span></label>
                     <input type="text" class="form-control button_text_input" name="button_text[]" placeholder="Enter button text" maxlength="20"  required><div class="invalid-feedback text-danger"></div>
                 </div>
                 <div class="col-xl-5 col-lg-10 col-sm-10">
                     <label for="website_url" class="d-block">Website URL<span class="text-danger">*</span></label>
                     <input type="url" class="form-control" name="button_value[]" placeholder="Enter website URL" maxlength="2000" required><div class="invalid-feedback text-danger"></div>
                 </div>
                 <div class="col-xl-1 col-lg-2 col-sm-2 text-end mt-4">
                     <button type="button" class="btn btn-danger text-white remove-card"><i class="las la-trash"></i></button>
                 </div>
             </div>
         </div>
     </div>`;
      } else if (actionType === "call_phone_number") {
         content = `<div class="card mt-2 c-card" data-action="call_phone_number" id="${uniqueId}">
         <div class="card-body">
             <div class="row">
                 <div class="col-xl-3 col-lg-6 col-sm-6">
                 <div class="bar-icon d-inline-flex"><i class="las la-bars"></i></div>
                 <div class="type_of_action">
                     <label for="type_of_action" class="d-block">Type of Action<span class="text-danger">*</span></label>
                     <select name="type_of_action[]" id="type_of_action" class="form-select" required>
                         <option value="PHONE_NUMBER">Call Phone Number</option>
                     </select><div class="invalid-feedback text-danger"></div>
                 </div>
                 </div>
                 <div class="col-xl-3 col-lg-6 col-sm-6">
                     <label for="button_text" class="d-block">Button Text<span class="text-danger">*</span></label>
                     <input type="text" class="form-control button_text_input" name="button_text[]" placeholder="Enter button text" maxlength="20" required><div class="invalid-feedback text-danger"></div>
                 </div>
                 <div class="col-xl-5 col-lg-10 col-sm-10">
                     <label for="phone_number" class="d-block">Phone Number<span class="text-danger">*</span></label>
                     <input type="text" class="form-control" name="button_value[]" placeholder="Enter phone number" maxlength="20" required><div class="invalid-feedback text-danger"></div>
                 </div>
                 <div class="col-xl-1 col-lg-2 col-sm-2 text-end mt-4">
                     <button type="button" class="btn btn-danger text-white remove-card"><i class="las la-trash"></i></button>
                 </div>
             </div>
         </div>
     </div>`;
      } else if (actionType === "copy_offer_code") {
         content = `<div class="card mt-2 c-card" data-action="copy_offer_code" id="${uniqueId}">
         <div class="card-body">
             <div class="row">
                 <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12">
                 <div class="bar-icon d-inline-flex"><i class="las la-bars"></i></div>
                 <div class="type_of_action">
                     <label for="type_of_action" class="d-block">Type of Action<span class="text-danger">*</span></label>
                     <select name="type_of_action[]" id="type_of_action" class="form-select" required>
                         <option value="COPY_CODE">Copy Offer Code</option>
                     </select><div class="invalid-feedback text-danger"></div>
                     <input type="hidden" class="form-control button_text_input" name="button_text[]" placeholder="Enter button text" maxlength="20">
                 </div>
                 </div>
                 <div class="col-xl-8 col-lg-6 col-md-6 col-sm-10">
                     <label for="button_text" class="d-block">Button Text<span class="text-danger">*</span></label>
                     <input type="text" class="form-control button_text_input" name="button_value[]" placeholder="Enter button text"><div class="invalid-feedback text-danger"></div>
                 </div>
                 <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 text-end mt-4 ">
                     <button type="button" class="btn btn-danger text-white remove-card"><i class="las la-trash"></i></button>
                 </div>
             </div>
         </div>`;
      } else if (actionType === "quick_reply") {
         content = `<div class="card mt-2 c-card" id="${uniqueId}">
         <div class="card-body">
             <div class="row">
             <div class="col">
             <div class="bar-icon d-inline-flex"><i class="las la-bars"></i></div>
             <div class="type_of_action">
             <input type="hidden" class="form-control button_text_input" name="button_value[]" placeholder="Enter button text">
             <label for="type_of_action" class="d-block sr-only">Type of Action<span class="text-danger">*</span></label>
                     <select name="type_of_action[]" id="type_of_action" class="form-select" required hidden>
                         <option value="QUICK_REPLY">Quick Reply</option>
                     </select><div class="invalid-feedback text-danger"></div>
  
             <label for="button_text" class="d-block">Button Text<span class="text-danger">*</span></label>
             <input type="text" class="form-control button_text_input" name="button_text[]" placeholder="Enter button text" required>
             </div>
             </div>
                 <div class="col-xl-1 col-lg-2 col-sm-2 text-end mt-4">
                     <button type="button" class="btn btn-danger text-white remove-card"><i class="las la-trash"></i></button>
                 </div>
             </div>
         </div>
     </div>`;
      }
  
      $("#append-button").append(content);
      var buttonContainer = $("#_footer_btn .tmp-btn-list");
      if (buttonContainer.length === 0) {
         $("#_footer_btn").append(
            `<div class="tmp-btn-list text-center mt-2"></div>`
         );
         buttonContainer = $("#_footer_btn .tmp-btn-list"); // Reassign after creating
      }
      var buttonPreview = "";
      switch (actionType) {
         case "visit_website":
            buttonPreview = `<button class="btn btn-template w-100 border-top" data-action="${actionType}" data-max="2" id="${uniqueId}_preview"><i class="las la-external-link-alt"></i> Read More</button>`;
            break;
         case "call_phone_number":
            buttonPreview = `<button class="btn btn-template w-100 border-top" data-action="${actionType}" data-max="1" id="${uniqueId}_preview"><i class="las la-phone"></i> Call Us</button>`;
            break;
         case "copy_offer_code":
            buttonPreview = `<button class="btn btn-template w-100 border-top" data-action="${actionType}" data-max="1" id="${uniqueId}_preview"><i class="las la-copy"></i> Copy Code</button>`;
            break;
         case "quick_reply":
            buttonPreview = `<button class="btn btn-template w-100 border-top" data-action="${actionType}" data-max="10" id="${uniqueId}_preview"><i class="las la-reply"></i>Quick Reply</button>`;
            break;
      }
  
      buttonContainer.append(buttonPreview);
      if (currentButtonCount + 1 >= maxButtonsAllowed) {
         $(this).addClass("disabled");
      }
      if (cardCount + 1 >= maxCards) {
         $(".add_call_to_action").addClass("disabled");
      }
      initializeSortable();
      // updateAddButtons();
   });
  
  
    // Event listener for input in button_text[] to update the corresponding preview
    $(document).on("input", "input[name='button_text[]']", function () {
      var cardId = $(this).closest(".card").attr("id"); // Get the card's unique ID
      var buttonText = $(this).val(); // Get the text value
      var previewButton = $(`#${cardId}_preview`); // Find the preview button
      var icon = previewButton.find("i"); // Find the icon element
      previewButton
        .contents()
        .filter(function () {
          return this.nodeType === Node.TEXT_NODE;
        })
        .remove(); // Remove all text nodes
      previewButton.append(` ${buttonText}`); // Append the new text after the icon
    });
  
    // Function to set the required attribute based on the selected header type
    function updateRequiredAttribute(headerType) {
      // Remove required attribute from all header fields initially
      $(
        "#header_text, #header_image, #header_video, #header_audio, #header_document"
      ).removeAttr("required");
      // Add the required attribute to the specific field based on the selected header type
      switch (headerType) {
        case "TEXT":
          $("#header_text").attr("required", true);
          break;
        case "IMAGE":
          $("#header_image").attr("required", true);
          break;
        case "VIDEO":
          $("#header_video").attr("required", true);
          break;
        case "AUDIO":
          $("#header_audio").attr("required", true);
          break;
        case "DOCUMENT":
          $("#header_document").attr("required", true);
          break;
        default:
          // No header or unknown type, no required attributes
          break;
      }
    }
    // Event listener for changes in the header type
    $("#header_type").on("change", function () {
      $("#message-header").html("");
      $(
        "#header_image, #header_video, #header_audio, #header_document, #header_text"
      ).val("");
      const selectedHeaderType = $(this).val();
      updateRequiredAttribute(selectedHeaderType);
    });
    // Call the function initially in case of default or pre-selected value
    updateRequiredAttribute($("#header_type").val()); // Ensure correct setup on page load
    // Event listener for changes in the image file input
    function handleFileChange(
      input,
      validationMessage,
      allowedTypes,
      maxSizeMB,
      displayFunction
    ) {
      const fileInput = input[0];
      if (!fileInput.files.length) {
        validationMessage.text(
          `Please upload a ${allowedTypes.join(" or ")} file.`
        );
        return;
      }
      const file = fileInput.files[0];
      if (!allowedTypes.includes(file.type)) {
        validationMessage.text(
          `Only ${allowedTypes.join(", ")} files are allowed.`
        );
        return;
      }
      const fileSizeMB = file.size / (1024 * 1024); // Convert bytes to MB
      if (fileSizeMB > maxSizeMB) {
        validationMessage.text(`The file must be ${maxSizeMB} MB or smaller.`);
        return;
      }
      const reader = new FileReader();
      reader.onload = function (e) {
        displayFunction(e.target.result);
      };
      if (file) {
        reader.readAsDataURL(file);
      }
      validationMessage.text("");
    }
  
    $("#header_image").change(function () {
      handleFileChange(
        $(this),
        $("#imageValidationMessage"),
        ["image/jpeg", "image/png", "image/gif"],
        5,
        function (result) {
          const img = $("<img>").attr("src", result);
          $("#message-header").empty().append(img);
        }
      );
    });
  
    $("#header_video").change(function () {
      handleFileChange(
        $(this),
        $("#validationMessage"),
        ["video/mp4", "video/avi", "video/mov", "video/wmv"],
        16,
        function (result) {
          const video = $("<video controls>").attr("src", result);
          $("#message-header").empty().append(video);
        }
      );
    });
  
    $("#header_audio").change(function () {
      handleFileChange(
        $(this),
        $("#audioValidationMessage"),
        ["audio/mpeg", "audio/wav", "audio/ogg"],
        10,
        function (result) {
          const audio = $("<audio controls>").attr("src", result);
          $("#message-header").empty().append(audio);
        }
      );
    });
  
    $("#header_document").change(function () {
      handleFileChange(
        $(this),
        $("#pdfValidationMessage"),
        ["application/pdf"],
        10,
        function (result) {
          const pdf = $("<iframe>")
            .attr("src", result)
            .attr("width", "100%")
            .attr("height", "400px");
          $("#message-header").empty().append(pdf);
        }
      );
    });
    // Update the button text
    $("#button_text").on("input", function () {
      var newButtonText = $(this).val();
      $("#_button_text").text(newButtonText);
    });
  });
  
  $(document).on("submit", "#whatsapp-template-form", async (event) => {
    event.preventDefault();
    debounceButton1();
    try {
      const url = $("#whatsapp-template-form").attr("action");
      const data = new FormData($("#whatsapp-template-form")[0]);
      const response = await axios.post(url, data, {
        processData: false,
        contentType: false,
      });
      console.log(response);
      if (response.data.status) {
        toastr.success(response.data.message);
        $("#whatsapp-template-form")[0].reset(); // Reset the form
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
      debounceButton1();
    }
  });
  
  $(document).on("submit", "#whatsapp-template-update", async (event) => {
    event.preventDefault();
    debounceButton1();
    try {
      const url = $("#whatsapp-template-update").attr("action");
      const data = new FormData($("#whatsapp-template-update")[0]);
      const response = await axios.post(url, data, {
        processData: false,
        contentType: false,
      });
      console.log(response);
      if (response.data.status) {
        toastr.success(response.data.message);
        $("#whatsapp-template-update")[0].reset(); // Reset the form
        window.location.href = response.data.redirect_to;
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
      debounceButton1();
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
              response.data.status == true ? "deleted" : "error",
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
  /* Time */
  var deviceTime = $(".status-bar .time");
  var messageTime = $(".message .time");
  
  function updateTime() {
    deviceTime.text(moment().format("h:mm"));
  }
  updateTime();
  setInterval(updateTime, 1000);
  messageTime.text(moment().format("h:mm A"));
  const debounceButton1 = () => {
    let preloader = $("#preloader");
    let saveButton = $(".save");
  
    if (preloader.hasClass("d-none")) {
      preloader.removeClass("d-none");
      saveButton.addClass("d-none");
    } else {
      preloader.addClass("d-none");
      saveButton.removeClass("d-none");
    }
  };
  
  function initializeSortable() {
    var sortable = new Sortable(
      document.getElementById("append-button"),
      {
        animation: 150,
        onUpdate: function (evt) {
          var item = evt.item;
          var items = sortable.toArray();
        },
      }
    );
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
  
  