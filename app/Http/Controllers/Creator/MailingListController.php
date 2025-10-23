<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\MailingList;
use App\Models\User;
use Illuminate\Http\Request;

class MailingListController extends Controller
{
    /**
     * Display a listing of the creator's mailing lists.
     */
    public function index()
    {
        $query = MailingList::withCount(['members', 'courses', 'resources'])->orderBy('created_at', 'desc');

        // Only scope to own content if NOT admin
        if (auth()->user()->role !== 'admin') {
            $query->where('creator_id', auth()->id());
        }

        $mailingLists = $query->get();

        return view('creator.mailing-lists.index', compact('mailingLists'));
    }

    /**
     * Show the form for creating a new mailing list.
     */
    public function create()
    {
        return view('creator.mailing-lists.create');
    }

    /**
     * Store a newly created mailing list in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $mailingList = MailingList::create([
            'creator_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('creator.mailing-lists.show', $mailingList)
            ->with('success', 'Mailing liste oprettet succesfuldt');
    }

    /**
     * Display the specified mailing list.
     */
    public function show(MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til denne mailing liste');
        }

        $mailingList->load(['activeMembers', 'courses', 'resources']);

        // Get available courses and resources (not yet assigned to this list)
        $courseQuery = \App\Models\Course::where(function($q) use ($mailingList) {
            $q->where('mailing_list_id', '!=', $mailingList->id)
              ->orWhereNull('mailing_list_id');
        });

        $resourceQuery = \App\Models\Resource::where(function($q) use ($mailingList) {
            $q->where('mailing_list_id', '!=', $mailingList->id)
              ->orWhereNull('mailing_list_id');
        });

        // Only show own content if NOT admin
        if (auth()->user()->role !== 'admin') {
            $courseQuery->where('creator_id', auth()->id());
            $resourceQuery->where('creator_id', auth()->id());
        }

        $availableCourses = $courseQuery->get();
        $availableResources = $resourceQuery->get();

