<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SuratTemplateController extends Controller
{
    public function index()
    {
        $templates = SuratTemplate::orderBy('created_at', 'desc')->get();
        return view('admin.surat-template.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'file_template' => 'required|file|mimes:docx,doc|max:5120', // Max 5MB
        ], [
            'nama.required' => 'Nama template wajib diisi.',
            'file_template.required' => 'File template wajib diunggah.',
            'file_template.mimes' => 'Format file template harus .docx atau .doc.',
            'file_template.max' => 'Ukuran file template maksimal 5MB.',
        ]);

        try {
            if ($request->hasFile('file_template')) {
                $file = $request->file('file_template');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('templates', $filename, 'public');

                SuratTemplate::create([
                    'nama' => $request->nama,
                    'file_path' => $path,
                    'is_active' => false,
                ]);

                return redirect()->route('admin.surat-template.index')
                    ->with('success', 'Template surat berhasil diunggah.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah template: ' . $e->getMessage())->withInput();
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

    public function activate($id)
    {
        $template = SuratTemplate::findOrFail($id);

        DB::beginTransaction();
        try {
            // Set all templates to inactive
            SuratTemplate::query()->update(['is_active' => false]);

            // Set current template to active
            $template->update(['is_active' => true]);

            DB::commit();

            return redirect()->route('admin.surat-template.index')
                ->with('success', 'Template "' . $template->nama . '" berhasil diaktifkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengaktifkan template: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $template = SuratTemplate::findOrFail($id);

        if (!$template->file_path || !Storage::disk('public')->exists($template->file_path)) {
            return back()->with('error', 'File template tidak ditemukan di storage.');
        }

        $extension = pathinfo($template->file_path, PATHINFO_EXTENSION);
        $filename = str_replace(' ', '_', $template->nama) . '.' . $extension;

        return Storage::disk('public')->download($template->file_path, $filename);
    }

    public function destroy($id)
    {
        $template = SuratTemplate::findOrFail($id);

        if ($template->is_active) {
            return back()->with('error', 'Template yang sedang aktif tidak dapat dihapus. Aktifkan template lain terlebih dahulu.');
        }

        try {
            if ($template->file_path && Storage::disk('public')->exists($template->file_path)) {
                Storage::disk('public')->delete($template->file_path);
            }

            $template->delete();

            return redirect()->route('admin.surat-template.index')
                ->with('success', 'Template surat berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus template: ' . $e->getMessage());
        }
    }
}
