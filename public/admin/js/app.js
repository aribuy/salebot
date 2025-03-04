/*-----------------------------------------------------------------------------------
    Template Name: SG Admin Dashboard
    Template URI: https://spagreen.net/sg-dashboard
    Author: SpagreenCreative
    Author URI:  https://spagreen.net
    Version: 1.0

    Note: This is Main JS file
-----------------------------------------------------------------------------------
    Js INDEX
    ===================
    #. 01. Sidebar Nav
    #. 02. Settings Tools Nav
    #. 03. Choices Select Field
    #. 04. Editor
    #. 05. File Uploader Dropzone
    #. 06. Password show hide
-----------------------------------------------------------------------------------*/
const meta_token = document.head.querySelector('meta[name="csrf-token"]');
let token = "";
if (meta_token) {
    token = meta_token.content;
}
let single_choice = "";
let section = 0;
let base_url = $(".base_url").val();
let modal_id = "";

(function ($) {
    "use strict";
    // 01. Sidebar Nav
    $(document).ready(function () {
        $(document).on('click',".bottom-icon.picker",function(e){
            e.preventDefault();
            $(".emoji_div").fadeIn(200,function(){$(this).focus();});
        });
        $(document).on('click',".gear-icon",function(e){
            $('.flow-side-nav').toggleClass('sidenavopen');
        });
        $(document).on('blur',".emoji_div",function(){
            $(this).fadeOut(200);
        });
        // $(document).on('click',".bottom-icon.button-saved",function(e){
        //     e.preventDefault();
        //     $("#save__item").fadeIn(200,function(){$(this).focus();});
        // });
        // $(document).on('click',".bottom-icon.robot__replay",function(e){
        //     e.preventDefault();
        //     $("#robot__replay").fadeIn(200,function(){$(this).focus();});
        // });
        // $(document).on('click',".bottom-icon.ai__replay",function(e){
        //     e.preventDefault();
        //     $("#ai__replay").fadeIn(200,function(){$(this).focus();});
        // });

         $(document).on('click',".dropDown__icon",function(e){
            e.preventDefault();
            $('.bottom-icons').toggleClass('active');
        });

        $(document).on("click", function (e) {
            if ($(e.target).closest(".new-chat-form").length === 0 && $(e.target).closest(".dropDown__icon").length === 0) {
                $(".bottom-icons").removeClass("active");
            }
        });

        // $(document).on("click", function (e) {
        //     if ($(e.target).closest(".chat_popupBox").length === 0 && $(e.target).closest(".savad-item-area.show-item").length === 0) {
        //         $(".savad-item-area.show-item").fadeOut(200);
        //     }
        // });


        // $(document).on('blur',".savad-item-area.show-item",function(){
        //     $(this).fadeOut(200);
        // });

        $(document).on('click',".canned_li",function(){
            $('.savad-item-area.show-item').fadeOut(200);
        });
        $(document).on('click',".sidebar_toggler",function(){
            $(".navbar-dark-v1").toggleClass("d-none");
        });
        $(document).on("click", ".copy_text", function () {
            let text = $(this).data("text");
            copyText(text);
        });
        $(document).on("change", "#lang", function () {
            $(this).closest("form").submit();
        });

        $(document).on('click',".chat-customtoggle",function(){
            $(".chatpage-wrapper").addClass("active shadow");
        });

        $(document).on('click',".close__flyout",function(){
            $(".chatpage-wrapper").removeClass("active shadow");
        });

        $(document).on("click", function (e) {
            if ($(e.target).closest(".sp-left-sidebar").length === 0 && $(e.target).closest(".chat-customtoggle, .close__flyout").length === 0) {
                $(".chatpage-wrapper").removeClass("active shadow");
            }
        });

        // $(document).on('click',"#bot_popup",function(e){
        //     e.preventDefault();
        //     $(".bot_popup").addClass('sp-modal');
        // });
        // $(document).on('click',".btn_close",function(e){
        //     e.preventDefault();
        //     $(".bot_popup").removeClass('sp-modal');
        // });

        // $(document).on('click',"#ai_popup",function(e){
        //     e.preventDefault();
        //     $(".ai_popup").addClass('sp-modal');
        // });
        // $(document).on('click',".btn_close",function(e){
        //     e.preventDefault();
        //     $(".ai_popup").removeClass('sp-modal');
        // });

        // $(document).on("click", function (e) {
        //     if ($(e.target).closest(".chat_popupBox").length === 0 && $(e.target).closest(".btn_close").length === 0) {
        //         $(".ai_popup").removeClass("sp-modal");
        //     }
        // });
        
    // $(function() {
    //     var footerHeight = $('.sp-static-bar.chat__sendArea').innerHeight();
    //     console.log('Footer Height:', footerHeight);
    //     $('.sp-main-wrapper-content').css('height', `calc(100% - ${footerHeight}px)`);
    // });


    // Chat page replay js
    $(document).on('click',"#action__replay",function(e){
        e.preventDefault();
        $("#msg__replay").slideToggle('');
    });
    $(document).on('click',"#close__action",function(e){
        e.preventDefault();
        $("#msg__replay").slideToggle('');
    });


    /*********************************
    /*   Select2 Start
    *********************************/
    if ($(".dropdown-select-item").length > 0) {
        // $(".dropdown-select-item").select2();
    }


        $(document).on("click", ".status-change", function (e) {
            let selector = $(this);
            var value = selector.val().split("/");
            let field_for = selector.data("field_for");
            var url = base_url + "/admin/" + value[0];

            if (field_for == "maintenance_mode") {
                if (selector.is(":checked")) {
                    e.preventDefault();
                    return $("#maintenance_mode").modal("show");
                } else {
                    $(".warning_text").addClass("d-none");
                }
            }

            if (field_for == "image_optimization") {
                if (selector.is(":checked")) {
                    $(".optimization_div").removeClass("d-none");
                } else {
                    $(".optimization_div").addClass("d-none");
                }
            }
            var id = selector.data("id");
            if ($(this).is(":checked")) {
                var status = 1;
            } else {
                var status = 0;
            }

            let form = {
                id: id,
                status: status,
                data: {
                    name: value[1],
                    value: status,
                },
            };

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": token,
                },
                url: url,
                method: "POST",
                data: form,
                success: function (response) {
                    toastr.clear();
                    if (
                        response.status == 200 ||
                        response.status == "success"
                    ) {
                        if (response.reload) {
                            toastr["success"](response.message);
                            location.reload();
                        } else {
                            toastr["success"](response.message);
                        }
                    } else {
                        selector.prop("checked", !status);
                        toastr["error"](response.message);
                    }
                },
                error: function (response) {
                    selector.prop("checked", !status);
                    toastr["error"](response.message);
                },
            });
        });

        // change published status

        $(document).on("click", ".pubished_status", function (e) {
            let selector = $(this);
            var value = selector.val().split("/");
            let field_for = selector.data("field_for");
            var url = base_url + "/admin/" + value[0];

            if (field_for == "maintenance_mode") {
                if (selector.is(":checked")) {
                    e.preventDefault();
                    return $("#maintenance_mode").modal("show");
                } else {
                    $(".warning_text").addClass("d-none");
                }
            }

            if (field_for == "image_optimization") {
                if (selector.is(":checked")) {
                    $(".optimization_div").removeClass("d-none");
                } else {
                    $(".optimization_div").addClass("d-none");
                }
            }
            var id = selector.data("id");
            if ($(this).is(":checked")) {
                var status = 1;
            } else {
                var status = 0;
            }

            let form = {
                id: id,
                status: status,
                data: {
                    name: value[1],
                    value: status,
                },
            };

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": token,
                },
                url: url,
                method: "POST",
                data: form,
                success: function (response) {
                    toastr.clear();

                    $("#dataTableBuilder").DataTable().ajax.reload();

                    if (
                        response.status == 200 ||
                        response.status == "success"
                    ) {
                        if (response.reload) {
                            toastr["success"](response.message);
                            location.reload();
                        } else {
                            toastr["success"](response.message);
                        }
                    } else {
                        selector.prop("checked", !status);
                        toastr["error"](response.message);
                    }
                },
                error: function (response) {
                    selector.prop("checked", !status);
                    toastr["error"](response.message);
                },
            });
        });

        // change published status

        //insturctor status change script
        $(document).on("click", ".instructor-status-change", function (e) {
            let selector = $(this);
            var value = selector.val().split("/");
            let field_for = selector.data("field_for");
            var url = base_url + "/organization/" + value[0];

            if (field_for == "maintenance_mode") {
                if (selector.is(":checked")) {
                    e.preventDefault();
                    return $("#maintenance_mode").modal("show");
                } else {
                    $(".warning_text").addClass("d-none");
                }
            }

            if (field_for == "image_optimization") {
                if (selector.is(":checked")) {
                    $(".optimization_div").removeClass("d-none");
                } else {
                    $(".optimization_div").addClass("d-none");
                }
            }
            var id = selector.data("id");
            if ($(this).is(":checked")) {
                var status = 1;
            } else {
                var status = 0;
            }

            let form = {
                id: id,
                status: status,
                data: {
                    name: value[1],
                    value: status,
                },
            };

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": token,
                },
                url: url,
                method: "POST",
                data: form,
                success: function (response) {
                    toastr.clear();
                    if (
                        response.status == 200 ||
                        response.status == "success"
                    ) {
                        if (response.reload) {
                            toastr["success"](response.message);
                            location.reload();
                        } else {
                            toastr["success"](response.message);
                        }
                    } else {
                        selector.prop("checked", !status);
                        toastr["error"](response.message);
                    }
                },
                error: function (response) {
                    selector.prop("checked", !status);
                    toastr["error"](response.message);
                },
            });
        });

        let sortable = document.getElementById("homepageContent");
        if (sortable) {
            new Sortable(sortable, {
                animation: 150,
            });
        }

        const popoverTriggerList = document.querySelectorAll(
            '[data-bs-toggle="popover"]'
        );
        const popoverList = [...popoverTriggerList].map(
            (popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl)
        );

        $("body").on("click", function (e) {
            //did not click a popover toggle or popover
            if (
                $(e.target).data("toggle") !== "popover" &&
                $(e.target).parents(".popover.in").length === 0
            ) {
                $('[data-bs-toggle="popover"]').popover("hide");
            }
        });

        //sidebar scrolling
        var activeMenuItem = $(".side-nav ul .active");
        if (activeMenuItem.length > 0) {
            var sidebarHeight = $(".side-nav").height();
            var menuItemOffset = activeMenuItem.position().top;
            var scrollPosition = menuItemOffset - sidebarHeight / 2;

            $(".side-nav").scrollTop(scrollPosition);
        }

        $(document).on("click", ".builder", function () {
            section++;
            let target_id = $(this).data("target_id");
            let name = $(this).data("name");
            let type = $(this).data("var_type");
            let accordion = $(".modal").find("#" + target_id);

            let selector = accordion.clone().appendTo("#homepageContent");

            let id = target_id + "_section_" + section;

            let button = selector.find("button");

            if (button.length) {
                button.attr("data-bs-target", "#" + id);
            }
            selector.find(".accordion-collapse").attr("id", id);

            selector.find("*").each(function () {
                if ($(this).attr("name")) {
                    if ($(this).is("input") || $(this).is("textarea")) {
                        let type = $(this).data("type");
                        $(this).prop(
                            "name",
                            "builder[" +
                                name +
                                "_" +
                                section +
                                "][" +
                                type +
                                "]"
                        );
                    } else {
                        $(this).prop(
                            "name",
                            "builder[" +
                                name +
                                "_" +
                                section +
                                "]" +
                                (type == "array" ? "[ids][]" : "") +
                                ""
                        );
                    }
                    return true;
                }
            });

            window.scrollTo({
                behavior: "smooth",
                top: document.body.scrollHeight,
            });
        });

        $(document).on("click", ".accordion-header", function () {
            let id = $(this).attr("id");
            if (
                $(this)
                    .find(".form-select")
                    .hasClass("select2-hidden-accessible")
            ) {
                $(this).find(".form-select").select2("destroy");
            }
            if (id == "instructor") {
                searchInstructor(
                    $(this).closest(".accordion-item").find(".form-select")
                );
            } else if (id == "featuredCourse" || id == "singleCourse") {
                searchCourse(
                    $(this).closest(".accordion-item").find(".form-select")
                );
            } else if (id == "subjects") {
                searchSubjects(
                    $(this).closest(".accordion-item").find(".form-select")
                );
            } else if (id == "mentor") {
                searchLessons(
                    $(this).closest(".accordion-item").find(".form-select")
                );
            }
        });

        $(document).on("click", ".homepage-content .delete-icon", function () {
            $(this).closest(".accordion-item").remove();
        });
        $(document).on("change", ".file_picker", function (e) {
            let file = e.target.files[0];
            let selector = $(this).closest(".input_file_div");
            selector.find(".file-upload-text").text(file.name);
            selector
                .find(".selected-img")
                .attr("src", URL.createObjectURL(file));
        });
        $(".modal")
            .on("shown.bs.modal", function () {
                let id = $(this).attr("id");
                if (id != "addMedia") {
                    modal_id = $("#" + id);
                    modal_id.find(".with_search").select2({
                        placeholder: $(this).attr("placeholder"),
                        dropdownParent: modal_id,
                    });
                    modal_id.find(".without_search").select2({
                        minimumResultsForSearch: Infinity,
                        placeholder: $(this).attr("placeholder"),
                        dropdownParent: modal_id,
                    });
                }
            })
            .on("hidden.bs.modal", function () {
                modal_id.find("p.error").text("");
            });

        $(document).on("submit", ".form", function (e) {
            e.preventDefault();
            let selector = this;
            $(selector).find(".loading_button").removeClass("d-none");
            $(selector).find("p.error").text("");
            $(selector).find(":submit").addClass("d-none");
            let action = $(selector).attr("action");
            let method = $(selector).attr("method");
            let formData = new FormData(selector);
            let modal = $(selector).find(".is_modal").val();
            $.ajax({
                url: action,
                method: method,
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    window.scrollTo({
                        behavior: "smooth",
                        top: 0,
                    });
                    if (response.success) {
                        if (modal_id && !modal) {
                            $(selector)
                                .find(".loading_button")
                                .addClass("d-none");
                            $(selector).find(":submit").removeClass("d-none");
                            toastr.success(response.success);
                            modal_id.modal("hide");
                            $(selector).trigger("reset");
                            modal_id
                                .find(".create_sub_title")
                                .removeClass("d-none");
                            modal_id.find(".edit_sub_title").addClass("d-none");
                            $(".dataTable").DataTable().ajax.reload();
                        } else {
                            if (response.route) {
                                window.location.href = response.route;
                            } else {
                                location.reload();
                            }
                        }
                    } else {
                        $(selector).find(".loading_button").addClass("d-none");
                        $(selector).find(":submit").removeClass("d-none");
                        toastr.error(response.error);
                    }
                },
                error: function (response) {
                    window.scrollTo({
                        behavior: "smooth",
                        top: 0,
                    });
                    $(selector).find(".loading_button").addClass("d-none");
                    $(selector).find(":submit").removeClass("d-none");
                    if (response.status == 422) {
                        if (formData.get("type") == "tab_form") {
                            instructorValidate(selector);
                        }
                        $.each(
                            response.responseJSON.errors,
                            function (key, value) {
                                $("." + key + "_error").text(value[0]);
                            }
                        );
                    } else {
                        toastr.error(response.responseJSON.message);
                    }
                },
            });
        });

        $(document).on("change", ".thumb_picker", function (e) {
            let id = $(this).attr("id");
            let file = e.target.files[0];
            $(this)
                .siblings(".file-upload-text")
                .find(".file_name")
                .text(file.name);
        });
        $(document).on("click", ".edit_modal", function () {
            let id = $(this).attr("data-modal");
            modal_id = $("#" + id);
            modal_id.modal("show");
            modal_id.find(".create_sub_title").addClass("d-none");
            modal_id.find(".edit_sub_title").removeClass("d-none");
            let fetch_url = $(this).attr("data-fetch_url");
            let route = $(this).attr("data-route");
            modal_id.find("form").attr("action", route);
            $.ajax({
                type: "GET",
                url: fetch_url,
                success: function (response) {
                    if (response.html) {
                        modal_id.find(".form_div").html(response.html);
                        defaultEditor();
                        selectionFields();
                    } else {
                        let keys = Object.keys(response);
                        for (let i = 0; i < keys.length; i++) {
                            let selector = modal_id.find(
                                'form [name="' + keys[i] + '"]'
                            );
                            if (selector.attr("type") == "checkbox") {
                                selector.prop("checked", response[keys[i]]);
                            } else {
                                selector.val(response[keys[i]]);
                            }
                        }
                    }
                },
            });
        });
        $(document).on("click", ".tab_switcher", function () {
            let selector = $(this).closest(".default-tab-list");
            let current_tab = selector.find(".nav-link.active");
            let current_tab_target = current_tab.data("bs-target");
            let next_tab_li = current_tab.closest("li").siblings(":first");
            let next_tab = next_tab_li.find(".nav-link");
            let next_tab_target = next_tab.data("bs-target");
            current_tab.removeClass("active");
            $(current_tab_target).removeClass("active");
            $(current_tab_target).removeClass("show");
            next_tab.addClass("active");
            $(next_tab_target).addClass("active");
            $(next_tab_target).addClass("show");
        });

        $(document).on("click", ".get_code", function () {
            let length = $(this).attr("data-length");
            let input = $(this).closest(".input-group").find("input");
            var api_key = "";
            var string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            for (var i = 0; i < length; i++)
                api_key += string.charAt(
                    Math.floor(Math.random() * string.length)
                );

            input.val(api_key);
        });
        $(document).on("change", ".sandbox_mode", function () {
            let id = $(this).attr("id");
            let payment_method_status = $(this).prop("checked");
            let input = $(this)
                .closest(".sandbox_mode_div")
                .find('input[name="' + id + '"]');
            if (payment_method_status) {
                input.val(1);
            } else {
                input.val(0);
            }
        });
        $(document).on("keyup", ".cross-origin", function (event) {
            var value = $(this).val();
            var input = $(this).closest("div").find(".cross_origin_input");
            input.val(btoa(value));
        });
        $(document).on("change", "#country", function () {
            var value = $(this).val();
            var url = $(this).attr("data-url");

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    country_id: value,
                },
                success: function (response) {
                    if (response.error) {
                        return toastr.error(response.error);
                    } else {
                        reInitializeSelect($("#state"), response);
                    }
                },
                error: function (response) {
                    toastr.error(response.responseJSON.error);
                },
            });
        });
        $(document).on("change", "#state", function () {
            var value = $(this).val();
            var url = $(this).attr("data-url");
            let no_area = $(this).data("no_area");
            if (no_area == 1) {
                return false;
            }

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    state_id: value,
                },
                success: function (response) {
                    if (response.error) {
                        return toastr.error(response.error);
                    } else {
                        reInitializeSelect($("#city"), response);
                    }
                },
                error: function (response) {
                    toastr.error(response.responseJSON.error);
                },
            });
        });
        $(document).on("change", "#ins_by_org", function () {
            var value = $(this).val();
            var url = $(this).attr("data-url");

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    organization_id: value,
                },
                success: function (response) {
                    if (response.error) {
                        return toastr.error(response.error);
                    } else {
                        reInitializeSelect($("#instructor_ids"), response);
                    }
                },
                error: function (response) {
                    toastr.error(response.responseJSON.error);
                },
            });
        });
        $(document).on("change", "#section_id", function () {
            var value = $(this).val();
            var url = $(this).attr("data-url");

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    section_id: value,
                },
                success: function (response) {
                    if (response.error) {
                        return toastr.error(response.error);
                    } else {
                        reInitializeSelect($("#lesson_id"), response);
                    }
                },
                error: function (response) {
                    toastr.error(response.responseJSON.error);
                },
            });
        });

        $(document).on("click", "#download_update", function () {
            let selector = $(this);
            var url = $(this).attr("data-url");
            var next_version = $(this).attr("data-version");
            $(".alert_div")
                .removeClass("alert-danger")
                .removeClass("alert-success")
                .addClass("d-none");
            $(".overlayText").removeClass("d-none");
            selector.addClass("d-none");
            $("#preloader").removeClass("d-none");
            setTimeout(function () {
                $.ajax({
                    method: "POST",
                    data: {
                        _token: token,
                        version: next_version,
                    },
                    url: url,
                    success: function (data) {
                        $(".alert_div")
                            .addClass("alert-" + data.class)
                            .removeClass("d-none");
                        $(".alert_div strong").text(data.type);
                        $(".alert_div span:first").text(data.message);
                        $(".overlayText").addClass("d-none");
                        selector.removeClass("d-none");
                        $("#preloader").addClass("d-none");
                        if (data.class == "success") {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    },
                    error: function (data) {
                        $(".alert_div")
                            .addClass("alert-danger")
                            .removeClass("d-none");
                        $(".alert_div strong").text("Error !");
                        $(".alert_div span:first").text(data.statusText);
                        $(".overlayText").addClass("d-none");
                        selector.removeClass("d-none");
                        $("#preloader").addClass("d-none");
                    },
                });
            }, 500);
        });
        $(document).on("change", ".organization_balance", function () {
            var value = $(this).val();
            var url = $(this).attr("data-url");
            $.ajax({
                type: "post",
                url: url,
                data: {
                    organization: value,
                    _token: token,
                },
                success: function (response) {
                    if (response.error) {
                        return toastr.error(response.error);
                    } else {
                        $(".available_balance").empty().html(response.amount);
                    }
                },
                error: function (response) {
                    toastr.error(response.responseJSON.error);
                },
            });
        });
        $("#dateRangePicker").on(
            "apply.daterangepicker",
            function (ev, picker) {
                $(this).val(
                    picker.startDate.format("MM/DD/YYYY") +
                        " - " +
                        picker.endDate.format("MM/DD/YYYY")
                );
            }
        );
    });

    passwordprotect();
    mainSidebar();
    settingTools();
    sideNav();
    selectionFields();
    defaultEditor();
    showMore();

    $(".lang").on("change", function () {
        $("#lang").submit();
    });
})(jQuery);

