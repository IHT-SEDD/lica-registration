"use strict";

let createData, rulesFormValidation, searchByNIK, baseUrl, birthdate, formValidation;

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

 baseUrl = (url) => {
    return base + url;
  }


    birthdate = () => {
    const thisYear = new Date().getFullYear();  // returns the current year
   $(".birthdate").flatpickr({
      altInput: true,
      altFormat: 'j F Y',
      dateFormat: 'Y-m-d',
      static: true
    });

  }

rulesFormValidation = {
    name: {
        required: true,
    },
    nik: {
    required: true,
    digits: true,
    remote: {
        url: baseUrl('form/check-nik'),
        type: "post",
        data: {
            nik: function () {
                return $("#nik").val();
            },
              search_nik: function () {
                return $("#search_nik").val();
            }
        },
    },
},
    no_bpjs: {
        number: true,
    },
    name: {
        required: true,
    },
    email : {
        email : true
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

    formValidation = () => {
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
                name: {
                    required: "Nama pasien wajib diisi!",
                },
                nik: {
                    required: "NIK wajib diisi!",
                    digits: "Nik harus berupa angka!",
                   remote: "NIK sudah terdaftar!",
                },
                email: {
                    email : "Email tidak valid!",
                },
                phone: {
                    required: 'No. HP wajib diisi',
                    digits: "Nik harus berupa angka!"
                },
                birthdate: {
                    required: 'Tanggal lahir wajib diisi'
                },
                gender: {
                    required: 'Jenis kelamin wajib diisi'
                },
                address: {
                    required: 'Alamat wajib diisi'
                }
            },
            // submitHandler: function(form, event) {
            // }
        });
        $("#btn-submit").on('click', function(e) {
            e.preventDefault();
            if ($(this).valid()) {
                createData();
            }
        });
    }


    createData = () => {
         $('#btn-submit').prop('disabled', true);
        let formData = new FormData($("#form-create")[0]);
        let form = $("#form-create");
        $.ajax({
            url: baseUrl('form/create'),
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status == 400) {
                    toastr.error("Something went wrong!");
                    $('#btn-submit').prop('disabled', false);
                } else {
                        toastr.success(res.message);
                        form[0].reset();
                        $('#btn-submit').prop('disabled', false);
                }
            },
            error: function (request, status, error) {
                toastr.error(request.responseJSON?.message);
                $(".btn-finish").prop("disabled", false);
            },
        });
    };

    searchByNIK = () => {

        $("#btn-search").on("click", function () {

        const nik = $('#form-create input[name="search_nik"]').val().trim();;

        if(nik.length < 16){
            toastr.error("NIK harus 16 digit!");
            return;
        }

        $.ajax({
            type: "GET",
            url: baseUrl('form/search-nik/'+nik),
            success: function (response) {
                if(response.status == 200){
                    const data = response.data;
                    $('#form-create input[name="nik"]').val(data.nik);
                    $('#form-create input[name="name"]').val(data.name);
                    $('#form-create input[name="no_bpjs"]').val(data.no_bpjs);
                    $('#form-create input[name="email"]').val(data.email);
                    $('#form-create input[name="phone"]').val(data.phone);
                  $('#form-create input[name="gender"][value="' + data.gender + '"]').prop('checked', true);
                    $('#form-create textarea[name="address"]').val(data.address);
                    $('#form-create input[name="birthdate"]').flatpickr().setDate(data.birthdate);
                } else {
                      toastr.error("Data tidak ditemukan!");
                }

            }
        });

        });
    };

    // On document ready
    document.addEventListener("DOMContentLoaded", function () {
        searchByNIK();
        birthdate();
        formValidation();
        // add the rule here
        let checkSelect = $.validator.addMethod(
            "niceSelectRequired",
            function (value, element) {
                // Kalau native value tidak kosong, berarti user sudah memilih
                return value && value.trim() !== "";
            },
            "Please select your marital status."
        );


    });
})();
