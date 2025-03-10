
@extends('layouts.admin')

@section('extra-headers')
    <script type="text/javascript" src="{{ mix('vendor/dropzone/js/dropzone.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ mix('vendor/tinymce/tinymce.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js"></script>
    <link type="text/css" rel="stylesheet" href="{{ mix('vendor/dropzone/css/dropzone.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css">
@endsection

@section('content')
    @include('components.back')
    {!! form_start($form) !!}
    {!! form_until($form, 'ticketlink') !!}

    <button id="btnImageCollapse" class="btn btn-primary col-12 text-left" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="collapseImages collapseDropzoneTarget">
        Bilder @if(!$form->images) hinzufügen @else anzeigen @endif
    </button>

    @if ($form->images)
        <div class="collection-container">
            {!! form_row($form->images) !!}
        </div>
    @endif

    @include('admin.templates.newImages')
    @include('admin.templates.dropzoneTarget', ['items' => $form->images])
    @include('admin.templates.imageEditorInline')

    {!! form_rest($form) !!}
    {!! form_end($form) !!}
    @include('components.back')
@endsection

@section('inline-scripts')
<script>
    // https://youtu.be/EcfCjO7KIyQ
    var reservedDates = [{!! $dates !!}];
    var tinymceOptions = {
        selector: '#tinymce',
        plugins: ['preview','code','lists','advlist','link','autolink','paste','media','quickbars','help'],
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link media quickbars preview help code',
        image_advtab: true,
        width: 800,
        height: 600,
        paste_as_text: true,
        paste_block_drop: true,
        images_upload_base_path: "{!! config('filesystems.disks.upload.webRoot') !!}",
    };
    InitEditor(tinymceOptions);
    var datepickerOptions = {
        weekStart: 1,
        startDate: new Date(),
        autoclose: true,
        todayBtn: false,
        todayHighlight: true,
        language: "de-DE",
    };
    var datepicker = InitDatepicker(datepickerOptions, reservedDates);
    datepicker.on('changeDate', function(e, data) {
        var eventDate = $(this).val(),
            $helpBlock = $('.help-block','#wrapperEventDate'),
            $btnConfirm = $('#wrapperBtnSubmitOverride'),
            $confirmReset = $('#wrapperConfirmReset'),
            $btnConfirmReset = $('button','#wrapperConfirmReset'),
            $override = $('#override'),
            $isPeriodic = $('#isPeriodic')
        ;
        //eventDate = eventDate.toISOString().split('T')[0];
        $.post({
            url: '/admin/events/checkForPeriodicDate',
            data: {
                date: eventDate,
                _token: $('[name="_token"]').val()
            },
            dataType: 'json',
            error: function(err){
                $isPeriodic.val(0);
                console.error(err);
            },
            success: function (result) {
                if(result.entity) {
                    $override.val(result.entity.id);
                    $isPeriodic.val(1);
                    $confirmReset.removeClass('d-none');
                    $btnConfirm.removeClass('d-none');

                    var event = result.entity,
                        html = $helpBlock.html()
                            .replace('%ID%', event.id)
                            .replace('%TITLE%', event.title)
                            .replace('%DATE%', result.date);

                    $('#title').val(event.title);
                    $('#event_time').val(event.event_time ? event.event_time : '19:00');

                    $helpBlock.removeClass('d-none').html(html);
                    $btnConfirmReset.click(function(){
                        $isPeriodic.val(false);
                        $('#event_date').val('');
                        $helpBlock.addClass('d-none');
                        $confirmReset.addClass('d-none');
                        $btnConfirm.addClass('d-none');
                    });
                } else {
                    $isPeriodic.val(0);
                    $btnConfirm.addClass('d-none');
                    $confirmReset.addClass('d-none');
                }
            }
        });
    });

    $('.multi-collapse').on('shown.bs.collapse', function () {
        $('html, body').animate({ scrollTop: ($('#btnImageCollapse').offset().top)}, 'slow');
    });

    //        const cropper;
    const ID = {{ $id ?? 'null' }},
        uploadWebPath = "{!! config('filesystems.disks.image_upload.webRoot') !!}",
        maxImageHeight = {!! config('event.maxImageHeight') !!},
        cropperMaxFilesize = {!! config('event.maxImageFileSize') !!},
        dropzoneOptions = {
            type: 'Image',
            paramName: 'image',
            maxFilesize: {!! config('event.maxImageFileSize') !!},
            dictInvalidFileType: 'Falscher Datei-Typ! Erlaubt sind folgende Typen: .jpg, .jpeg',
            acceptedFiles: ".jpeg,.jpg",
            url: "/admin/file/upload",
        };

    InitDropzone(dropzoneOptions);

    const $myDate = $('input[type=date]');

    const handleReservedDates = (e) => {
        var target = e.target,$target = $(target),date = $target.val(),
            errMsg = "Sorry, dieses Datum (" + date + ") ist bereits von einem anderen Event belegt!\nBitte wähle ein anderes aus.";

        if($.inArray(date, reservedDates) > -1 ){
            console.info('is reserved');
            $target.addClass('invalid');
            e.target.setCustomValidity(errMsg);
            alert(errMsg);
            $target.val('');
        } else {
            $target.removeClass('invalid');
            e.target.setCustomValidity('');
        }
    }

    $myDate.on('input',handleReservedDates);
</script>
@endsection
