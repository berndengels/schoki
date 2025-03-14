const InitEditor = function (options) {
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
				plugins: 'preview autolink link paste media image preview imagetools code help',
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
			default_link_target: '_blank',
			link_default_protocol: 'https',
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
export default InitEditor;
