require("bootstrap");
require("jstree/dist/jstree.min");
require("cropperjs/dist/cropper.min");

import InitEditor from "./modules/InitEditor";
import InitCropper from "./modules/InitCropper";
import InitDropzone from "./modules/InitDropzone";
import MyModale from "./modules/MyModale";

window.InitEditor = InitEditor;
window.InitCropper = InitCropper;
window.InitDropzone = InitDropzone;
window.MyModale = MyModale;
/*
window.Cropper = require('cropperjs/dist/cropper.min');
window.InitEditor = require('./modules/InitEditor')
window.InitCropper = require('./modules/InitCropper')
window.InitDropzone = require('./modules/InitDropzone')
window.MyModale = require('./modules/MyModale')
*/
$.ajaxSetup({
	headers: {'X-CSRF-Token': $('[name="csrf-token"]').attr('content')}
});

