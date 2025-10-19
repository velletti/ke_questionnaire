class ExportCsv {
    constructor() {
        this.clickableButtons = document.querySelectorAll("#kequest_export-start");
        this.initializeClickableButtons();
    }

    initializeClickableButtons() {
        this.clickableButtons.forEach(button => {
            button.addEventListener('click', (evt) => {
                evt.preventDefault();
                const progress = document.querySelector("#kequest_export-progress");
                const current = parseInt(progress.getAttribute('aria-valuenow'), 10) || 0; // Initialize current
                const max = parseInt(progress.getAttribute('aria-valuemax'), 10) || 0; // Initialize max
                this.LoopUntilFinished(button, current, max);
            });
        });
    }

    LoopUntilFinished(button, current, max) {
        const uid = button.getAttribute('data-uid');
        const target = button.getAttribute('data-target');
        const url = button.getAttribute('data-url');

        const progress = document.querySelector("#kequest_export-progress");
        const fileinfo = document.querySelector("#kequest_export-fileinfo"); // Assuming there's an element with id

        if (progress) {
            progress.setAttribute('aria-valuenow', current);
            progress.style.width = `${(current / max) * 100}%`;
            progress.textContent = `${Math.round((current / max) * 100)}%`;

        }
        let finalurl = url + "&current=" + current + "&max=" + max + "&uid=" + uid + "&target=" + target;

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
                if (data.length) {
                    fileinfo.innerHTML = data.length; // Update file info if available
                }
                if (data.success) {
                    if (data.finished) {
                        alert(data.message);
                        document.querySelector("#kequest_export-start").classList.add('d-none');
                        document.querySelector("#kequest_export-download").classList.remove('d-none');
                    } else {
                        this.LoopUntilFinished(button, data.current, max); // Update current from response
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error exporting CSV:', error);
                alert('An error occurred while exporting the CSV. See browser console for details.');
            })
            .finally(() => {
                button.classList.remove('loading');
            });
    }
}

export default new ExportCsv();