function instructorValidate(el) {
    let selector = $(el).closest(".default-tab-list");
    let current_tab = selector.find(".nav-link.active");
    let current_tab_target = current_tab.data("bs-target");
    current_tab.removeClass("active");
    $(current_tab_target).removeClass("active");
    $(current_tab_target).removeClass("show");
    selector.find(".nav-link:first").addClass("active");
    selector.find(".tab-pane:first").addClass("active");
    selector.find(".tab-pane:first").addClass("show");
}
function resetForm() {
    modal_id.find("form").trigger("reset");
    /*modal_id.find('.form-select').select2('destroy');
    modal_id.find('.form-select').select2({
    placeholder: modal_id.find('.form-select').attr('placeholder'),
    dropdownParent: modal_id
    });
    modal_id.find('.file-upload-text').text('');
    modal_id.find('.selected-img').attr('src', '');*/
}

// Copy Text
function copyText(text) {
    let success_txt = $(".text_copied").val();
    let error_txt = $(".text_copied_fail").val();
    navigator.clipboard
        .writeText(text)
        .then(() => {
            toastr["success"](success_txt);
        })
        .catch((err) => {
            toastr["error"](error_txt + ": ", err);
        });
}

function showMore() {
    $(".details").click(function (event) {
        event.preventDefault();
        $(this).prev("p").toggleClass("toggle");
        if ($(this).text() === "Details") {
            $(this).text("See less");
        } else {
            $(this).text("Details");
        }
    });
    $(".details").each(function () {
        if ($(this).prev("p").height() <= 50.38) {
            $(this).remove();
        }
    });
}

