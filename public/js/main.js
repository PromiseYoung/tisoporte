$(document).ready(function () {
    window._token = $('meta[name="csrf-token"]').attr('content');

    initializeEditors();
    configureMomentLocale();
    initializeDatePickers();
    initializeSelect2();
    handleSelectAllButtons();
    handleTreeView();

    function initializeEditors() {
        const allEditors = document.querySelectorAll('.ckeditor');
        allEditors.forEach(editor => {
            ClassicEditor.create(editor, {
                removePlugins: ['ImageUpload']
            });
        });
    }

    function configureMomentLocale() {
        moment.updateLocale('en', {
            week: { dow: 1 } // Monday is the first day of the week
        });
    }

    function initializeDatePickers() {
        $('.date').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: 'en'
        });

        $('.datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: 'en',
            sideBySide: true
        });

        $('.timepicker').datetimepicker({
            format: 'HH:mm:ss'
        });
    }

    function initializeSelect2() {
        $('.select2').select2();
    }

    function handleSelectAllButtons() {
        $('.select-all').click(function () {
            const $select2 = $(this).parent().siblings('.select2');
            $select2.find('option').prop('selected', 'selected');
            $select2.trigger('change');
        });

        $('.deselect-all').click(function () {
            const $select2 = $(this).parent().siblings('.select2');
            $select2.find('option').prop('selected', '');
            $select2.trigger('change');
        });
    }

    function handleTreeView() {
        $('.treeview').each(function () {
            const shouldExpand = $(this).find('li.active').length > 0;
            if (shouldExpand) {
                $(this).addClass('active');
            }
        });
    }
});
