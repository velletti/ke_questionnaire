location.hash = '#no-';
if (location.hash == '#no-') {
	location.hash = '#_';
	window.onhashchange = function () {
		if (location.hash == '#no-') {
			location.hash = '#_';
		}
	};
}

jQuery(document).ready(function () {
	const $loading = $('#keq_loadingDiv').hide();
	jQuery(document).ajaxStart(
		function () {	$loading.show(); }).ajaxStop(function () {
			$loading.hide();
		});

	jQuery(".keqButtons input.prev").on("click", function () {
		const $currentPage = jQuery(".tx-ke-questionnaire input.currentPage").val();
		jQuery(".tx-ke-questionnaire input.requestedPage").val($currentPage - 1);
		jQuery(".tx-ke-questionnaire form").submit();
	});

	window.submitToPage = function ($page) {
		jQuery(".tx-ke-questionnaire input.requestedPage").val($page);
		jQuery(".tx-ke-questionnaire form").submit();
	};

	window.checkAnswerErrors = function () {
		jQuery(".hasMinAnswers").each(function() {
			console.log("MinAnswers " + jQuery(this).data('minanswers')) ;
			let counter=0 ;
			jQuery(this).find("input[type='checkbox']").each(
				function(){
					if( jQuery(this).prop("checked")) {
						counter++;
					}

				}
			)
			console.log("Answer count " + counter ) ;
			if (counter >= jQuery(this).data('minanswers')) {
				jQuery(this).parent().find("input[id^='keqAnswerError']").val(0) ;
			} else {
				jQuery(this).parent().find("input[id^='keqAnswerError']").val(1) ;
			}
		})

		jQuery(".hasMaxAnswers").each(function() {
			console.log("MinAnswers " + jQuery(this).data('maxanswers')) ;
			let counter=0 ;
			jQuery(this).find("input[type='checkbox']").each(
				function(){
					if( jQuery(this).prop("checked")) {
						counter++;
					}

				}
			)
			console.log("Answer count " + counter ) ;
			if (counter <= jQuery(this).data('maxanswers')) {
				// ToDo has Min answers and still error ? do nothing
				if ( jQuery(this).data('minanswers') && jQuery(this).parent().find("input[id^='keqAnswerError']").val() == 1 ) {
					// do nothing
				} else {
					jQuery(this).parent().find("input[id^='keqAnswerError']").val(0) ;
				}
			} else {
				jQuery(this).parent().find("input[id^='keqAnswerError']").val(1) ;
			}
		})

		if (jQuery("input[id^='keqAnswerError']").filter("input[value='1']").length == 0) {
			jQuery("input[type='submit']").removeAttr("disabled");
			return true;
		} else {
			jQuery("input[type='submit']").attr("disabled", "disabled");
			return false;
		}
	};
	window.checkMandatory = function () {
		return jQuery("input[id^='keqMandatory']").filter(function () {
			return !jQuery(this).parent().parent().is(":hidden") && jQuery(this).val() == 1;
		}).length == 0;
	};

	window.checkMandatorySubmit = function () {
		let isCorrect = true;
		jQuery("input[id^='keqMandatory']").each(function () {
			if (jQuery(this).val() == "1") {
				if (!jQuery(this).parent().parent().is(":hidden")) {
					jQuery(this).parent().fadeIn();
					isCorrect = false;
				}
			} else {
				jQuery(this).parent().fadeOut();
			}
		});

		return isCorrect;
	};

	window.keqFormIsValid = function () {
		return window.checkAnswerErrors() && window.checkMandatory();
		// return true;
	};

	jQuery(".tx-ke-questionnaire .hasMinAnswers input[type='checkbox']").on("change", function () {
		window.checkAnswerErrors();
	});

	jQuery(".tx-ke-questionnaire form").on("submit", function () {
		if (!checkMandatorySubmit()) {
			return false;
		}

		if (!keqFormIsValid()) {
			return false;
		}
	});

	window.checkAnswerErrors();
	window.checkMandatory();

});