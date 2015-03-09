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
            $('#syrmetrika-loading-settlement-data-icon').show();
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
                    } else if(r.status == 'fail') {
                        $('#syrmetrika-load-settlement-data-messages').text(r.errors[0]);
                        $('#syrmetrika-load-settlement-data-messages').show('fast', function(){
                            $(this).fadeOut(2000);
                        });
                    }
                },
                'json')
                .always(function () {
                    $('input,select,button', $.Syrmetrika.form).prop('disabled', false);
                    $('#syrmetrika-loading-settlement-data-icon').hide();
                });
        },

        save: function () {
            var form_data = $.Syrmetrika.form.serialize();
            $('input,select,button', $.Syrmetrika.form).prop('disabled', true);
            $('#syrmetrika-save-settings-process-icon').show();
            $.post(
                $.Syrmetrika.form.attr('action'),
                form_data,
                function (r) {
                    if(r.status == 'ok') {
                        $('#syrmetrika-save-settings-result')
                            .removeClass()
                            .addClass('success')
                            .html('<i class="icon16 yes"></i> ' + r.data[0]);
                    } else if(r.status == 'fail') {
                        $('#syrmetrika-save-settings-result')
                            .removeClass()
                            .addClass('error-message')
                            .html('<i class="icon16 no"></i> ' + r.errors[0])
                    }
                },
                'json')
                .always(function () {
                    $('input,select,button', $.Syrmetrika.form).prop('disabled', false);
                    $('#syrmetrika-save-settings-process-icon').hide();
                    $('#syrmetrika-save-settings-result').show('fast', function(){
                        $(this).fadeOut(2000);
                    });
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