<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            min-height: 100vh;
        }

        .widget-container {
            max-width: 480px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 32px;
        }

        .widget-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .widget-header h2 {
            color: #1a1a1a;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .widget-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .form-group label .required {
            color: #e53935;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: #4a90d9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.1);
        }

        .form-control.error {
            border-color: #e53935;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .file-input-wrapper {
            position: relative;
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .file-input-wrapper:hover {
            border-color: #4a90d9;
            background-color: #f8fafc;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-input-text {
            color: #666;
            font-size: 14px;
        }

        .file-input-text strong {
            color: #4a90d9;
        }

        .file-list {
            margin-top: 12px;
            font-size: 13px;
            color: #666;
        }

        .file-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #f5f5f5;
            border-radius: 6px;
            margin-top: 6px;
        }

        .file-list-item button {
            background: none;
            border: none;
            color: #e53935;
            cursor: pointer;
            font-size: 18px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #4a90d9;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background: #3a7fc8;
        }

        .btn-submit:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .error-text {
            color: #e53935;
            font-size: 12px;
            margin-top: 4px;
        }

        .hidden {
            display: none;
        }

        .contact-hint {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="widget-container">
        <div class="widget-header">
            <h2>Contact Us</h2>
            <p>We'd love to hear from you. Send us a message!</p>
        </div>

        <div id="alert" class="alert hidden"></div>

        <form id="ticketForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control" required>
                <div class="error-text hidden" id="name-error"></div>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="+79001234567">
                <div class="contact-hint">Enter in international format (E.164)</div>
                <div class="error-text hidden" id="phone-error"></div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com">
                <div class="contact-hint">Phone or email is required</div>
                <div class="error-text hidden" id="email-error"></div>
            </div>

            <div class="form-group">
                <label for="subject">Subject <span class="required">*</span></label>
                <input type="text" id="subject" name="subject" class="form-control" required>
                <div class="error-text hidden" id="subject-error"></div>
            </div>

            <div class="form-group">
                <label for="text">Message <span class="required">*</span></label>
                <textarea id="text" name="text" class="form-control" required></textarea>
                <div class="error-text hidden" id="text-error"></div>
            </div>

            <div class="form-group">
                <label>Attachments</label>
                <div class="file-input-wrapper">
                    <input type="file" id="files" name="files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                    <div class="file-input-text">
                        <strong>Click to upload</strong> or drag and drop<br>
                        <small>JPG, PNG, PDF, DOC (max 10MB each, up to 5 files)</small>
                    </div>
                </div>
                <div id="file-list" class="file-list"></div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">Send Message</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('ticketForm');
        const alert = document.getElementById('alert');
        const submitBtn = document.getElementById('submitBtn');
        const fileInput = document.getElementById('files');
        const fileList = document.getElementById('file-list');

        let selectedFiles = [];

        fileInput.addEventListener('change', function(e) {
            const newFiles = Array.from(e.target.files);
            selectedFiles = [...selectedFiles, ...newFiles].slice(0, 5);
            updateFileList();
        });

        function updateFileList() {
            fileList.innerHTML = selectedFiles.map((file, index) => `
                <div class="file-list-item">
                    <span>${file.name} (${formatFileSize(file.size)})</span>
                    <button type="button" onclick="removeFile(${index})">&times;</button>
                </div>
            `).join('');
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileList();
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function showAlert(message, type) {
            alert.textContent = message;
            alert.className = `alert alert-${type}`;
            alert.classList.remove('hidden');
            alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function hideAlert() {
            alert.classList.add('hidden');
        }

        function clearErrors() {
            document.querySelectorAll('.error-text').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('error'));
        }

        function showError(field, message) {
            const input = document.getElementById(field);
            const errorEl = document.getElementById(`${field}-error`);
            if (input) input.classList.add('error');
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.classList.remove('hidden');
            }
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            hideAlert();
            clearErrors();

            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            const formData = new FormData();
            formData.append('name', document.getElementById('name').value);
            formData.append('phone', document.getElementById('phone').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('subject', document.getElementById('subject').value);
            formData.append('text', document.getElementById('text').value);

            selectedFiles.forEach(file => {
                formData.append('files[]', file);
            });

            try {
                const response = await fetch('/api/tickets', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    showAlert('Thank you! Your message has been sent successfully.', 'success');
                    form.reset();
                    selectedFiles = [];
                    updateFileList();
                } else if (response.status === 422) {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            showError(field, data.errors[field][0]);
                        });
                    }
                    showAlert('Please fix the errors below.', 'error');
                } else if (response.status === 429) {
                    showAlert(data.message || 'You can only submit one ticket per day.', 'error');
                } else {
                    showAlert('An error occurred. Please try again later.', 'error');
                }
            } catch (error) {
                showAlert('Network error. Please check your connection.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send Message';
            }
        });
    </script>
</body>
</html>
