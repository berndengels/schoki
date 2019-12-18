
@extends('layouts.admin')

@section('extra-headers')
    <script type="text/javascript" src="{{ asset('vendor/dropzone/js/dropzone.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('vendor/tinymce/tinymce.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js"></script>
    <script type="text/javascript" src="{{ asset('js/admin.js') }}" charset="UTF-8"></script>
    <link type="text/css" rel="stylesheet" href="{{ asset('vendor/dropzone/css/dropzone.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css">
@endsection

@section('content')
    @include('components.back')
    {!! form($form) !!}
    {!! form_start($form) !!}
    {!! form_until($form, 'body') !!}

    <button id="btnAudioCollapse" class="btn btn-primary col-12 text-left" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="collapseAudios collapseDropzoneTarget">
        Audios @if(!$form->audios) hinzufügen @else anzeigen @endif
    </button>

    @if ($form->audios)
        <div class="collection-container">
            {!! form_row($form->audios) !!}
        </div>
    @endif

    @include('admin.templates.newAudios')
    {!! form_rest($form) !!}
    {!! form_end($form) !!}
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

        function sendImageFile(file) {
            var data = new FormData();
            data.append("image", file);
            data.append('_token', '{{csrf_token()}}');

            $.ajax({
                data: data,
                type: "POST",
                url: "/admin/file/upload",
                enctype: 'multipart/form-data',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.debug(response);
                    alert("success");
                },
                error: function(xhr, err, errThrown ) {
                    alert(xhr.responseText);
                }
            });
        }

        $('.multi-collapse').on('shown.bs.collapse', function () {
            $('html, body').animate({ scrollTop: ($('#btnImageCollapse').offset().top)}, 'slow');
        });
        var ID = {{ $id ?? 'null' }},
            uploadWebPath = "{!! config('filesystems.disks.audio_upload.webRoot') !!}",
            dropzoneOptions = {
                type: 'Audio',
                paramName: 'audio',
                maxFilesize: 10,
                dictInvalidFileType: 'Falscher Datei-Typ! Erlaubt sind folgende Typen: .mp3, .m4a',
                acceptedFiles: ".mp3,.m4a",
                url: "/admin/file/uploadAudio",
            };

        initDropzone(dropzoneOptions);

    </script>
@endsection

