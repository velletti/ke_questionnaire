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
	const $forms = jQuery('form.keq_questionnaire_form');
	let submissionEvent = null;
	if ($forms.length) {
		window.addEventListener('beforeunload', function (event) {
			// A valid questionnaire submission may navigate without a warning.
			if (submissionEvent && !submissionEvent.isDefaultPrevented()) {
				return;
			}
			event.preventDefault();
			event.returnValue = '';
		});
		window.addEventListener('pageshow', function () {
			submissionEvent = null;
		});
	}
	const $loading = jQuery('#keq_loadingDiv').hide();
	jQuery(document).ajaxStart(
		function () {	$loading.show(); }).ajaxStop(function () {
			$loading.hide();
		});

	$forms.find(".keqButtons input.prev").on("click", function (event) {
		event.preventDefault();
		const $form = jQuery(this).closest('form');
		const currentPage = Number($form.find('input.currentPage').val());
		$form.find('input.requestedPage').val(currentPage - 1);
		$form.submit();
	});

	window.submitToPage = function ($page) {
		$forms.find('input.requestedPage').val($page);
		$forms.submit();
	};

	window.checkAnswerErrors = function () {
		let allValid = true;
		$forms.each(function () {
			const $form = jQuery(this);
			const answerLimits = new Map();
			$form.find('.hasMinAnswers, .hasMaxAnswers').each(function () {
				const $box = jQuery(this);
				// Hidden identity/error fields are not answers. Count filled text
				// fields as well as selected checkbox, radio and select answers.
				const count = $box.find('input, textarea, select').filter(function () {
					if (this.disabled || /^(hidden|submit|button|reset|image)$/i.test(this.type)) return false;
					if (/^(checkbox|radio)$/i.test(this.type)) return this.checked;
					const value = jQuery(this).val();
					if (Array.isArray(value)) return value.some(function (item) { return String(item).trim() !== ''; });
					return value != null && String(value).trim() !== '';
				}).length;
				const min = Number($box.data('minanswers')) || 0;
				const max = Number($box.data('maxanswers')) || 0;
				const invalid = count < min || (max > 0 && count > max);
				$box.closest('.keqQuestion').find("input[id^='keqAnswerErrorQ']").val(invalid ? 1 : 0);
				if (invalid) {
					answerLimits.set($box.closest('.keqQuestion')[0], {
						grund: count < min ? 'min Answers' : 'max Answers',
						ausgewaehlt: count,
						minimum: min,
						maximum: max
					});
				}
			});
			const valid = $form.find("input[id^='keqAnswerError']").filter(function () {
				if (jQuery(this).val() != '1') return false;
				const $flag = jQuery(this);
				const $question = $flag.closest('.keqQuestion');
				const questionId = $question.attr('id');
				const isQuestionError = this.id.indexOf('keqAnswerErrorQ') === 0;
				const fieldId = isQuestionError ? questionId : questionId + 'a' + this.id.replace('keqAnswerError', '');
				const info = document.getElementById(fieldId + 'info');
				const limit = isQuestionError && answerLimits.get($question[0]);
				console.warn('[keq] Submit gesperrt: Antwortprüfung', Object.assign({
					feldId: fieldId,
					frageId: questionId,
					fehlerFeldId: this.id,
					grund: info && jQuery(info).text().trim() || 'Antwort ungültig oder Prüfung noch nicht abgeschlossen'
				}, limit || {}));
				return true;
			}).length === 0;
			$form.find("input[type='submit'], button[type='submit']").prop('disabled', !valid);
			allValid = allValid && valid;
		});
		return allValid;
	};
	window.checkMandatory = function () {
		return jQuery("input[id^='keqMandatory']").filter(function () {
			const missing = !jQuery(this).parent().parent().is(":hidden") && jQuery(this).val() == 1;
			if (missing) logMandatoryError(this);
			return missing;
		}).length == 0;
	};
	function logMandatoryError(flag) {
		const $question = jQuery(flag).closest('.keqQuestion');
		console.warn('[keq] Submit gesperrt: Pflichtfeldprüfung', {
			frageId: $question.attr('id'),
			feldIds: $question.find('input:not([type="hidden"]), textarea, select').map(function () {
				return this.id;
			}).get(),
			fehlerFeldId: flag.id,
			grund: 'Pflichtfrage nicht beantwortet (mandatory)'
		});
	}

	window.checkMandatorySubmit = function () {
		let isCorrect = true;
		jQuery("input[id^='keqMandatory']").each(function () {
			if (jQuery(this).val() == "1") {
				if (!jQuery(this).parent().parent().is(":hidden")) {
					logMandatoryError(this);
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
		const answersValid = window.checkAnswerErrors();
		const mandatoryValid = window.checkMandatory();
		return answersValid && mandatoryValid;
	};

	$forms.on("input change", ".hasMinAnswers input, .hasMinAnswers textarea, .hasMinAnswers select, .hasMaxAnswers input, .hasMaxAnswers textarea, .hasMaxAnswers select", function () {
		window.checkAnswerErrors();
	});

	$forms.on("submit", function (event) {
		submissionEvent = null;
		// Recheck current values, including autofill, before using cached validation flags.
		jQuery(this).find('.keqAnswerSingleInput input[type="text"]').trigger('keqValidate');
		const mandatoryValid = window.checkMandatorySubmit();
		const answersValid = window.checkAnswerErrors();
		if (!mandatoryValid || !answersValid) {
			console.warn('[keq] Submit abgebrochen', { mandatoryValid: mandatoryValid, answersValid: answersValid });
			return false;
		}
		submissionEvent = event;
	});

	window.checkAnswerErrors();
	window.checkMandatory();

});
