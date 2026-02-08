$(document).on('shown.bs.modal', function (e) {
    let modal = $(e.target);

    modal.find('.select-barang').select2({
        placeholder: "-- Pilih Barang --",
        allowClear: true,
        width: '100%',
        dropdownParent: modal
    });
});
$(document).on('hide.bs.modal', function () {
    document.activeElement.blur();
});