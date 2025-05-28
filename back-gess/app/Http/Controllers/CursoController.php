<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCursoRequest;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{

    public function index(Request $request)
    {
        return $this->generateViewSetList(
            $request,
            Curso::query(),
            [],
            ['id'],
            ['id']
        );
    }

    public function store(StoreCursoRequest $request)
    {
        return response(Curso::create($request['curso']), 201);
    }

    public function show(Curso $curso)
    {
        return response()->json($curso);
    }

    public function update(StoreCursoRequest $request, Curso $curso)
    {
        $curso->update($request['curso']);

        return response()->json([$curso, $request]);
    }

    public function destroy(Curso $curso)
    {
        return response()->json($curso->delete());
    }
}
