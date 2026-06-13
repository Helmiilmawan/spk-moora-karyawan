<?php

namespace App\Http\Controllers;

class TeamController extends Controller
{
    public function index()
    {
        // Hanya menyisakan satu anggota tim utama
        $team = [
            [
                'nama' => 'Helmi Ilmawan',
                'nim'  => '221351150',
                'role' => 'Project Developer',
                'color'=> '#3b82f6', // Menggunakan warna biru utama agar serasi dengan bar
                'foto' => 'https://ui-avatars.com/api/?name=Helmi+Ilmawan&background=3b82f6&color=fff&size=150&bold=true'
            ]
        ];

        return view('team.index', compact('team'));
    }
}