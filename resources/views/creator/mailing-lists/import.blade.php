<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Mailing lister</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $mailingList->name }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Import</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                        Importer: <span style="font-weight: 100;">{{ $mailingList->name }}</span>
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('creator.mailing-lists.edit', $mailingList) }}" class="btn btn-primary">
                        <i class="fa-solid fa-pen me-1"></i> Rediger
                    </a>
                    <a href="{{ route('creator.mailing-lists.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage
                    </a>
                </div>
            </div>
        </div>

        <!-- Horizontal Tab Menu -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.show', $mailingList) }}">
                    <i class="fa-solid fa-list me-1"></i> Mailingliste
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.signup-forms', $mailingList) }}">
                    <i class="fa-solid fa-code me-1"></i> Signup forms
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.qr-code', $mailingList) }}">
                    <i class="fa-solid fa-qrcode me-1"></i> QR Kode
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('creator.mailing-lists.import', $mailingList) }}">
                    <i class="fa-solid fa-file-import me-1"></i> Importer
                </a>
            </li>
        </ul>

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Upload Section -->
                <div class="card mb-4" id="uploadSection">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-file" style="color: var(--primary-color);"></i> Vælg fil
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file" class="form-control" id="importFile" name="file" accept=".csv,.xlsx,.xls" required>
                                <div class="form-text">Accepterede formater: CSV, Excel (.xlsx, .xls)</div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-arrow-right me-1"></i> Næste: Kortlæg felter
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Field Mapping Section (Hidden initially) -->
                <div class="card mb-4" id="mappingSection" style="display: none;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-map" style="color: var(--primary-color);"></i> Kortlæg felter
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="mappingForm">
                            @csrf
                            <input type="hidden" id="fileData" name="fileData">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" style="font-weight: 500;">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="emailField" name="email_field" required>
                                        <option value="">Vælg kolonne...</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" style="font-weight: 500;">Navn</label>
                                    <select class="form-select" id="nameField" name="name_field">
                                        <option value="">Vælg kolonne...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="skipDuplicates" name="skip_duplicates" checked>
                                    <label class="form-check-label" for="skipDuplicates" style="font-weight: 300;">
                                        Spring duplikater over (email-adresser der allerede er i listen)
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="resetUpload()">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Tilbage
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-check me-1"></i> Importér medlemmer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Preview Section (Hidden initially) -->
                <div class="card" id="previewSection" style="display: none;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-eye" style="color: var(--primary-color);"></i> Forhåndsvisning
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm" id="previewTable">
                                <thead>
                                    <tr id="previewHeaders"></tr>
                                </thead>
                                <tbody id="previewBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Instructions -->
                <div class="card mb-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary-color);"></i> Vejledning
                        </h5>
                    </div>
                    <div class="card-body">
                        <div style="font-weight: 300; color: #666; font-size: 14px;">
                            <p><strong style="font-weight: 500;">1. Forbered din fil</strong></p>
                            <p>Sørg for at din fil har en kolonne med email-adresser. Du kan også inkludere navne.</p>

                            <p class="mt-3"><strong style="font-weight: 500;">2. Upload filen</strong></p>
                            <p>Vælg en CSV eller Excel fil fra din computer.</p>

                            <p class="mt-3"><strong style="font-weight: 500;">3. Kortlæg felter</strong></p>
                            <p>Vælg hvilke kolonner der matcher email og navn.</p>

                            <p class="mb-0 mt-3"><strong style="font-weight: 500;">4. Importér</strong></p>
                            <p class="mb-0">Gennemse forhåndsvisningen og klik på "Importér medlemmer".</p>
                        </div>
                    </div>
                </div>

                <!-- Download Template -->
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-download" style="color: var(--primary-color);"></i> Skabelon
                        </h5>
                    </div>
                    <div class="card-body">
                        <p style="font-weight: 300; color: #666; font-size: 14px; margin-bottom: 15px;">
                            Download en skabelon-fil som eksempel på hvordan din fil skal være formateret.
                        </p>
                        <a href="{{ route('creator.mailing-lists.download-template') }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fa-solid fa-file-csv me-1"></i> Download CSV skabelon
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            font-size: 14px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        #previewTable {
            font-size: 13px;
        }
        #previewTable thead th {
            background: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
    </style>

    <script>
        let fileHeaders = [];
        let fileRows = [];

        // Handle file upload
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('importFile');
            const file = fileInput.files[0];

            if (!file) {
                alert('Vælg venligst en fil');
                return;
            }

            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Indlæser...';
            submitBtn.disabled = true;

            try {
                const formData = new FormData();
                formData.append('file', file);

                const response = await fetch('{{ route('creator.mailing-lists.parse-import', $mailingList) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    fileHeaders = data.headers;
                    fileRows = data.rows;

                    // Populate field selects
                    populateFieldSelects(data.headers);

                    // Show preview
                    showPreview(data.headers, data.rows.slice(0, 5));

                    // Show mapping section
                    document.getElementById('uploadSection').style.display = 'none';
                    document.getElementById('mappingSection').style.display = 'block';
                    document.getElementById('previewSection').style.display = 'block';

                    // Store file data
                    document.getElementById('fileData').value = JSON.stringify(data);
                } else {
                    alert('Fejl ved indlæsning af fil: ' + (data.message || 'Ukendt fejl'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Der opstod en fejl ved upload af filen');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Populate field mapping selects
        function populateFieldSelects(headers) {
            const emailSelect = document.getElementById('emailField');
            const nameSelect = document.getElementById('nameField');

            // Clear existing options (except first)
            emailSelect.innerHTML = '<option value="">Vælg kolonne...</option>';
            nameSelect.innerHTML = '<option value="">Vælg kolonne...</option>';

            headers.forEach((header, index) => {
                const emailOption = new Option(header, index);
                const nameOption = new Option(header, index);

                emailSelect.add(emailOption);
                nameSelect.add(nameOption);

                // Auto-select likely matches
                const lowerHeader = header.toLowerCase();
                if (lowerHeader.includes('email') || lowerHeader.includes('e-mail')) {
                    emailSelect.value = index;
                } else if (lowerHeader.includes('name') || lowerHeader.includes('navn')) {
                    nameSelect.value = index;
                }
            });
        }

        // Show preview table
        function showPreview(headers, rows) {
            const headersRow = document.getElementById('previewHeaders');
            const tbody = document.getElementById('previewBody');

            // Clear existing content
            headersRow.innerHTML = '';
            tbody.innerHTML = '';

            // Add headers
            headers.forEach(header => {
                const th = document.createElement('th');
                th.textContent = header;
                headersRow.appendChild(th);
            });

            // Add rows
            rows.forEach(row => {
                const tr = document.createElement('tr');
                row.forEach(cell => {
                    const td = document.createElement('td');
                    td.textContent = cell || '-';
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
        }

        // Handle import submission
        document.getElementById('mappingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const emailField = document.getElementById('emailField').value;
            if (!emailField) {
                alert('Du skal vælge en kolonne til email');
                return;
            }

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Importerer...';
            submitBtn.disabled = true;

            try {
                const formData = {
                    headers: fileHeaders,
                    rows: fileRows,
                    email_field: parseInt(emailField),
                    name_field: document.getElementById('nameField').value ? parseInt(document.getElementById('nameField').value) : null,
                    skip_duplicates: document.getElementById('skipDuplicates').checked
                };

                const response = await fetch('{{ route('creator.mailing-lists.process-import', $mailingList) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    alert(`Import gennemført!\n\n${data.imported} medlemmer importeret\n${data.skipped} duplikater sprunget over`);
                    window.location.href = '{{ route('creator.mailing-lists.show', $mailingList) }}';
                } else {
                    alert('Fejl ved import: ' + (data.message || 'Ukendt fejl'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Der opstod en fejl ved import');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Reset upload form
        function resetUpload() {
            document.getElementById('uploadSection').style.display = 'block';
            document.getElementById('mappingSection').style.display = 'none';
            document.getElementById('previewSection').style.display = 'none';
            document.getElementById('uploadForm').reset();
            fileHeaders = [];
            fileRows = [];
        }
    </script>
</x-app-layout>
