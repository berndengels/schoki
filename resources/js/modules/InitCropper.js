const InitCropper = (filename, filenameOrig, img, myDropzone) => {
	const cropperOptions = {
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
	const cropper = new Cropper(img, cropperOptions);

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
			InitDropzone.dropzoneReset = true;
			$('#imgEditor').collapse("hide");
		}
	);
	$(".crop-save").unbind('click').bind('click', () => {
			InitDropzone.dropzoneReset = true;
			const canvas = cropper.getCroppedCanvas();

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
export default InitCropper;
