const MyModale = (action, title, text) => {
	const elem = '#myModal',
		$modal = $(elem);
	$modal.on(
		'show.bs.modal', (e) => {
			console.info("modale", e.target);
			$('.modal-title').html(title);
			$('.modal-body').html(text);
		}
	);
	$modal.modal(action);
};
export default MyModale;