        return view('creator.mailing-lists.show', compact('mailingList', 'availableCourses', 'availableResources'));
    }

    /**
     * Show the form for editing the specified mailing list.
     */
    public function edit(MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere denne mailing liste');
        }

        return view('creator.mailing-lists.edit', compact('mailingList'));
    }

    /**
     * Update the specified mailing list in storage.
     */
    public function update(Request $request, MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere denne mailing liste');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $mailingList->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('creator.mailing-lists.show', $mailingList)
            ->with('success', 'Mailing liste opdateret succesfuldt');
    }

    /**
     * Remove the specified mailing list from storage.
     */
    public function destroy(MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at slette denne mailing liste');
        }

        $mailingList->delete();

        return redirect()
            ->route('creator.mailing-lists.index')
            ->with('success', 'Mailing liste slettet succesfuldt');
    }

    /**
     * Add a member to the mailing list
     */
    public function addMember(Request $request, MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Check if already a member
        if ($mailingList->hasMember($user)) {
            return back()->with('error', 'Bruger er allerede medlem af denne liste');
        }

        $mailingList->members()->attach($user->id, [
            'subscribed_at' => now(),
            'status' => 'active',
        ]);

        return back()->with('success', 'Medlem tilføjet succesfuldt');
    }

    /**
     * Remove a member from the mailing list
     */
    public function removeMember(MailingList $mailingList, User $user)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $mailingList->members()->detach($user->id);

        return back()->with('success', 'Medlem fjernet succesfuldt');
    }

    /**
     * Display signup form embed codes for the mailing list
     */
    public function signupForms(MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til denne mailing liste');
        }

        // Get form data with defaults
        $formData = $mailingList->signup_form_data ?? [];
        $selectedTemplate = $mailingList->signup_form_template ?? 'simple';

        return view('creator.mailing-lists.signup-forms', compact('mailingList', 'formData', 'selectedTemplate'));
    }

    /**
     * Display QR code generator for the mailing list
     */
    public function qrCode(MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til denne mailing liste');
        }

        return view('creator.mailing-lists.qr-code', compact('mailingList'));
    }

    /**
     * Display import page for the mailing list
     */
    public function import(MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til denne mailing liste');
        }

        return view('creator.mailing-lists.import', compact('mailingList'));
    }

    /**
     * Download CSV template for import
     */
    public function downloadTemplate()
    {
        $csv = "email,name\n";
        $csv .= "john@example.com,John Doe\n";
        $csv .= "jane@example.com,Jane Smith\n";
        $csv .= "bob@example.com,Bob Johnson\n";

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="mailing-list-template.csv"');
    }

    /**
     * Parse uploaded import file
     */
    public function parseImport(Request $request, MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'], // 10MB max
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            if (in_array($extension, ['xlsx', 'xls'])) {
                // Handle Excel files
                $data = $this->parseExcel($file);
            } else {
                // Handle CSV files
                $data = $this->parseCsv($file);
            }

            return response()->json([
                'success' => true,
                'headers' => $data['headers'],
                'rows' => $data['rows'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kunne ikke læse filen: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Process and import members from parsed data
     */
    public function processImport(Request $request, MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'headers' => ['required', 'array'],
            'rows' => ['required', 'array'],
            'email_field' => ['required', 'integer'],
            'name_field' => ['nullable', 'integer'],
            'skip_duplicates' => ['boolean'],
        ]);

        $imported = 0;
        $skipped = 0;
        $emailIndex = $validated['email_field'];
        $nameIndex = $validated['name_field'] ?? null;

        foreach ($validated['rows'] as $row) {
            $email = $row[$emailIndex] ?? null;

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            // Check if user already exists in this mailing list
            $existingMember = $mailingList->members()
                ->where('email', $email)
                ->first();

            if ($existingMember && $validated['skip_duplicates']) {
                $skipped++;
                continue;
            }

            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $nameIndex !== null ? ($row[$nameIndex] ?? 'Imported User') : 'Imported User',
                    'password' => \Hash::make(\Str::random(32)), // Random password
                    'role' => 'member',
                ]
            );

            // Add to mailing list if not already a member
            if (!$existingMember) {
                $mailingList->members()->attach($user->id, [
                    'subscribed_at' => now(),
                    'status' => 'active',
                ]);
                $imported++;
            }
        }

        return response()->json([
            'success' => true,
            'imported' => $imported,
            'skipped' => $skipped,
        ]);
    }

    /**
     * Parse CSV file
     */
    private function parseCsv($file)
    {
        $handle = fopen($file->getRealPath(), 'r');
        $headers = fgetcsv($handle);
        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }

        fclose($handle);

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    /**
     * Parse Excel file
     */
    private function parseExcel($file)
    {
        // For Excel parsing, we'll use a simple approach with PhpSpreadsheet
        // You'll need to install: composer require phpoffice/phpspreadsheet

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows)) {
                throw new \Exception('Filen er tom');
            }

            $headers = array_shift($rows);

            return [
                'headers' => $headers,
                'rows' => $rows,
            ];
        } catch (\Exception $e) {
            // Fallback: treat as CSV
            return $this->parseCsv($file);
        }
    }

    /**
     * Update signup form template selection
     */
    public function updateSignupFormTemplate(Request $request, MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'template' => ['required', 'in:simple,modern,split'],
        ]);

        $mailingList->update([
            'signup_form_template' => $validated['template'],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Update signup form customization data
     */
    public function updateSignupFormData(Request $request, MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'template' => ['required', 'in:simple,modern,split'],
            'image' => ['nullable', 'string'],
            'header' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:500'],
            'buttonText' => ['nullable', 'string', 'max:50'],
            'buttonColor' => ['nullable', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
        ]);

        // Get existing data or empty array
        $formData = $mailingList->signup_form_data ?? [];

        // Update data for this specific template
        $formData[$validated['template']] = [
            'image' => $validated['image'] ?? null,
            'header' => $validated['header'] ?? null,
            'body' => $validated['body'] ?? null,
            'buttonText' => $validated['buttonText'] ?? null,
            'buttonColor' => $validated['buttonColor'] ?? null,
        ];

        $mailingList->update([
            'signup_form_data' => $formData,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Upload image for signup form
     */
    public function uploadSignupFormImage(Request $request, MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'image' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        $path = $request->file('image')->store('signup-forms', 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Update offer membership setting
     */
    public function updateOfferMembership(Request $request, MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'offer_membership' => ['required', 'boolean'],
        ]);

        $mailingList->update([
            'offer_membership' => $validated['offer_membership'],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Assign courses to mailing list
     */
    public function assignCourses(Request $request, MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'course_ids' => ['required', 'array'],
            'course_ids.*' => ['exists:courses,id'],
        ]);

        // Update courses to belong to this mailing list
        $query = \App\Models\Course::whereIn('id', $validated['course_ids']);

        // Only scope to own content if NOT admin
        if (auth()->user()->role !== 'admin') {
            $query->where('creator_id', auth()->id());
        }

        $query->update(['mailing_list_id' => $mailingList->id]);

        return back()->with('success', 'Forløb tilbudt succesfuldt');
    }

    /**
     * Assign resources to mailing list
     */
    public function assignResources(Request $request, MailingList $mailingList)
    {
        if (auth()->user()->role !== 'admin' && $mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'resource_ids' => ['required', 'array'],
            'resource_ids.*' => ['exists:resources,id'],
        ]);

        // Update resources to belong to this mailing list
        $query = \App\Models\Resource::whereIn('id', $validated['resource_ids']);

        // Only scope to own content if NOT admin
        if (auth()->user()->role !== 'admin') {
            $query->where('creator_id', auth()->id());
        }

        $query->update(['mailing_list_id' => $mailingList->id]);

        return back()->with('success', 'Downloads tilbudt succesfuldt');
    }
}
