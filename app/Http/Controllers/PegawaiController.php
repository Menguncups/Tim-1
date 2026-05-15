<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();

        return view('pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        Pegawai::create([
            'id_pegawai' => $request->id_pegawai,
            'nip' => $request->nip,
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp' => $request->no_hp,
            'no_hp_darurat' => $request->no_hp_darurat,
            'homebase' => $request->homebase,
            'email' => $request->email,
        ]);

        return redirect('/pegawai');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $pegawai->update($request->all());

        return redirect('/pegawai');
    }

    public function destroy($id)
    {
        Pegawai::destroy($id);

        return redirect('/pegawai');
    }
}