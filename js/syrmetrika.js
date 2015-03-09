/**
 * @package Syrmetrika
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @version 2.0.0
 * @copyright (c) 2014, Serge Rodovnichenko
 * @license http://www.webasyst.com/terms/#eula Webasyst
 */
$(function () {
    $.Syrmetrika = {
        form: $('form', '#syrmetrikasettings'),
        settlement: $('[name="settlement"]', '#syrmetrikasettings'),
        load: function () {
            var settlement = $.Syrmetrika.settlement.val();
            $('input,select,button', $.Syrmetrika.form).prop('disabled', true);
            $.get(
                $.Syrmetrika.form.attr('action'),
                {
                    settlement: settlement
                },
                function (r) {
                    if(r.status == 'ok') {
                        $.each(r.data, function(key, value){
                            $("input[name='setting["+key+"]']", $.Syrmetrika.form).val(value);
                        })
                    }
                },
                'json')
                .always(function () {
                    $('input,select,button', $.Syrmetrika.form).prop('disabled', false);
                });
        },

        save: function () {
            var form_data = $.Syrmetrika.form.serialize();
            $('input,select,button', $.Syrmetrika.form).prop('disabled', true);
            $.post(
                $.Syrmetrika.form.attr('action'),
                form_data,
                function (r) {
                },
                'json')
                .always(function () {
                    $('input,select,button', $.Syrmetrika.form).prop('disabled', false);
                });
        },

        init: function () {
            $('.field.settlement select', $.Syrmetrika.form)
                .unbind('change')
                .bind('change', function () {
                    $.Syrmetrika.load();
                });

            $(this.form)
                .unbind()
                .bind('submit', function(evt){
                    evt.preventDefault();
                    $.Syrmetrika.save();
                    return false;
                });

            if (this.form.length) this.load();
        }
    };

    $.Syrmetrika.init();
});