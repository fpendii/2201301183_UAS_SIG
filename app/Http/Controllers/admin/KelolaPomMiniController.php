<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PomMiniModel;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class KelolaPomMiniController extends Controller
{
    public function index()
    {
        $pomMini = PomMiniModel::all();

        return view('admin/kelola_pom_mini/data_pom_mini', compact('pomMini'));
    }

    public function tambah()
    {
        return view('admin/kelola_pom_mini/form_tambah');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $createPomMini = PomMiniModel::create($request->all());

        if (!$createPomMini) {
            return redirect()->back()->with('error', 'Data Gagal ditambahkan');
        }

        return redirect()->to('admin/kelola-pom-mini')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $pomMini = PomMiniModel::findOrFail($id);
        return view('admin/kelola_pom_mini/form_edit', compact('pomMini'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $pomMini = PomMiniModel::findOrFail($id);
        $pomMini->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect('admin/kelola-pom-mini')->with('success', 'Pom Mini berhasil diperbarui');
    }

    public function delete($id)
    {
        $pomMini = PomMiniModel::findOrFail($id);
        $pomMini->delete();

        return redirect('admin/kelola-pom-mini')->with('success', 'Pom Mini berhasil dihapus');
    }
}
