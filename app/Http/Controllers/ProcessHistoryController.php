<?php

namespace App\Http\Controllers;

use App\Models\ProcessHistory;

class ProcessHistoryController extends Controller
{
    public function index()
    {
        $histories = ProcessHistory::latest()->get();

        return view(
            'history.index',
            compact('histories')
        );
    }
public function destroy($id)
    {
        // Cari data berdasarkan ID, jika tidak ketemu langsung gagalkan (404)
        $history = ProcessHistory::findOrFail($id);
        
        // Eksekusi hapus data
        $history->delete();

        // Redirect kembali ke halaman index dengan membawa alert sukses
        return redirect()->route('history.index')->with('success', 'Riwayat pemrosesan berhasil dihapus permanen!');
    }
  public function show($id)
{
    // Cukup ambil datanya saja, tidak perlu eager loading relasi
    $history = ProcessHistory::findOrFail($id); 

    return view('history.show', compact('history'));
}
}