// Main Sidebar
function mainSidebar() {
    const toggleBTN = $(".sidebar-toggler"),
        body = $("body");

    toggleBTN.on("click", function () {
        document.body.classList.toggle("sidebar-collapse");
    });

   
    $(window).on("resize", function () {
        const width = $(this).width();
        if ( screen.width >= 992 && width <= 1366) {
            body.addClass("sidebar-collapse");
        } else {
            body.removeClass("sidebar-collapse");
        }
    });
}
    



// function mainSidebar() {
//     const toggleBTN = $(".sidebar-toggler"),
//         body = $("body");

//     toggleBTN.on("click", function () {
//         document.body.classList.toggle("sidebar-collapse");
//     });

//     $(window).on("resize", function () {
//         const width = $(this).width();
//         if (width >= 576 && width <= 991) {
//             body.addClass("sidebar-collapse");
//         } else {
//             body.removeClass("sidebar-collapse");
//         }
//     });
// }

// 02. Settings Tools Nav
function settingTools() {
    const settingToolsNav = $(".settings-tools-nav ul");
    settingToolsNav.find("li a").each(function () {
        if ($(this).next().length > 0) {
            $(this).append('<i class="dropdown-icon las la-angle-down"></i>');
            $(this).addClass("has-dropdown");
        }
    });
    // Expands dropdown menu on each click
    settingToolsNav.find(".has-dropdown").on("click", function (e) {
        e.preventDefault();
        $(this)
            .parent()
            .parent("li")
            .children("ul")
            .stop(true, true)
            .slideToggle(350);
        $(this).toggleClass("sub-menu-opened");
    });
    $(".settings-tools-nav").on("click", function (event) {
        event.stopPropagation();
    });
}

