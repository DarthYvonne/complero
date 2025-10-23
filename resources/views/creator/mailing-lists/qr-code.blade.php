<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Mailing lister</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $mailingList->name }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">QR Kode</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                        QR Kode: <span style="font-weight: 100;">{{ $mailingList->name }}</span>
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
                <a class="nav-link active" href="{{ route('creator.mailing-lists.qr-code', $mailingList) }}">
                    <i class="fa-solid fa-qrcode me-1"></i> QR Kode
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.import', $mailingList) }}">
                    <i class="fa-solid fa-file-import me-1"></i> Importer
                </a>
            </li>
        </ul>

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- QR Code Display -->
                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-qrcode" style="color: var(--primary-color);"></i> QR Kode
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <p style="font-weight: 300; color: #666; margin-bottom: 30px;">
                            Scan denne QR kode med en smartphone for at blive ført direkte til tilmeldingsformularen.
                        </p>

                        <!-- QR Code Image -->
                        <div class="mb-4">
                            <img id="qrCode"
                                 src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={{ urlencode(url('/signup/' . $mailingList->slug)) }}"
                                 alt="QR Code for {{ $mailingList->name }}"
                                 style="max-width: 400px; width: 100%; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; background: white;">
                        </div>

                        <!-- Download Buttons -->
                        <div class="d-flex justify-content-center gap-2">
                            <a href="https://api.qrserver.com/v1/create-qr-code/?size=800x800&data={{ urlencode(url('/signup/' . $mailingList->slug)) }}"
                               download="qr-code-{{ $mailingList->slug }}.png"
                               class="btn btn-primary">
                                <i class="fa-solid fa-download me-1"></i> Download PNG (800x800)
                            </a>
                            <a href="https://api.qrserver.com/v1/create-qr-code/?size=2000x2000&data={{ urlencode(url('/signup/' . $mailingList->slug)) }}"
                               download="qr-code-{{ $mailingList->slug }}-large.png"
                               class="btn btn-outline-primary">
                                <i class="fa-solid fa-download me-1"></i> Download PNG (2000x2000)
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Link Information -->
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-link" style="color: var(--primary-color);"></i> QR kode link
                        </h5>
                    </div>
                    <div class="card-body">
                        <p style="font-weight: 300; color: #666; margin-bottom: 20px;">
                            QR koden peger på dette link:
                        </p>

                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   id="qrLink"
                                   value="{{ url('/signup/' . $mailingList->slug) }}"
                                   readonly>
                            <button class="btn btn-outline-primary"
                                    type="button"
                                    onclick="copyToClipboard()">
                                <i class="fa-solid fa-copy me-1"></i> Kopier
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- QR Code Customization -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                            <i class="fa-solid fa-sliders"></i> Tilpas størrelse
                        </h5>
                        <p style="font-weight: 300; color: #666; font-size: 14px; margin-bottom: 15px;">
                            Vælg størrelse på QR koden:
                        </p>

                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-secondary btn-sm" onclick="updateQRSize(200)">
                                200 x 200 px (Lille)
                            </button>
                            <button class="btn btn-outline-secondary btn-sm active" onclick="updateQRSize(400)">
                                400 x 400 px (Medium)
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="updateQRSize(600)">
                                600 x 600 px (Stor)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Usage Instructions -->
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                            <i class="fa-solid fa-circle-info"></i> Sådan bruger du QR koden
                        </h5>
                        <div style="font-weight: 300; color: #666; font-size: 14px;">
                            <p><strong style="font-weight: 500;">Print materialer:</strong> Download den store version (2000x2000) til print på plakater, flyers osv.</p>
                            <p><strong style="font-weight: 500;">Digital brug:</strong> Brug medium versionen (800x800) til websites og præsentationer.</p>
                            <p><strong style="font-weight: 500;">Events:</strong> Vis QR koden på et display eller projektor, så deltagere nemt kan tilmelde sig.</p>
                            <p class="mb-0"><strong style="font-weight: 500;">Sociale medier:</strong> Del QR koden i dine opslag for at gøre det nemt at tilmelde sig.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-tabs .nav-link {
            background-color: transparent !important;
        }

        .nav-tabs .nav-link.active {
            background-color: transparent !important;
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
        .btn-outline-secondary.active {
            background: #6c757d;
            color: white;
        }
    </style>

    <script>
        function copyToClipboard() {
            const input = document.getElementById('qrLink');
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices

            navigator.clipboard.writeText(input.value).then(() => {
                // Show success feedback
                const button = event.target.closest('button');
                const originalHtml = button.innerHTML;
                button.innerHTML = '<i class="fa-solid fa-check me-1"></i> Kopieret!';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-success');

                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-primary');
                }, 2000);
            }).catch(err => {
                alert('Kunne ikke kopiere: ' + err);
            });
        }

        function updateQRSize(size) {
            const qrCode = document.getElementById('qrCode');
            const url = new URL(qrCode.src);
            url.searchParams.set('size', size + 'x' + size);
            qrCode.src = url.toString();

            // Update active button state
            document.querySelectorAll('.btn-outline-secondary.btn-sm').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
        }
    </script>

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
    </style>
</x-app-layout>
