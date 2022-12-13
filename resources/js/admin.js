var initEditor = function (options) {
    $(document).on(
        'focusin', function (e) {
            if ($(e.target).closest(".mce-window").length) {
                e.stopImmediatePropagation();
            }
        }
    );
    tinymce.init(
        {
            selector: options.selector,
            content_style: options.content_style ? options.content_style : '',
            plugins:  options.plugins,
            toolbar: options.toolbar,
            width: options.width,
            height: options.height,
            importcss_append: true,
            menubar: false,
            lang: 'de',
            setup: function (editor) {
                tinymce.on(
                    'media-dialog-open', function () {
                        var msg = 'Hier unter "Source" nur die URL des Objekts eintragen (z.B: https://youtu.be/vUU2HCaXtbQ).',
                        $msg = $('<div class="text-danger">').html(msg);
                        $('.tox-form','.tox-dialog').append($msg);
                    }
                );
            },
            mobile: {
                theme: 'mobile',
                plugins: 'preview code autolink link paste media image preview imagetools help',
                toolbar: 'undo redo | bold italic | link autolink paste image code help',
            },
            font_css: [
                '//fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,400;0,700;1,400;1,700&display=swap',
            ],
            content_css: '/css/tiny.css',
            body_class: 'eventContent',
            formats: {
                alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'left' },
                aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'center' },
                alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'right' },
                alignfull: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'full' },
                bold: { inline: 'b' },
                italic: { inline: 'i' },
                //            underline: { inline: 'span', 'classes': 'underline', exact: true },
                underline: { inline: 'u' },
                strikethrough: { inline: 'del' },
                //            customformat: { inline: 'span', styles: { color: '#00ff00', fontSize: '20px' }, attributes: { title: 'My custom format' }, classes: 'example1' }
            },
            style_formats: [
            { title: 'Custom format', format: 'customformat' },
            { title: 'Align left', format: 'alignleft' },
            { title: 'Align center', format: 'aligncenter' },
            { title: 'Align right', format: 'alignright' },
            { title: 'Align full', format: 'alignfull' },
            { title: 'Bold text', inline: 'strong' },
            { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' },
            { title: 'Image formats' },
            { title: 'Image Left', selector: 'img', styles: { 'float': 'left', 'margin': '0 10px 0 10px' } },
            { title: 'Image Right', selector: 'img', styles: { 'float': 'right', 'margin': '0 0 10px 10px' } },
            ],
            media_live_embeds: false,
            media_dimensions: false,
            paste_data_images: false,
            image_advtab: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 h4 blockquote',
            quickbars_insert_toolbar: false,
            quickbars_image_toolbar: false,
            paste_as_text: options.paste_as_text,
            paste_block_drop: options.paste_block_drop,
            media_url_resolver: function (data, resolve/*, reject*/) {
                // <iframe src="//www.youtube.com/embed/vUU2HCaXtbQ" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                // https://youtu.be/XQUDsRafP0Q

                var token = data.url.split('/').pop(),
                url = "//www.youtube.com/embed/" + token + "?autoplay=0";
                console.log('media_url_resolver', data.url, token);
                if (data.url.indexOf('youtu') !== -1) {
                    var embedHtml = '<iframe class="d-block mx-auto" src="' + url + '" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';
                    resolve({html: embedHtml});
                } else {
                    resolve({html: ''});
                }
            },
            init_instance_callback: function (editor) {
                editor.on('ExecCommand', e => {
                        console.info(e.command, e.value)
                        switch(e.command) {
                            case 'mceMedia':
                                tinymce.fire('media-dialog-open', {
                                    'editor': tinymce.EditorManager.activeEditor
                                });
                                break;
                        }
                    }
                )
                .on('BeforeSetContent', e => {
                        console.info('BeforeSetContent', e.type);
                        e.content = $.trim(e.content);
//                        e.content = e.content.replace(/<[^>]+>/ig,'');
//                        e.content = e.content.replace(/(<[^\>]+)(style="[^"]+")/ig,'$1');
                        return e.content;
                    }
                )
                .on('GetContent', function (e) {
                        console.info('GetContent',e.type)
                    }
                );
            },
            external_filemanager_path: "/filemanager/",
            external_plugins: { "filemanager" : "/filemanager/plugin.min.js"},
            filemanager_title: "Responsive Filemanager",
        }
    );
};

var initDatepicker = function (options, datesDisabled) {
    if (undefined !== datesDisabled) {
        options = $.extend(options, {datesDisabled: datesDisabled});
    }

    return $(".datepicker").datepicker(options);
};

