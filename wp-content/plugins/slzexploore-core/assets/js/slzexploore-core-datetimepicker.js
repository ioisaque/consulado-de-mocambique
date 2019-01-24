jQuery(document).ready(function($) {
	"use strict";
	$('.datepicker').datetimepicker({
		timepicker:false,
		format:'Y-m-d'
	});
	$('.timepicker').datetimepicker({
		datepicker:false,
		format:'H:i',
		step:15
	});
	$('#datetimepicker').datetimepicker({
		timepicker:true,
		format:'Y-m-d'
	});
});