// Sidenav
function sideNav() {
    const sideNav = $(".email-tamplate-sidenav ul");
    sideNav.find("li a").each(function () {
        if ($(this).next().length > 0) {
            $(this).append('<i class="arrow-icon las la-angle-down"></i>');
            $(this).addClass("has-dropdown");
        }
    });
    // Expands dropdown menu on each click
    sideNav.find(".has-dropdown").on("click", function (e) {
        e.preventDefault();
        $(this)
            .parent()
            .parent("li")
            .children("ul")
            .stop(true, true)
            .slideToggle(350);
        $(this).toggleClass("sub-menu-opened");
    });
    $(".settings-tools-nav").on("click", function (event) {
        event.stopPropagation();
    });
}

// 03. Choices Select Field
function selectionFields() {
    $(".without_search").select2({
        minimumResultsForSearch: Infinity,
        placeholder: $(this).attr("placeholder"),
    });

    $(".with_search, .multiple-select-1").select2({
        placeholder: $(this).attr("placeholder"),
        // dropdownParent: $('#addCurrency')
    });
}

function reInitializeSelect(selector, response) {
    var $Select = selector.select2();
    $Select.empty();
    if (modal_id) {
        $Select.select2({
            data: response,
            dropdownParent: modal_id,
        });
    } else {
        $Select.select2({
            data: response,
        });
    }
    $Select.trigger("change");
}

