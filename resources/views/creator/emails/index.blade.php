<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Mine Emails</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Mine Emails
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Send emails til dine medlemmer
                    </p>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="emailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="composer-tab" data-bs-toggle="tab" data-bs-target="#composer" type="button" role="tab" aria-controls="composer" aria-selected="true">
                    <i class="fa-solid fa-pen me-1"></i> Skriv Email
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="archive-tab" data-bs-toggle="tab" data-bs-target="#archive" type="button" role="tab" aria-controls="archive" aria-selected="false">
                    <i class="fa-solid fa-archive me-1"></i> Email Arkiv ({{ $emails->count() }})
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="emailTabsContent">
            <!-- Composer Tab -->
            <div class="tab-pane fade show active" id="composer" role="tabpanel" aria-labelledby="composer-tab">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0" style="font-weight: 600;">Ny Email</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('creator.emails.send') }}" method="POST" id="emailForm">
                            @csrf

                            <!-- Mailing List -->
                            <div class="mb-3">
                                <label for="mailing_list_id" class="form-label">Send til liste</label>
                                <select name="mailing_list_id" id="mailing_list_id" class="form-select @error('mailing_list_id') is-invalid @enderror">
                                    <option value="">Vælg en liste</option>
                                    @foreach($mailingLists as $list)
                                        <option value="{{ $list->id }}" {{ old('mailing_list_id') == $list->id ? 'selected' : '' }}>
                                            {{ $list->name }} ({{ $list->active_members_count ?? 0 }} medlemmer)
                                        </option>
                                    @endforeach
                                </select>
                                @error('mailing_list_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Subject -->
                            <div class="mb-3">
                                <label for="subject" class="form-label">Emne</label>
                                <input type="text"
                                       name="subject"
                                       id="subject"
                                       class="form-control @error('subject') is-invalid @enderror"
                                       placeholder="Indtast email emne"
                                       value="{{ old('subject') }}"
                                       required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Body HTML -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="body_html" class="form-label mb-0">Besked</label>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="placeholderDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-code me-1"></i> Indsæt placeholder
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="placeholderDropdown">
                                            <li><a class="dropdown-item placeholder-item" href="#" data-placeholder="@{{navn}}"><code>@{{navn}}</code> - Modtagerens navn</a></li>
                                            <li><a class="dropdown-item placeholder-item" href="#" data-placeholder="@{{email}}"><code>@{{email}}</code> - Modtagerens email</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <textarea name="body_html" id="body_html" class="form-control @error('body_html') is-invalid @enderror" style="display: none;">{{ old('body_html') }}</textarea>
                                <div id="editor" style="height: 400px;"></div>
                                @error('body_html')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Send Email
                                </button>
                                <button type="reset" class="btn btn-outline-secondary" onclick="quill.setContents([]);">
                                    Ryd
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Archive Tab -->
            <div class="tab-pane fade" id="archive" role="tabpanel" aria-labelledby="archive-tab">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0" style="font-weight: 600;">
                            Email Arkiv
                            @if($emails->count() > 0)
                                (<b>{{ $emails->count() }}</b>)
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($emails->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="font-weight: 600; color: #333;">Emne</th>
                                            <th style="font-weight: 600; color: #333;">Liste</th>
                                            <th style="font-weight: 600; color: #333;">Modtagere</th>
                                            <th style="font-weight: 600; color: #333;">Åbninger</th>
                                            <th style="font-weight: 600; color: #333;">Klik</th>
                                            <th style="font-weight: 600; color: #333;">Sendt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($emails as $email)
                                            <tr class="email-row" style="cursor: pointer;"
                                                data-email-id="{{ $email->id }}"
                                                data-subject="{{ $email->subject }}"
                                                data-list="{{ $email->mailingList ? $email->mailingList->name : 'Ingen liste' }}"
                                                data-recipients="{{ $email->recipients_count }}"
                                                data-unique-opens="{{ $email->unique_opens }}"
                                                data-total-opens="{{ $email->total_opens }}"
                                                data-unique-clicks="{{ $email->unique_clicks }}"
                                                data-total-clicks="{{ $email->total_clicks }}"
                                                data-sent-at="{{ $email->sent_at ? $email->sent_at->format('d/m/Y H:i') : 'Ikke sendt' }}">
                                                <td>
                                                    <strong style="font-weight: 500;">{{ $email->subject }}</strong>
                                                </td>
                                                <td style="font-weight: 300; color: #666;">
                                                    {{ $email->mailingList ? $email->mailingList->name : 'Ingen liste' }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $email->recipients_count }} personer</span>
                                                </td>
                                                <td>
                                                    @if($email->unique_opens > 0)
                                                        <span style="font-weight: 500; color: #333;">{{ $email->unique_opens }}</span>
                                                        <span style="font-weight: 300; color: #999; font-size: 12px;">({{ $email->total_opens }} total)</span>
                                                    @else
                                                        <span style="font-weight: 300; color: #999;">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($email->unique_clicks > 0)
                                                        <span style="font-weight: 500; color: #333;">{{ $email->unique_clicks }}</span>
                                                        <span style="font-weight: 300; color: #999; font-size: 12px;">({{ $email->total_clicks }} total)</span>
                                                    @else
                                                        <span style="font-weight: 300; color: #999;">-</span>
                                                    @endif
                                                </td>
                                                <td style="font-weight: 300; color: #666;">
                                                    {{ $email->sent_at ? $email->sent_at->format('d/m/Y H:i') : 'Ikke sendt' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-paper-plane" style="font-size: 3rem;"></i>
                                <p class="mt-3">Ingen sendte emails endnu</p>
                                <p class="small">Send din første email fra "Skriv Email" fanen</p>
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
        </div>
    </div>

    <!-- Email Details Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Email Detaljer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Email Info -->
                    <div class="mb-3">
                        <h6 style="font-weight: 600; color: #333;">Emne</h6>
                        <p id="modalSubject" style="font-weight: 400;"></p>
                    </div>

                    <!-- Stats Row -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600;">Modtagere</div>
                                    <div style="font-size: 24px; font-weight: 600; color: #333;" id="modalRecipients"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600;">Sendt</div>
                                    <div style="font-size: 14px; font-weight: 500; color: #333;" id="modalSentAt"></div>
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
                        <p style="font-size: 13px; color: #666;"><strong>Liste:</strong> <span id="modalList"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Luk</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Include Quill stylesheet -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        // Initialize Quill editor
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            },
            placeholder: 'Skriv din email besked her...'
        });

        // Load existing content if any
        const existingContent = document.querySelector('#body_html').value;
        if (existingContent) {
            quill.root.innerHTML = existingContent;
        }

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

        // Add click handlers to email rows
        document.querySelectorAll('.email-row').forEach(function(row) {
            row.addEventListener('click', function() {
                // Get data from attributes
                const emailId = this.dataset.emailId;
                const subject = this.dataset.subject;
                const list = this.dataset.list;
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
                document.getElementById('modalList').textContent = list;
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
                var modal = new bootstrap.Modal(document.getElementById('emailModal'));
                modal.show();
            });
        });
    </script>
    @endpush

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
            border-radius: 6px;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        .btn-outline-secondary {
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
        }
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
        .table th {
            border-bottom: 2px solid #e0e0e0;
            padding: 12px;
        }
        .table td {
            padding: 12px;
            vertical-align: middle;
        }
    </style>
</x-app-layout>
