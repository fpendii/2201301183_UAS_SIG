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
}
