class ExportCsv {
    constructor() {
        this.clickableButtons = document.querySelectorAll("#kequest_export-start");
        this.initializeClickableButtons();
    }

    initializeClickableButtons() {
        this.clickableButtons.forEach(button => {
            button.addEventListener('click', function (evt) {
                evt.preventDefault();

                // get data-uid="{plugin.uid}" data-target="{fileName}" from Button
                const uid = button.getAttribute('data-uid');
                const target = button.getAttribute('data-target');
                // send via ajax as post to backend module kequestionnairebe_exportcsvinterval
                const url = button.getAttribute('data-url');
                // Use the fetch API to send the POST request
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        uid: uid,
                        target: target
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Open new window with the CSV file
                            window.open(data.fileUrl, '_blank');
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error exporting CSV:', error);
                        alert('An error occurred while exporting the CSV.');
                    })
                    .finally(() => {
                        // Remove loading indicator
                        button.classList.remove('loading');
                    });

            });
        });
    }

}
export default new ExportCsv;