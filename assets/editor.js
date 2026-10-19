/* Elementor custom control: a column repeater followed by collapsed, synchronized row editors. */
(function ($) {
    'use strict';
    function clean(value) {
        if (!value || typeof value !== 'object' || Array.isArray(value)) { value = {}; }
        var columns = Array.isArray(value.columns) ? value.columns.slice(0, 12).map(String) : ['Column 1', 'Column 2'];
        if (!columns.length) { columns = ['Column 1']; }
        var rows = Array.isArray(value.rows) ? value.rows.slice(0, 250) : [];
        rows = rows.map(function (row) {
            row = Array.isArray(row) ? row.slice(0, columns.length).map(String) : [];
            while (row.length < columns.length) { row.push(''); }
            return row;
        });
        return {columns: columns, rows: rows};
    }
    function dom(tag, cls, text) {
        var node = document.createElement(tag);
        if (cls) { node.className = cls; }
        if (text !== undefined) { node.textContent = text; }
        return node;
    }
    function button(text, title, fn) {
        var el = dom('button', '', text);
        el.type = 'button'; el.title = title || text;
        el.addEventListener('click', function (event) { event.preventDefault(); fn(); });
        return el;
    }
    function field(label, value, callback) {
        var wrap = dom('div', 'brs-editor-field');
        var main = dom('div'); var labelEl = dom('label', '', label);
        var input = dom('input'); input.type = 'text'; input.value = value;
        input.addEventListener('input', function () { callback(input.value); });
        main.appendChild(labelEl); main.appendChild(input); wrap.appendChild(main);
        return wrap;
    }
    function save(view) { view.setValue(JSON.parse(JSON.stringify(view.brsData))); }
    function rebuild(view) {
        var root = view.$el.find('.brs-table-editor-root')[0];
        if (!root) { return; }
        root.replaceChildren();
        var data = view.brsData;
        var outer = dom('div', 'brs-structure-editor');
        var header = dom('details', 'brs-editor-section'); header.open = view.brsOpen !== 'rows';
        header.addEventListener('toggle', function () { if (header.open) { view.brsOpen = 'header'; } });
        header.appendChild(dom('summary', '', 'Header Row (' + data.columns.length + ' columns)'));
        var headerFields = dom('div', 'brs-editor-fields');
        data.columns.forEach(function (value, i) {
            var item = field('Column ' + (i + 1), value, function (next) { data.columns[i] = next; save(view); });
            item.appendChild(button('↑', 'Move left', function () {
                if (i < 1) { return; }
                [data.columns[i - 1], data.columns[i]] = [data.columns[i], data.columns[i - 1]];
                data.rows.forEach(function (row) { [row[i - 1], row[i]] = [row[i], row[i - 1]]; });
                save(view); rebuild(view);
            }));
            item.appendChild(button('×', 'Delete column', function () {
                if (data.columns.length <= 1) { return; }
                data.columns.splice(i, 1);
                data.rows.forEach(function (row) { row.splice(i, 1); });
                save(view); rebuild(view);
            }));
            headerFields.appendChild(item);
        });
        headerFields.appendChild(button('+ Add Column', 'Add column', function () {
            if (data.columns.length >= 12) { return; }
            data.columns.push('Column ' + (data.columns.length + 1));
            data.rows.forEach(function (row) { row.push(''); });
            save(view); view.brsOpen = 'header'; rebuild(view);
        }));
        header.appendChild(headerFields); outer.appendChild(header);
        var rowsTitle = dom('div', '', 'Table Rows'); rowsTitle.style.fontWeight = '600'; outer.appendChild(rowsTitle);
        data.rows.forEach(function (row, ri) {
            var details = dom('details', 'brs-editor-section');
            details.open = view.brsOpen === 'rows' && view.brsOpenRow === ri;
            details.addEventListener('toggle', function () {
                if (details.open) { view.brsOpen = 'rows'; view.brsOpenRow = ri; }
            });
            details.appendChild(dom('summary', '', 'Row ' + (ri + 1)));
            var fields = dom('div', 'brs-editor-fields');
            row.forEach(function (val, ci) {
                fields.appendChild(field('Item ' + (ci + 1), val, function (next) { row[ci] = next; save(view); }));
            });
            var actions = dom('div', 'brs-editor-actions');
            actions.appendChild(button('↑', 'Move row up', function () {
                if (ri === 0) { return; }
                [data.rows[ri - 1], data.rows[ri]] = [data.rows[ri], data.rows[ri - 1]];
                save(view); view.brsOpenRow = ri - 1; rebuild(view);
            }));
            actions.appendChild(button('↓', 'Move row down', function () {
                if (ri >= data.rows.length - 1) { return; }
                [data.rows[ri + 1], data.rows[ri]] = [data.rows[ri], data.rows[ri + 1]];
                save(view); view.brsOpenRow = ri + 1; rebuild(view);
            }));
            actions.appendChild(button('Duplicate', 'Duplicate row', function () {
                data.rows.splice(ri + 1, 0, row.slice()); save(view); view.brsOpenRow = ri + 1; rebuild(view);
            }));
            actions.appendChild(button('Delete', 'Delete row', function () {
                data.rows.splice(ri, 1); save(view); view.brsOpenRow = -1; rebuild(view);
            }));
            fields.appendChild(actions); details.appendChild(fields); outer.appendChild(details);
        });
        outer.appendChild(button('+ Add Row', 'Add table row', function () {
            if (data.rows.length >= 250) { return; }
            data.rows.push(data.columns.map(function () { return ''; }));
            view.brsOpen = 'rows'; view.brsOpenRow = data.rows.length - 1;
            save(view); rebuild(view);
        }));
        root.appendChild(outer);
    }
    $(window).on('elementor:init', function () {
        var watchedSettings = null;
        var watchedChange = null;
        function syncWidthControls(settings) {
            var panel = document.getElementById('elementor-panel');
            if (!panel) { return; }
            for (var i = 1; i <= 12; i++) { panel.classList.remove('brs-column-count-' + i); }
            var structure = settings && settings.get('structure');
            var count = structure && Array.isArray(structure.columns) ? structure.columns.length : 2;
            panel.classList.add('brs-column-count-' + Math.max(1, Math.min(12, count)));
        }
        elementor.hooks.addAction('panel/open_editor/widget', function (panelView, model) {
            if (watchedSettings && watchedChange) { watchedSettings.off('change:structure', watchedChange); }
            watchedSettings = null;
            watchedChange = null;
            var panel = document.getElementById('elementor-panel');
            if (panel) { for (var i = 1; i <= 12; i++) { panel.classList.remove('brs-column-count-' + i); } }
            if (!model || model.get('widgetType') !== 'brs_table') { return; }
            watchedSettings = model.get('settings');
            if (!watchedSettings) { return; }
            watchedChange = function () { syncWidthControls(watchedSettings); };
            watchedSettings.on('change:structure', watchedChange);
            syncWidthControls(watchedSettings);
        });
        elementor.addControlView('brs_table_structure', elementor.modules.controls.BaseData.extend({
            onReady: function () {
                this.brsData = clean(this.getControlValue());
                this.brsOpen = 'header'; this.brsOpenRow = -1;
                rebuild(this);
            }
        }));
    });
})(jQuery);
