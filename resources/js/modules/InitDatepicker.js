const InitDatepicker = (options, datesDisabled) => {
	if (undefined !== datesDisabled) {
		options = $.extend(options, {datesDisabled: datesDisabled});
	}
	return $(".datepicker").datepicker(options);
};
export default InitDatepicker;
