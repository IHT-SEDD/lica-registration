"use strict";

let createData, rulesFormValidation, searchByNIK;

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

rulesFormValidation = {
    name: {
        required: true,
    },
    nik: {
        required: true,
        number: true,
    },
    name: {
        required: true,
    },
    email: {
        required: true,
    },
    phone: {
        required: true,
        digits: true,
    },
    birthdate: {
        required: true,
    },
    gender: {
        required: true,
    },
    address: {
        required: true,
    },
};

(function () {
    createData = () => {
        let formData = new FormData(myForm[0]);
        let form = myForm;

        $.ajax({
            url: "create",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status == 400) {
                    Toast.fire({
                        icon: "error",
                        title: res.messages || "Something went wrong",
                    });
                } else {
                    $(".btn-finish").prop("disabled", false);
                    sessionStorage.setItem("createEmployee", "true");
                    window.location.href = "/employee";
                }
            },
            error: function (request, status, error) {
                Toast.fire({
                    icon: "error",
                    title:
                        request.responseJSON?.message || "Something went wrong",
                });

                $(".btn-finish").prop("disabled", false);
            },
        });
    };

    searchByNIK = () => {
        $("#btn-search").on("click", function () {
            alert($('#form-create input[name="search_nik"]'));
        });
    };

    // On document ready
    document.addEventListener("DOMContentLoaded", function () {
        // add the rule here
        let checkSelect = $.validator.addMethod(
            "niceSelectRequired",
            function (value, element) {
                // Kalau native value tidak kosong, berarti user sudah memilih
                return value && value.trim() !== "";
            },
            "Please select your marital status."
        );

        $("#form-create").validate({
            ignore: "input[type=hidden], .select2-search__field, .ignore-this", // ignore hidden fields
            errorClass: "fv-plugins-message-container invalid-feedback",
            successClass: "validation-valid-label",
            validClass: "validation-valid-label",
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            // success: function(label) {
            //     label.addClass('validation-valid-label').text('Valid'); // remove to hide Success message
            // },

            // Different components require proper error label placement
            errorPlacement: function (error, element) {
                // Unstyled checkboxes, radios
                if (element.parents().hasClass("form-check")) {
                    error.appendTo(element.parents(".form-check").parent());
                }

                // Input with icons and Select2
                else if (
                    element.parents().hasClass("form-group-feedback") ||
                    element.hasClass("select2-hidden-accessible")
                ) {
                    error.appendTo(element.parent());
                }

                // Input group, styled file input
                else if (
                    element.parent().is(".uniform-uploader, .uniform-select") ||
                    element.parents().hasClass("input-group")
                ) {
                    error.appendTo(element.parent().parent());
                }

                // Other elements
                else {
                    error.insertAfter(element);
                }
            },
            rules: rulesFormValidation,
            messages: {
                custom: {
                    required: "This is a custom error message",
                },
            },
            // submitHandler: function(form, event) {
            // }
        });
    });
})();
