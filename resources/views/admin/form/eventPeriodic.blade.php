
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
    {!! form_until($form, 'links') !!}

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
    const ID = {{ $id ?? 'null' }},
        uploadWebPath = "{!! config('filesystems.disks.image_upload.webRoot') !!}",
        maxImageHeight = {!! config('event.maxImageHeight') !!},
        cropperMaxFilesize = {!! config('event.maxImageFileSize') !!},
        periodicDates = [{!! $dates !!}],
        dropzoneOptions = {
            type: 'Image',
            paramName: 'image',
            maxFilesize: {!! config('event.maxImageFileSize') !!},
            dictInvalidFileType: 'Falscher Datei-Typ! Erlaubt sind folgende Typen: .jpg, .jpeg',
            acceptedFiles: ".jpeg,.jpg",
            url: "/admin/file/upload",
        };

    InitDropzone(dropzoneOptions);

    $('.multi-collapse').on('shown.bs.collapse', function () {
        $('html, body').animate({ scrollTop: ($('#btnImageCollapse').offset().top)}, 'slow');
    });

    const tinymceOptions = {
        selector: '#tinymce',
        plugins: ['preview','code','lists','advlist','link','autolink','paste','media','quickbars','help'],
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link media quickbars preview help',
        image_advtab: true,
        width: 800,
        height: 600,
        paste_as_text: true,
        paste_block_drop: true,
        images_upload_base_path: "{!! config('filesystems.disks.upload.webRoot') !!}",
    };
    InitEditor(tinymceOptions);
    const datepickerOptions = {
        weekStart: 1,
        startDate: new Date(),
        autoclose: true,
        todayBtn: false,
        todayHighlight: true,
        language: "de-DE",
    };

    InitDatepicker(datepickerOptions, periodicDates);
</script>
@endsection
