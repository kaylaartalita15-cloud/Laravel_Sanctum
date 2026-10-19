<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CatatanResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CatatanController extends Controller
{
    /**
     * Menampilkan daftar catatan milik akun yang sedang login.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $catatans = $request->user()
            ->catatans()
            ->latest()
            ->get();

        return CatatanResource::collection($catatans);
    }

    /**
     * Menyimpan catatan baru untuk akun yang sedang login.
     * user_id ditentukan dari akun terautentikasi, bukan dari request client.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:100'],
            'isi' => ['required', 'string'],
        ]);

        $catatan = $request->user()->catatans()->create($validated);

        return response()->json([
            'message' => 'Catatan berhasil disimpan.',
            'catatan' => new CatatanResource($catatan),
        ], 201);
    }
}
