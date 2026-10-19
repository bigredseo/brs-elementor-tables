/* BRS Table: sync the visible repeater cell fields to the widget's column count. */
(function ($) {
    'use strict';

    function updatePanelClass(panel, settings) {
        var count = parseInt(settings.get('column_count'), 10);
        count = Math.max(1, Math.min(6, isNaN(count) ? 3 : count));
        for (var i = 1; i <= 6; i++) {
            panel.classList.remove('brs-table-columns-' + i);
        }
        panel.classList.add('brs-table-columns-' + count);
    }

    $(window).on('elementor:init', function () {
        var activeSettings = null;
        var onCountChanged = null;

        elementor.hooks.addAction('panel/open_editor/widget', function (panelView, model) {
            var panel = document.getElementById('elementor-panel');
            if (!panel) { return; }

            if (activeSettings && onCountChanged) {
                activeSettings.off('change:column_count', onCountChanged);
            }
            activeSettings = null;
            onCountChanged = null;

            if (!model || model.get('widgetType') !== 'brs_table') {
                for (var i = 1; i <= 6; i++) {
                    panel.classList.remove('brs-table-columns-' + i);
                }
                return;
            }

            var settings = model.get('settings');
            if (!settings) { return; }
            activeSettings = settings;
            onCountChanged = function () { updatePanelClass(panel, settings); };
            settings.on('change:column_count', onCountChanged);
            updatePanelClass(panel, settings);
        });
    });
})(jQuery);
