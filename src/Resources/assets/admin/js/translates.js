var moduleTranslatesAdmin = {
    config: {
        'languages_lists': '.languages_select_list'
    },

    init: function () {
        let self = this;
        let config = self.config;

        $(config.languages_lists).unbind('change').on('change', function (e) {
            document.location.href = langIndexUrl + '/' + $(this).val();
        });
    }
};

$(document).on('mainComponentsAdminLoaded', function () {
    moduleTranslatesAdmin.init();
});
