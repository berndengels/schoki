/*
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
*/
/*
const dataURItoBlob = (dataURI) => {
	var byteString = atob(dataURI.split(',')[1]);
	var ab = new ArrayBuffer(byteString.length);
	var ia = new Uint8Array(ab);
	for (var i = 0; i < byteString.length; i++) {
		ia[i] = byteString.charCodeAt(i);
	}
	return new Blob([ab], {type: 'image/jpeg'});
};
*/
const addNewImage = (response) => {
	const filename = response.internal_filename,
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

const addNewAudio = (response) => {
	const filename = response.internal_filename,
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

	$(".btnRemoveAdded").click(function () {
			var target = $(this).data('target');
			removeFile(target, 'Audio');
			console.info(target);
		}
	);
}

const removeFile = (filename, type) => {
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

const InitDropzone = (options) => {
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
			maxFilesize: options.maxFilesize / 1000000 || 10, // MB
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

	const initCropperView = () => {
		var $container = $('#imgEditor');
		return $container;
	};

	Dropzone.autoDiscover = false;
	myDropzone = new Dropzone(dropzoneTarget, dropzoneOptions);
	myDropzone.on("maxfilesexceeded", function (file) {
			this.removeFile(file);
		}).on("error", function (file, error) {
				var fileSize = (file.size/1000000).toFixed(1),
					errMsg = error.replace('%FILE_SIZE%', fileSize);
				alert(errMsg);
				this.removeFile(file);
			}
		).on("complete", function (file) {
				dropzoneReset = true;
				this.removeFile(file);
			}
		).on("success", function (file, result) {
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
					const cropper = InitCropper(fileName, fileNameOrig, image, myDropzone);
				}
				c++;
			}
		).on("removedfile", function (file) {
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

export default InitDropzone;
