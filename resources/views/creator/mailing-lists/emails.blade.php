<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Grupper</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $mailingList->name }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Email</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                Gruppe: <span style="font-weight: 100;">{{ $mailingList->name }} ({{ $mailingList->active_members_count }})</span>
            </h1>
        </div>

        <!-- Horizontal Tab Menu -->
        <ul class="nav nav-tabs" style="margin-bottom: 44px;" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.show', $mailingList) }}">
                    <i class="fa-solid fa-circle-user me-1"></i> Medlemmer
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('creator.mailing-lists.emails', $mailingList) }}">
                    <i class="fa-solid fa-paper-plane me-1"></i> Email
                </a>
            </li>
            <li class="nav-item dropdown" role="presentation">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fa-solid fa-plus me-1"></i> Sign up
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.signup-forms', $mailingList) }}">
                        <i class="fa-solid fa-code me-2"></i> Forms
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.qr-code', $mailingList) }}">
                        <i class="fa-solid fa-qrcode me-2"></i> QR Code
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.landing-page', $mailingList) }}">
                        <i class="fa-solid fa-image me-2"></i> Landing page
                    </a></li>
                </ul>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.welcome', $mailingList) }}">
                    <i class="fa-solid fa-heart me-1"></i> Velkomst
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.content', $mailingList) }}">
                    <i class="fa-solid fa-circle-play me-1"></i> Indhold
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.settings', $mailingList) }}">
                    <i class="fa-solid fa-gear me-1"></i> Indstillinger
                </a>
            </li>
        </ul>

        <!-- Page Description -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 style="font-size: 20px; font-weight: 600; color: #333; margin: 0;">
                    <i class="fa-solid fa-paper-plane me-2" style="color: var(--primary-color);"></i>
                    Emails og nyhedsbreve
                </h2>
                <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#composeEmailModal">
                    <i class="fa-solid fa-paper-plane me-1"></i> Skriv til medlemmerne
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Email Archive -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                    Sendte emails
                    @if($emails->count() > 0)
                        (<b>{{ $emails->count() }}</b>)
                    @endif
                </h5>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($emails->count() > 0)
                    @foreach($emails as $email)
                        <div class="email-item" style="cursor: pointer; padding: 16px 20px; {{ $loop->last ? '' : 'border-bottom: 1px solid #e0e0e0;' }} transition: background-color 0.2s;"
                             data-email-id="{{ $email->id }}"
                             data-subject="{{ $email->subject }}"
                             data-recipients="{{ $email->recipients_count }}"
                             data-unique-opens="{{ $email->unique_opens }}"
                             data-total-opens="{{ $email->total_opens }}"
                             data-unique-clicks="{{ $email->unique_clicks }}"
                             data-total-clicks="{{ $email->total_clicks }}"
                             data-sent-at="{{ $email->sent_at ? $email->sent_at->format('d/m/Y H:i') : 'Ikke sendt' }}"
                             onmouseover="this.style.backgroundColor='#f8f9fa'"
                             onmouseout="this.style.backgroundColor='white'">
                            <div style="display: grid; grid-template-columns: minmax(200px, auto) 130px 120px 130px 130px 1fr; gap: 24px; align-items: center;">
                                <div style="font-weight: 600; color: #333; white-space: nowrap;">
                                    <i class="fa-solid fa-envelope me-2" style="font-size: 14px; color: #999;"></i>{{ $email->subject }}
                                </div>
                                <div style="font-weight: 300; color: #666; white-space: nowrap;">
                                    {{ $email->sent_at ? $email->sent_at->format('d/m/Y H:i') : 'Ikke sendt' }}
                                </div>
                                <div style="font-weight: 300; color: #666; white-space: nowrap;">
                                    {{ $email->recipients_count }} medlemmer
                                </div>
                                <div style="font-weight: 300; color: #666; white-space: nowrap;">
                                    @if($email->unique_opens > 0)
                                        {{ $email->unique_opens }} ({{ $email->total_opens }} total)
                                    @else
                                        -
                                    @endif
                                </div>
                                <div style="font-weight: 300; color: #666; white-space: nowrap;">
                                    @if($email->unique_clicks > 0)
                                        {{ $email->unique_clicks }} ({{ $email->total_clicks }} total)
                                    @else
                                        -
                                    @endif
                                </div>
                                <div></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fa-solid fa-paper-plane" style="font-size: 3rem; color: #d1d5db;"></i>
                        <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen sendte emails endnu</p>
                        <p class="small" style="color: #999;">Klik på "Skriv til medlemmerne" for at sende den første email</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Email Bodies Data (stored as JSON for modal display) -->
        @if($emails->count() > 0)
        <script type="application/json" id="emailBodiesData">
        {
            @foreach($emails as $email)
            "{{ $email->id }}": {!! json_encode($email->body_html) !!}{{ $loop->last ? '' : ',' }}
            @endforeach
        }
        </script>
        @endif
    </div>

    <!-- Compose Email Modal -->
    <div class="modal fade" id="composeEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-paper-plane me-2" style="color: var(--primary-color);"></i>
                        Skriv email til {{ $mailingList->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('creator.emails.send') }}" method="POST" id="emailForm">
                    @csrf
                    <input type="hidden" name="mailing_list_id" value="{{ $mailingList->id }}">

                    <div class="modal-body">
                        <!-- Subject -->
                        <div class="mb-3">
                            <label for="subject" class="form-label" style="font-weight: 500;">Emne</label>
                            <input type="text"
                                   name="subject"
                                   id="subject"
                                   class="form-control"
                                   placeholder="Indtast email emne"
                                   required>
                        </div>

                        <!-- Body HTML -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0" style="font-weight: 500;">Besked</label>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="placeholderDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-code me-1"></i> Indsæt placeholder
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="placeholderDropdown">
                                        <li><a class="dropdown-item placeholder-item" href="#" data-placeholder="@{{navn}}"><code>@{{navn}}</code> - Modtagerens navn</a></li>
                                    </ul>
                                </div>
                            </div>
                            <textarea name="body_html" id="body_html" class="form-control" style="display: none;"></textarea>
                            <div id="editor" style="height: 300px; background: white; border: 1px solid #dee2e6; border-radius: 4px;"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="testEmailBtn">
                                    <i class="fa-solid fa-flask me-1"></i> Send testmail
                                </button>
                                <div id="testEmailField" style="display: none; margin-top: 10px;">
                                    <input type="email"
                                           id="test_email"
                                           class="form-control form-control-sm"
                                           placeholder="Test email adresse"
                                           value="{{ auth()->user()->email }}"
                                           style="width: 250px;">
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                                <button type="submit" class="btn btn-primary" id="sendEmailBtn">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Send til {{ $mailingList->name }}'s {{ $mailingList->active_members_count }} medlemmer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Email Details Modal -->
    <div class="modal fade" id="emailDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Email Detaljer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Email Info -->
                    <div class="mb-3">
                        <h6 style="font-weight: 600; color: #333;">Emne</h6>
                        <p id="modalSubject" style="font-weight: 400;"></p>
                    </div>

                    <!-- Stats Row -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600;">Modtagere</div>
                                    <div style="font-size: 24px; font-weight: 600; color: #333;" id="modalRecipients"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600;">Åbninger</div>
                                    <div style="font-size: 24px; font-weight: 600; color: #333;">
                                        <span id="modalUniqueOpens"></span>
                                        <span style="font-size: 14px; color: #999; font-weight: 400;" id="modalTotalOpens"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600;">Klik</div>
                                    <div style="font-size: 24px; font-weight: 600; color: #333;">
                                        <span id="modalUniqueClicks"></span>
                                        <span style="font-size: 14px; color: #999; font-weight: 400;" id="modalTotalClicks"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Content -->
                    <div class="mb-3">
                        <h6 style="font-weight: 600; color: #333;">Email Indhold</h6>
                        <div id="modalBody" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; background: #f9f9f9;"></div>
                    </div>

                    <div class="mb-0">
                        <p style="font-size: 13px; color: #666;"><strong>Sendt:</strong> <span id="modalSentAt"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Luk</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        .nav-tabs {
            border-bottom: none;
        }
        .nav-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: none;
        }
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

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

        /* Dropdown on hover */
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }
        .dropdown-menu {
            margin-top: 0;
        }
    </style>

    <!-- Include Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        // Initialize Quill editor
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            },
            placeholder: 'Skriv din email besked her...'
        });

        // Sync Quill content to hidden textarea on form submit
        document.querySelector('#emailForm').onsubmit = function() {
            document.querySelector('#body_html').value = quill.root.innerHTML;
        };

        // Handle placeholder insertion
        document.querySelectorAll('.placeholder-item').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const placeholder = this.getAttribute('data-placeholder');

                // Get current cursor position
                const range = quill.getSelection();
                if (range) {
                    // Insert placeholder at cursor position
                    quill.insertText(range.index, placeholder);
                    // Move cursor after the inserted placeholder
                    quill.setSelection(range.index + placeholder.length);
                } else {
                    // If no selection, insert at the end
                    const length = quill.getLength();
                    quill.insertText(length - 1, placeholder);
                    quill.setSelection(length + placeholder.length - 1);
                }

                // Focus back on the editor
                quill.focus();
            });
        });

        // Test email button toggle
        document.getElementById('testEmailBtn').addEventListener('click', function() {
            const testEmailField = document.getElementById('testEmailField');
            const sendEmailBtn = document.getElementById('sendEmailBtn');
            const emailForm = document.getElementById('emailForm');

            if (testEmailField.style.display === 'none') {
                testEmailField.style.display = 'block';
                this.innerHTML = '<i class="fa-solid fa-xmark me-1"></i> Annuller testmail';
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-warning');
                sendEmailBtn.innerHTML = '<i class="fa-solid fa-flask me-1"></i> Send testmail';

                // Change form action to test mode
                emailForm.dataset.testMode = 'true';
            } else {
                testEmailField.style.display = 'none';
                this.innerHTML = '<i class="fa-solid fa-flask me-1"></i> Send testmail';
                this.classList.remove('btn-warning');
                this.classList.add('btn-outline-secondary');
                sendEmailBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Send til {{ $mailingList->name }}\'s {{ $mailingList->active_members_count }} medlemmer';

                // Reset form action
                emailForm.dataset.testMode = 'false';
            }
        });

        // Modify form submission for test mode
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            if (this.dataset.testMode === 'true') {
                e.preventDefault();

                const testEmail = document.getElementById('test_email').value;
                if (!testEmail) {
                    alert('Indtast venligst en test email adresse');
                    return;
                }

                // Create a temporary form with test email data
                const formData = new FormData(this);
                formData.delete('mailing_list_id'); // Remove mailing list to send only to test email

                // Submit via fetch to send to test email only
                fetch('{{ route('creator.emails.send') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: new URLSearchParams({
                        subject: formData.get('subject'),
                        body_html: formData.get('body_html'),
                        test_email: testEmail
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success || data.redirect) {
                        window.location.reload();
                    } else {
                        alert('Kunne ikke sende testmail: ' + (data.message || 'Ukendt fejl'));
                    }
                })
                .catch(error => {
                    // If not JSON response, reload page (likely redirected with success)
                    window.location.reload();
                });
            }
        });

        // Parse email bodies from JSON
        let emailBodies = {};
        const emailBodiesElement = document.getElementById('emailBodiesData');
        if (emailBodiesElement) {
            try {
                emailBodies = JSON.parse(emailBodiesElement.textContent);
            } catch (e) {
                console.error('Failed to parse email bodies:', e);
            }
        }

        // Add click handlers to email items
        document.querySelectorAll('.email-item').forEach(function(row) {
            row.addEventListener('click', function() {
                // Get data from attributes
                const emailId = this.dataset.emailId;
                const subject = this.dataset.subject;
                const recipients = this.dataset.recipients;
                const uniqueOpens = parseInt(this.dataset.uniqueOpens);
                const totalOpens = parseInt(this.dataset.totalOpens);
                const uniqueClicks = parseInt(this.dataset.uniqueClicks);
                const totalClicks = parseInt(this.dataset.totalClicks);
                const sentAt = this.dataset.sentAt;

                // Get the HTML body from the parsed JSON data
                const body = emailBodies[emailId] || '<p>Email indhold ikke tilgængeligt</p>';

                // Populate modal fields
                document.getElementById('modalSubject').textContent = subject;
                document.getElementById('modalBody').innerHTML = body;
                document.getElementById('modalRecipients').textContent = recipients;
                document.getElementById('modalSentAt').textContent = sentAt;

                // Opens
                if (uniqueOpens > 0) {
                    document.getElementById('modalUniqueOpens').textContent = uniqueOpens;
                    document.getElementById('modalTotalOpens').textContent = ' (' + totalOpens + ')';
                } else {
                    document.getElementById('modalUniqueOpens').textContent = '-';
                    document.getElementById('modalTotalOpens').textContent = '';
                }

                // Clicks
                if (uniqueClicks > 0) {
                    document.getElementById('modalUniqueClicks').textContent = uniqueClicks;
                    document.getElementById('modalTotalClicks').textContent = ' (' + totalClicks + ')';
                } else {
                    document.getElementById('modalUniqueClicks').textContent = '-';
                    document.getElementById('modalTotalClicks').textContent = '';
                }

                // Show modal
                var modal = new bootstrap.Modal(document.getElementById('emailDetailsModal'));
                modal.show();
            });
        });
    </script>
</x-app-layout>