function addNewImage(response, type)
{
    var filename = response.internal_filename,
        $newImages = $('#newImagesWrapper'),
        $imgContainer = $('#newImages','#newImagesWrapper'),
        $div = $('<div class="imgItem text-center">');

    $("<img class='addImg'>").attr(
        {
            src: uploadWebPath +"/"+ filename,
            rel: filename
        }
    ).appendTo($div);

    $('<div class="btnWrapper"><input class="btn btn-sm btn-outline-danger btnRemoveAdded" type="button" data-target="'+filename+'" value="löschen"></div>')
        .appendTo($div);
    $("<input name='addedImgages["+filename+"]' class='addImg' type='hidden' rel='"+filename+"'>")
        .val(JSON.stringify(response))
        .appendTo($div);

    $div.appendTo($imgContainer);

    $newImages.show();

    $(".btnRemoveAdded").click(
        function () {
            var target = $(this).data('target');
            removeFile(target, 'Image');
            console.info(target);
        }
    );
}

function addNewImage(response)
{
    var filename = response.internal_filename,
        $newImages = $('#newImagesWrapper'),
        $imgContainer = $('#newImages','#newImagesWrapper'),
        $div = $('<div class="ImageItem text-center">');

    $("<img class='addImage'>").attr(
        {
            src: uploadWebPath +"/"+ filename,
            rel: filename
        }
    ).appendTo($div);

    $('<div class="btnWrapper"><input class="btn btn-sm btn-outline-danger btnRemoveAdded" type="button" data-target="'+filename+'" value="löschen"></div>')
        .appendTo($div);
    $("<input name='addedImgages["+filename+"]' class='addImage' type='hidden' rel='"+filename+"'>")
        .val(JSON.stringify(response))
        .appendTo($div);

    $div.appendTo($imgContainer);

    $newImages.show();

    $(".btnRemoveAdded").click(
        function () {
            var target = $(this).data('target');
            removeFile(target, 'Image');
            console.info(target);
        }
    );
}

function addNewAudio(response)
{
    var filename = response.internal_filename,
        $newFiles = $('#newAudiosWrapper'),
        $container = $('#newAudios','#newAudiosWrapper'),
        $div = $('<div class="AudioItem text-center">');

    $("<img class='addAudio'>").attr(
        {
            src: uploadWebPath +"/"+ filename,
            rel: filename
        }
    ).appendTo($div);

    $('<div class="btnWrapper"><input class="btn btn-sm btn-outline-danger btnRemoveAdded" type="button" data-target="'+filename+'" value="löschen"></div>')
        .appendTo($div);
    $("<input name='addedAudios["+filename+"]' class='addAudio' type='hidden' rel='"+filename+"'>")
        .val(JSON.stringify(response))
        .appendTo($div);

    $div.appendTo($container);

    $newFiles.show();

    $(".btnRemoveAdded").click(
        function () {
            var target = $(this).data('target');
            removeFile(target, 'Audio');
            console.info(target);
        }
    );
}

function removeFile(filename, type) {
    var formData = new FormData();
    formData.append('id', ID);
    formData.append('filename', filename);
    formData.append('type', type);
    formData.append('_token', $('[name="_token"]').val());

    $.post(
        {
            url: '/admin/file/deleteDropfile',
            data: formData,
            processData: false,
            contentType: false,
            success(response) {
                console.info('deleteDropfile');
                console.info(response);
                if(response.success) {
                    $("[rel='"+filename+"']").parent('div.' + type + 'Item').remove();
                }
            },
            error(xhr,err) {
                console.error('Remove error');
                console.error(err);
            }
        }
    );
}

var initCropper = function (filename, filenameOrig, img, myDropzone) {
    cropperOptions = {
        autoCrop: true,
        viewMode: 1,
        initialAspectRatio: 16/9,
        //                aspectRatio: 4/3,
        rotatable: false,
        //                minCanvasHeight: maxImageHeight,
        minCropBoxHeight: maxImageHeight || 300,
        minCropBoxWidth: 300,
    };

    if (!img) {
        console.error("image is: " +img);
        return false;
    }
    console.info('cropper ready');
    cropper = new Cropper(img, cropperOptions);

    img.addEventListener(
        'ready', function () {
            this.cropper.crop();
        }
    );

    $('.crop-zoom-in').on(
        'click', function () {
            cropper.zoom(0.1);
        }
    );
    $('.crop-zoom-out').click(
        function () {
            cropper.zoom(-0.1);
        }
    );
    $('.crop-reset').click(
        function () {
            cropper.reset();
        }
    );
    $('.crop-cancel').click(
        function () {
            dropzoneReset = true;
            $('#imgEditor').collapse("hide");
        }
    );
    $(".crop-save").unbind('click').bind(
        'click', function () {
            dropzoneReset = true;
            var canvas = cropper.getCroppedCanvas();

            console.info('btnSaveCrop clicked');

            canvas.toBlob(
                function (blob) {

                    var formData = new FormData();
                    formData.append('id', ID);
                    formData.append('filename', filename);
                    formData.append('filenameOrig', filenameOrig);
                    formData.append('croppedImage', blob);
                    formData.append('_token', $('[name="_token"]').val());

                    $.post({
                        url: '/admin/file/uploadCropped',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success(response) {
                            myDropzone.removeFile(blob);
                            if(cropper) {
                                cropper.clear();
                            }
                            $('#imgEditor').collapse("hide");
                            $(document).trigger('cropperSaved', response);
                        },
                        error(xhr,err) {
                             console.error('Upload error');
                             console.error(err);
                        },
                    });
                },'image/jpeg', 0.7
            );
        }
    );
    $('html, body').animate({ scrollTop: ($('#imgEditor').offset().top)}, 'slow');

    return cropper;
};

