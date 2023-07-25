function removeElement(id) {
    const elementCount = document.querySelectorAll('.stacking-file[data-file]').length;
    if (elementCount > 1) {
        document.getElementById(id).remove();
    }
}

function validateFileExtension(input) {
    const allowedExtensions = ['.jpg', '.jpeg', '.png', '.pdf'];
    const maxFileSize = 10 * 1024 * 1024; // 10MB in bytes
    const file = input.files[0];
    const fileSize = file.size;

    if (fileSize > maxFileSize) {
        // Handle the case when the file size exceeds the limit
        Swal.fire({
            icon: 'warning',
            title: 'Ukuran File Terlalu Besar',
            text: 'File yang dipilih melebihi batas maksimum yang diizinkan (10MB). Harap pilih file yang lebih kecil.',
            showConfirmButton: false,
            timer: 7000,
            timerProgressBar: true,
        });
        input.value = '';
        return false;
    }

    const filePath = file.name;
    const fileExtension = filePath.substring(filePath.lastIndexOf('.'));

    if (!allowedExtensions.includes(fileExtension)) {
        // Handle the case when the file extension is not allowed
        Swal.fire({
            icon: 'warning',
            title: 'Ekstensi File Dilarang',
            text: 'Harap pilih file dengan salah satu ekstensi berikut: ' + allowedExtensions.join(', '),
            showConfirmButton: false,
            timer: 7000,
            timerProgressBar: true,
        });
        input.value = '';
        return false;
    }

    return true;
}

function handleFormSubmit(form, result) {
    result.validate().then((status) => {
        if (status === 'Valid') {
            Swal.fire({
                title: 'Simpan perubahan?',
                text: "Anda yakin ingin menyimpan perubahan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Tidak',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else if (result.isDenied) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Perubahan Dibatalkan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
}

const resultExamples = FormValidation.formValidation(formValidationExamples, {
    fields: {
        namaProgram: {
            validators: {
                notEmpty: {
                message: 'Tolong input nama program'
                }
            }
        },
        coa: {
            validators: {
                notEmpty: {
                message: 'Tolong pilih coa'
                }
            }
        },
        nilaiAnggaran: {
            validators: {
                notEmpty: {
                message: 'Tolong input nilai anggaran'
                }
            }
        },
        realisasi: {
            validators: {
                notEmpty: {
                message: 'Tolong input realisasi'
                }
            }
        },
        periodeProgram: {
            validators: {
                notEmpty: {
                message: 'Tolong input tanggal periode'
                }
            }
        }
    },
    plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '',
        rowSelector: function (field, ele) {
            switch (field) {
            case 'formValidationName':
                return '.col-md-6';
            case 'formValidationPlan':
                return '.col-xl-3'
            default:
                return '.row';
            }
        }
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus()
    },
    init: instance => {
        instance.on('plugins.message.placed', function (e) {
        if (e.element.parentElement.classList.contains('input-group')) {
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
        }
        if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
            e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
        }
        });
    }
});

document.getElementById('submit-button').addEventListener('click', function(e) {
    e.preventDefault();
    handleFormSubmit(document.getElementById('formValidationExamples'), resultExamples);
});