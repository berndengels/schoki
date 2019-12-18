<?php if ($showLabel && $showField && !$options['is_child']): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>

    <?php if ($showLabel): ?>
        <?= Form::label($name, $options['label'], $options['label_attr']) ?>
        <?php endif; ?>

    <?php if ($showField): ?>
    <div class="input-group date {{ $options['div_class'] }}" data-date="1979-09-16T05:25:07Z" data-date-format="{{ $options['dt_format'] }}" data-link-field="{{ $name }}_hidden">
        <?= Form::input($type, $name, $options['default_value'], $options['attr']) ?>
        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
    </div>
    <input type="hidden" id="{{ $name }}_hidden"    value="" /><br/>
    <?php endif; ?>

    <?php if ($showError && isset($errors)): ?>
        <?= $errors->first(array_get($options, 'real_name', $name), '<div '.$options['errorAttrs'].'>:message</div>') ?>
        <?php endif; ?>

    <?php if ($showLabel && $showField && !$options['is_child']): ?>
</div>

<!--script type="text/javascript" src="{{ asset('js/jquery-1.8.3.min.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/js/locales/bootstrap-datepicker.de.js') }}" charset="UTF-8"></script-->

<script type="text/javascript">
    $(".{{ $options['div_class'] }}").datepicker({
        format: "{{ $options['format'] }}",
        startDate: new Date(),
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        datesDisabled: []
//        format: 'mm/dd/yyyy',
//        showMeridian: true,
//        todayBtn: true,
//        todayHighlight: true,
//        minuteStep: 15,
    });
</script>


<?php endif; ?>