// 04. Editor
function defaultEditor() {
    $("#product-update-editor").summernote({
        tabsize: 2,
        height: 350,
        fontNames: ["sans-serif", "Arial"],
        fontsize: "16",
        disableResize: true,
        disableResizeEditor: true,
        resize: false,
        toolbar: [
            ["font", ["bold", "underline"]],
            ["fontname", ["fontname"]],
            ["fontsize", ["fontsize"]],
            ["color", ["color"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["insert", ["link", "picture", "video"]],
            ["view", ["fullscreen", "help"]],
        ],
    });
    $(".summernote").summernote({
        tabsize: 2,
        height: 350,
        fontNames: ["sans-serif", "Arial"],
        fontsize: "16",
        disableResize: true,
        disableResizeEditor: true,
        resize: false,
        toolbar: [
            ["font", ["bold", "underline"]],
            ["fontname", ["fontname"]],
            ["fontsize", ["fontsize"]],
            ["color", ["color"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["insert", ["link", "picture", "video"]],
            ["view", ["fullscreen", "help"]],

        ],
    });
}

// 05. File Uploader Dropzone
// Dropzone.autoDiscover = false;

// 06. Password show hide
function passwordprotect() {
    $(document).on("click", ".toggle-password", function () {
        var passwordFieldId = $(this).prev("input").attr("id");
        var passwordField = $(this)
            .closest(".user-password")
            .find(".passField");
        var toggleBtn = $(this);

        if (passwordField.attr("type") === "password") {
            passwordField.attr("type", "text");
            toggleBtn.html("<i class='lar la-eye-slash'></i>");
        } else {
            passwordField.attr("type", "password");
            toggleBtn.html("<i class='lar la-eye'></i>");
        }
    });
}

function searchInstructor(el) {
    el.select2({
        placeholder: el.attr("placeholder"),
        ajax: {
            url: el.data("route")
                ? el.data("route")
                : base_url + "/ajax/instructors",
            dataType: "json",
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        minimumInputLength: 2,
    });
}
function searchUser(el) {
    el.select2({
        placeholder: el.attr("placeholder"),
        ajax: {
            url: el.data("route"),
            dataType: "json",
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        minimumInputLength: 2,
    });
}
function searchBlog(el) {
    el.select2({
        placeholder: el.attr("placeholder"),
        ajax: {
            url: el.data("route"),
            dataType: "json",
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        minimumInputLength: 2,
    });
}
function searchBook(el) {
    el.select2({
        placeholder: el.attr("placeholder"),
        ajax: {
            url: el.data("route"),
            dataType: "json",
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        minimumInputLength: 2,
    });
}
function searchCategory(el) {
    el.select2({
        placeholder: el.attr("placeholder"),
        ajax: {
            url: el.data("route")
                ? el.data("route")
                : base_url + "/ajax/categories",
            dataType: "json",
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        minimumInputLength: 2,
    });
}
function searchOrganization(el) {
    el.select2({
        placeholder: el.attr("placeholder"),
        ajax: {
            url: el.data("route")
                ? el.data("route")
                : base_url + "/ajax/organizations",
            dataType: "json",
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        minimumInputLength: 2,
    });
}

function searchCourse(el) {
    el.select2({
        placeholder: el.attr("placeholder"),
        ajax: {
            url: el.data("route")
                ? el.data("route")
                : base_url + "/ajax/courses",
            dataType: "json",
            data: function (params) {
                return {
                    q: params.term,
                    is_featured: el.data("is_featured") ? 1 : 0,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        minimumInputLength: 2,
    });
}

function searchSubjects(el) {
    el.select2({
        placeholder: el.attr("placeholder"),
        ajax: {
            url: el.data("route")
                ? el.data("route")
                : base_url + "/ajax/subjects",
            dataType: "json",
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        minimumInputLength: 2,
    });
}

function searchLessons(el) {
    el.select2({
        placeholder: el.attr("placeholder"),
        ajax: {
            url: el.data("route")
                ? el.data("route")
                : base_url + "/ajax/lessons",
            dataType: "json",
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        minimumInputLength: 2,
    });
}

function formatState(state) {
    if (!state.id) {
        return state.text;
    }

    return $(
        '<span><img src="' +
            state.image +
            '.png" class="img-flag" /> ' +
            state.image +
            "</span>"
    );
}
window.onload = function () {
    const progressBarLength =
        document.querySelectorAll(".line-progress").length;
    const dataProgressLength = document.querySelectorAll(
        ".line-progress [data-progress]"
    ).length;
    const dataProgress = document.querySelectorAll(
        ".line-progress [data-progress]"
    );

    if (progressBarLength > 0 && dataProgressLength > 0) {
        dataProgress.forEach((x) => AnimateProgress(x));
    }
};
function AnimateProgress(el) {
    el.className = "animate-progress";
    el.setAttribute(
        "style",
        `--animate-progress:${el.getAttribute("data-progress")}%;`
    );
}

var instructorChart2 = document.getElementById("instructorStatistics2");
if (instructorChart2) {
    const instructorChart = new Chart(instructorChart2, {
        type: "line",
        data: {
            labels: [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saterday",
            ],
            datasets: [
                {
                    label: "Regular Sell",
                    data: [5, 10, 2, 13, 7, 14, 2],
                    fill: false,
                    borderColor: "#3F52E3",
                    backgroundColor: "#3F52E3",
                    borderWidth: 1,
                    barThickness: 5,
                    borderRadius: 5,
                    tension: 0.4,
                },
                {
                    label: "Offer Sell",
                    data: [24, 44, 12, 40, 37, 58, 38],
                    fill: false,
                    borderColor: "#24D6A5",
                    backgroundColor: "#24D6A5",
                    borderWidth: 1,
                    barThickness: 5,
                    borderRadius: 5,
                    tension: 0.4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    border: {
                        display: false,
                    },
                    grid: {
                        drawBorder: false,
                        borderDash: [0, 0],
                        lineWidth: 0,
                    },
                },
                y: {
                    grid: {
                        drawBorder: false,
                        borderDash: [5, 5],
                    },
                },
            },
            plugins: {
                legend: {
                    align: "center",
                    position: "bottom",
                    labels: {
                        boxWidth: 7,
                        boxHeight: 7,
                        usePointStyle: true,
                        pointStyle: "circle",
                    },
                },
            },
        },
    });
}
// End Instructor Dashboard Statistics

var instructorChart3 = document.getElementById("instructorStatistics3");
if (instructorChart3) {
    var context = instructorChart3.getContext("2d");
    var gradientBGColor = context.createLinearGradient(0, 0, 0, 900);
    gradientBGColor.addColorStop(0.1, "#3F52E390");
    gradientBGColor.addColorStop(0.4, "#ffffff10");

    const instructorChartItem3 = new Chart(instructorChart3, {
        type: "line",
        data: {
            labels: [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saterday",
            ],
            datasets: [
                {
                    label: "Earning",
                    data: [100, 200, 150, 220, 250, 150, 300],
                    fill: true,
                    borderColor: "#3F52E3",
                    backgroundColor: gradientBGColor,
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    border: {
                        display: false,
                    },
                    grid: {
                        drawBorder: false,
                        borderDash: [0, 0],
                        lineWidth: 0,
                    },
                },
                y: {
                    grid: {
                        drawBorder: false,
                        borderDash: [5, 5],
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });
}
// End Instructor Finance Statistics3

var instructorChart4 = document.getElementById("instructorStatistics4");
if (instructorChart4) {
    var context = instructorChart4.getContext("2d");
    var gradientBGColor = context.createLinearGradient(0, 0, 0, 900);
    gradientBGColor.addColorStop(0.1, "#24D6AC60");
    gradientBGColor.addColorStop(0.4, "#ffffff30");

    const instructorChartItem3 = new Chart(instructorChart4, {
        type: "line",
        data: {
            labels: [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saterday",
            ],
            datasets: [
                {
                    label: "Payment",
                    data: [100, 200, 150, 220, 250, 150, 300],
                    fill: true,
                    borderColor: "#24D6AC",
                    backgroundColor: gradientBGColor,
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    border: {
                        display: false,
                    },
                    grid: {
                        drawBorder: false,
                        borderDash: [0, 0],
                        lineWidth: 0,
                    },
                },
                y: {
                    grid: {
                        drawBorder: false,
                        borderDash: [5, 5],
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });
}
// End Instructor Finance Statistics4

var courseStatisticsItem = document.getElementById("courseStatisticChart");
if (courseStatisticsItem) {
    const courseChart = new Chart(courseStatisticsItem, {
        type: "line",
        data: {
            labels: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
            datasets: [
                {
                    label: "Total Enrolment",
                    data: [65, 58, 41, 58, 74, 57, 63, 43, 62, 65, 100],
                    fill: false,
                    borderColor: "#3F52E3",
                    backgroundColor: "#3F52E3",
                    borderWidth: 1,
                    barThickness: 5,
                    borderRadius: 5,
                },
                {
                    label: "Total Orders",
                    data: [42, 58, 21, 68, 34, 77, 93, 50, 82, 35, 90],
                    fill: false,
                    borderColor: "#24D6A5",
                    backgroundColor: "#24D6A5",
                    borderWidth: 1,
                    barThickness: 5,
                    borderRadius: 5,
                },
                {
                    label: "Total Profit",
                    data: [20, 28, 71, 38, 54, 90, 71, 31, 34, 25, 79],
                    fill: false,
                    borderColor: "#FF5630",
                    backgroundColor: "#FF5630",
                    borderWidth: 1,
                    barThickness: 5,
                    borderRadius: 5,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    border: {
                        display: false,
                    },
                    grid: {
                        drawBorder: false,
                        borderDash: [0, 0],
                        lineWidth: 0,
                    },
                },
                y: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        borderDash: [5, 5],
                    },
                },
            },
            plugins: {
                legend: {
                    align: "start",
                    position: "right",
                    labels: {
                        boxWidth: 7,
                        boxHeight: 7,
                        usePointStyle: true,
                        pointStyle: "circle",
                    },
                },
            },
        },
    });
}

var statisticsItem2 = document.getElementById("statisticsBarChart2");
if (statisticsItem2) {
    const stastBar = new Chart(statisticsItem2, {
        type: "bar",
        data: {
            labels: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
            datasets: [
                {
                    label: "New Students",
                    data: [65, 58, 41, 58, 74, 57, 63, 43, 62, 65, 100],
                    fill: false,
                    borderColor: "#3F52E3",
                    backgroundColor: "#3F52E3",
                    borderWidth: 1,
                    barThickness: 5,
                    borderRadius: 5,
                },
                {
                    label: "New Enrolled",
                    data: [42, 58, 21, 68, 34, 77, 93, 50, 82, 35, 90],
                    fill: false,
                    borderColor: "#24D6A5",
                    backgroundColor: "#24D6A5",
                    borderWidth: 1,
                    barThickness: 5,
                    borderRadius: 5,
                },
                {
                    label: "Total Sale",
                    data: [20, 28, 71, 38, 54, 90, 71, 31, 34, 25, 79],
                    fill: false,
                    borderColor: "#FF5630",
                    backgroundColor: "#FF5630",
                    borderWidth: 1,
                    barThickness: 5,
                    borderRadius: 5,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    border: {
                        display: false,
                    },
                    grid: {
                        drawBorder: false,
                        borderDash: [0, 0],
                        lineWidth: 0,
                    },
                },
                y: {
                    stacked: true,
                    grid: {
                        drawBorder: false,
                        borderDash: [5, 5],
                    },
                },
            },
            plugins: {
                legend: {
                    align: "start",
                    position: "right",
                    labels: {
                        boxWidth: 7,
                        boxHeight: 7,
                        usePointStyle: true,
                        pointStyle: "circle",
                    },
                },
            },
        },
    });
}

const chartItem7 = document.getElementById("statisticsChart7");
if (chartItem7) {
    var context = chartItem7.getContext("2d");
    var gradientBGColor = context.createLinearGradient(0, 0, 0, 200);
    gradientBGColor.addColorStop(0.1, "#ff563050");
    gradientBGColor.addColorStop(0.4, "#ffffff30");

    const chart3 = new Chart(chartItem7, {
        type: "line",
        data: {
            labels: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Nov",
                "Des",
            ],
            datasets: [
                {
                    label: "Free Course",
                    backgroundColor: gradientBGColor,
                    borderColor: "#ff5630",
                    data: [1, 37, 26, 40, 34, 59, 42, 60, 78, 80, 100],
                    fill: true,
                },
            ],
        },
        options: {
            plugins: {
                legend: {
                    display: false,
                },
            },
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: false,
                },
                y: {
                    display: false,
                },
            },
            elements: {
                line: {
                    borderWidth: 2,
                    tension: 0.4,
                },
                point: {
                    radius: 0,
                    hitRadius: 10,
                    hoverRadius: 4,
                },
            },
        },
    });
}
const debounceButton = () => {
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
  