
function updatePattern() {
    const platform = document.getElementById("platform").value;
    const addressInput = document.getElementById("listening_channel");

    if (platform === "web") {
        // Web: Allow multiple valid URLs separated by commas
        addressInput.pattern = "^\\s*(https?:\\/\\/[^\\s,]+)\\s*(,\\s*https?:\\/\\/[^\\s,]+\\s*)*$";
        addressInput.title = "Enter valid URLs separated by commas (e.g., https://example.com, https://site.com)";
        addressInput.placeholder = '';
    } else if (platform === "telegram") {
        // Telegram: Allow multiple channel/usernames or numeric IDs separated by commas
        addressInput.pattern = "^\\s*([a-zA-Z0-9_]{5,})\\s*(,\\s*[a-zA-Z0-9_]{5,}\\s*)*$";
        addressInput.title = "Enter Telegram channel IDs or usernames separated by commas (e.g., @channel, 123456789)";
        addressInput.placeholder = '';
    }
}

document.addEventListener("DOMContentLoaded", () => {
    updatePattern();
    const form = document.getElementById("addForm");
    // Add event listener for form submission
    form.addEventListener("submit", function (event) {
        if (!validateCheckboxes()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });

    const watermarkInput = document.getElementById('watermark');
    const watermarkPreview = document.getElementById('watermarkPreview');
    const watermarkPosSelect = document.getElementById('watermark_pos');
    const WatermarkContiner = document.getElementById("WatermarkContiner");
    const watermarkDelete = document.getElementById("watermarkDelete");
    const watermarkValue = document.getElementById("watermarkValue");

    // Show uploaded image in the preview box
    watermarkInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                watermarkPreview.src = e.target.result;
                watermarkPreview.classList.remove('hidden');
                WatermarkContiner.style.display = "flex";
            };
            reader.readAsDataURL(file);
        } else {
            watermarkPreview.src = '#';
            watermarkPreview.classList.add('hidden');
        }
    });

    // Update watermark position based on dropdown selection
    watermarkPosSelect.addEventListener('change', function () {
        const position = this.value;

        // Remove all position-related classes
        Array.from(watermarkPreview.classList).forEach(cls => {
            if (!['watermark-preview-image', 'hidden'].includes(cls)) {
                watermarkPreview.classList.remove(cls);
            }
        });

        // Add the selected position class
        if (position) {
            watermarkPreview.classList.add(position);
        }
    });


    watermarkDelete.addEventListener('click', function (event) {
        watermarkInput.value = ""; // Try resetting the value first
        watermarkValue.value = '';
        if (watermarkInput.value) { // If not cleared due to security, replace it
            let newInput = watermarkInput.cloneNode(true);
            watermarkInput.parentNode.replaceChild(newInput, watermarkInput);
        }
        WatermarkContiner.style.display = "none";
        watermarkPreview.classList.add('hidden');
    });

    if (watermarkPreview.src.indexOf('#') != -1) {
        WatermarkContiner.style.display = "none"
        watermarkPreview.classList.add('hidden')
    } else {
        WatermarkContiner.style.display = "flex"
        watermarkPreview.classList.remove('hidden');
        watermarkPosSelect.dispatchEvent(new Event('change'));
    }

});

function validateCheckboxes() {
    const checkboxes = document.querySelectorAll('.crawler-networks');
    const errorMessage = document.getElementById("checkboxError");

    if (!checkboxes.length) {
        errorMessage.style.display = "block";
        return false;
    }

    let isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    if (!isChecked) {
        errorMessage.style.display = "block";
        checkboxes[0].focus();
        return false;
    } else {
        errorMessage.style.display = "none";
        return true;
    }
}
