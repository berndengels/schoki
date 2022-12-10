
@extends('layouts.admin')

@section('extra-headers')
    <script type="text/javascript" src="{{ mix('vendor/dropzone/js/dropzone.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ mix('vendor/tinymce/tinymce.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js"></script>
    <script type="text/javascript" src="{{ mix('js/admin.js') }}" charset="UTF-8"></script>
    <link type="text/css" rel="stylesheet" href="{{ mix('vendor/dropzone/css/dropzone.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css">
@endsection

@section('content')
    @include('components.back')
    {!! form($form) !!}
    @include('components.back')

    <script>
        var tinymceOptions = {
            selector: '#tinymce',
            plugins: 'preview code advlist autolink link paste lists charmap preview imagetools help media responsivefilemanager',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link autolink media responsivefilemanager code preview help',
            image_advtab: true,
            width: 800,
            height: 600,
            paste_as_text: false,
            images_upload_base_path: "{!! config('filesystems.disks.upload.webRoot') !!}",
        };
        initEditor(tinymceOptions);
    </script>
@endsection

