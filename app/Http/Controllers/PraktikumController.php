<?php

namespace App\Http\Controllers;

use App\Http\Resources\PraktikumResource;
use App\Laravue\Models\Praktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Config;

class PraktikumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limitConfig = config('config.paginate');
        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', $limitConfig);
        $nama = Arr::get($searchParams, 'nama', '');
        $praktikumQuery = Praktikum::query();

        if (!empty($nama)) {
            $praktikumQuery->where('nama', 'LIKE', '%' . $nama . '%');
        }

        return PraktikumResource::collection($praktikumQuery->paginate($limit));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => ['required'],
                'periode' => ['required'],
                'kode' => ['required'],
                'status' => ['required']
            ]
        );
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $praktikum = Praktikum::create([
                'nama' => $params['nama'],
                'periode' => $params['periode'],
                'kode' => $params['kode'],
                'status' => $params['status'],
            ]);
            
            return new PraktikumResource($praktikum);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravue\Models\Praktikum  $praktikum
     * @return \Illuminate\Http\Response
     */
    public function show(Praktikum $praktikum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laravue\Models\Praktikum  $praktikum
     * @return \Illuminate\Http\Response
     */
    public function edit(Praktikum $praktikum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\Praktikum  $praktikum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Praktikum $praktikum)
    {
        {
            if ($praktikum === null) {
                return response()->json(['error' => 'Praktikum tidak ditemukan'], 404);
            }
    
            $validator = Validator::make(
                $request->all(),
                [
                    'nama' => ['required']
                ]
            );
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 403);
            } else {
                $params = $request->all();
                $praktikum->nama = $params['nama'];
                $praktikum->periode = $params['periode'];
                $praktikum->kode = $params['kode'];
                $praktikum->status = $params['status'];
                $praktikum->save();
            }
    
            return new PraktikumResource($praktikum);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\Praktikum  $praktikum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Praktikum $praktikum)
    {
        try {
            $praktikum->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    
        return response()->json(null, 204);
    }
}