var initDropzone = function (options) {

    var c = 0,
        currentFile,
        fileName = null,
        fileNameOrig = null,
        myDropzone,
        $cropperView,
        dropzoneReset = false,
        dropzoneOptions = {
            url: options.url || "/admin/file/upload",
            type: options.type,
            params: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            paramName: options.paramName || "image",
            maxFilesize: options.maxFilesize || 10, // MB
            maxFiles: 1,
            uploadMultiple: false,
            addRemoveLinks: true,
            parallelUploads: 1,
            dictFileSizeUnits: 'mb',
            dictRemoveFile: 'Datei löschen',
            dictFileTooBig: 'Diese Datei ist %FILE_SIZE% MB gross. Es sind aber max. 2MB erlaubt',
            dictDefaultMessage: 'Datei hier hineinziehen oder Klicken für Dateiauswahl.<br>Wenn es ein Bild ist, danach bitte korrekt beschneiden.',
            dictMaxFilesExceeded: 'Es darf nur eine Datei gleichzeitig hochgeladen werden!',
            //                dictInvalidFileType: 'Falscher Datei-Typ! Erlaubt sind folgende Typen: .jpeg, .jpg, .png, .gif',
            //                acceptedFiles: ".jpeg,.jpg,.png,.gif",
            dictInvalidFileType: options.dictInvalidFileType || 'Falscher Datei-Typ! Erlaubt sind folgende Typen: .jpeg, .jpg',
            acceptedFiles: options.acceptedFiles || ".jpeg,.jpg",
            timeout: 10000,
        },
        dropzoneTarget = "#dropzoneTarget";

    if("Image" === dropzoneOptions.type) {
        initCropperView = function () {
            var $container = $('#imgEditor');
            return $container;
        };
    }

    Dropzone.autoDiscover = false;
    myDropzone = new Dropzone(dropzoneTarget, dropzoneOptions);
    myDropzone.on(
            "maxfilesexceeded", function (file) {
                this.removeFile(file);
            }
        )
        .on(
            "error", function (file, error) {
                var fileSize = (file.size/1000000).toFixed(1),
                errMsg = error.replace('%FILE_SIZE%', fileSize);
                alert(errMsg);
                this.removeFile(file);
            }
        )
        .on(
            "complete", function (file) {
                dropzoneReset = true;
                this.removeFile(file);
            }
        )
        .on(
            "success", function (file, result) {
                currentFile = file;
                fileName = result.internal_filename;
                fileNameOrig = currentFile.name;
                dropzoneReset = false;

                if("Image" === dropzoneOptions.type) {
                    $cropperView = initCropperView();
                    $cropperView.collapse('show');

                    var image = document.createElement('img');
                    image.id = 'img'+c;
                    image.src = '/uploads/' + fileName;
                    image.className = 'img-responsive';
                    $cropperView.find('.img-container').html(image);
                    cropper = initCropper(fileName, fileNameOrig, image, myDropzone);
                }
                c++;
            }
        )
        .on(
            "removedfile", function (file) {
                if(dropzoneReset) {
                    return;
                }
                $.post(
                    {
                        url: '/admin/file/delete',
                        data: {
                            filename: fileName,
                            _token: $('[name="_token"]').val()
                        },
                        dataType: 'json',
                        success: function (data) {
                            switch(dropzoneOptions.type) {
                                case "Image":
                                    $('#imgEditor').collapse("hide");
                                    $('[rel="'+fileName+'"]','#newImages').remove();
                                    break;
                                case "Audio":
                                    $('[rel="'+fileName+'"]','#newAudios').remove();
                                    break;
                            }
                        }
                    }
                );
            }
        );
    $(document).on(
        'cropperSaved', function (evt, response) {
            switch(dropzoneOptions.type) {
                case 'Image':
                    addNewImage(response);
                    break;
                case 'Audio':
                    addNewAudio(response);
                    break;
            }
        }
    );

    return myDropzone;
};

var dataURItoBlob = function (dataURI) {
    var byteString = atob(dataURI.split(',')[1]);
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], {type: 'image/jpeg'});
};
var myModale = function (action, title, text) {
    var elem = '#myModal',
        $modal = $(elem);
    $modal.on(
        'show.bs.modal', function () {
            $('.modal-title', this).html(title);
            $('.modal-body', this).html(text);
        }
    );
    $modal.modal(action);
};
var isMobile = false; //initiate as false
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))
) {
    isMobile = true;
}
$.ajaxSetup(
    {
        headers: {'X-CSRF-Token': $('[name="csrf-token"]').attr('content')}
    }
);
