class Analyse {
    constructor() {
        this.clickableButtons = document.querySelectorAll("#kequest_analyse-start");
        this.initializeClickableButtons();
    }

    initializeClickableButtons() {
        this.clickableButtons.forEach(button => {
            button.addEventListener('click', (evt) => {
                evt.preventDefault();
                const progress = document.querySelector("#kequest_analyse-progress");
                const current =  1; // Initialize current
                const max = parseInt(progress.getAttribute('aria-valuemax'), 10) || 0; // Initialize max
                this.LoopUntilFinished(button, current, max);
            });
        });
    }

    LoopUntilFinished(button, current, max) {
        button.classList.add('running'); // Add running class after completion
        const id = button.getAttribute('data-id');
        const target = button.getAttribute('data-target');
        const url = button.getAttribute('data-url');
        const onlyFinished = document.querySelector("#kequest_analyse-only-finished").checked ? 1 : 0; // Assuming there's a checkbox to filter only finished exports
        const progress = document.querySelector("#kequest_analyse-progress");
        // question Uid
        const uidDiv = document.querySelector("#keq_analyse_question_" + current);
        const uid = uidDiv ? uidDiv.getAttribute('data-uid') : null;
        if (progress) {
            progress.setAttribute('aria-valuenow', current);
            progress.style.width = `${(current / max) * 100}%`;
            progress.textContent = `${Math.round((current / max) * 100)}%`;
        }
        let finalurl = url + "&current=" + current + "&max=" + max + "&uid=" + uid  + "&onlyFinished=" + onlyFinished;

        fetch(finalurl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (data.questionuid) {
                        document.querySelector("#keq_analyse_total_" + data.questionuid).innerHTML = data.total ? data.total : 0; // Update total for the question
                    }
                    if (data.answers) {
                        data.answers.forEach(answer => {
                            // keq_analyse_answer_{answer.uid}
                            const answerDiv = document.querySelector("#keq_analyse_answer_" + answer.uid);
                            if (answerDiv) {
                                answerDiv.setAttribute('aria-valuenow', answer.value ? answer.value : 0);
                                answerDiv.setAttribute('aria-valuemax', answer.max ? answer.max : 0);
                                answerDiv.style.width = answer.width ? answer.width : '0%';
                                answerDiv.innerHTML = answer.html; // Update answer HTML
                            }
                        });
                    }
                    if (data.finished) {
                        document.querySelector("#kequest_analyse-start").classList.add('d-none');
                    } else {
                        this.LoopUntilFinished(button, data.current, max); // Update current from response
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error on analyse:', error);
                alert('An error occurred while Analyse. See browser console for details.');
            })
            .finally(() => {
                button.classList.remove('running'); // Remove running class after completion
            });
    }
}

export default new Analyse();