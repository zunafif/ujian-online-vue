<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModulResource;
use App\Laravue\Models\Modul;
use App\Laravue\Models\Praktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr; 
use Config;

class ModulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limitConfig = config('config.paginate');
        $searchParam = $request->all();
        $nama_modul = Arr::get($searchParam, 'nama_modul', '');
        $limit = Arr::get($searchParam, 'limit', $limitConfig);
        $data = Modul::query()
            ->join('praktikum', 'modul.id_praktikum', 'praktikum.id')
            ->select('modul.id','modul.id_praktikum','modul.nama_modul','praktikum.nama','modul.jumlah_bab','modul.materi');
        
        if(!empty($nama_modul)){
            $data->where('nama_modul', 'LIKE', '%'. $nama_modul .'%');
        }

        return ModulResource::collection($data->paginate($limit));
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
                'nama_modul' => ['required'],
                'jumlah_bab' => ['required'],
                'materi' => ['required'],
            ]
        );
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $modul = Modul::create([
                'nama_modul' => $params['nama_modul'],
                'id_praktikum' => $params['id_praktikum'],
                'jumlah_bab' => $params['jumlah_bab'],
                'materi' => $params['materi'],
            ]);
            
            return new ModulResource($modul);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravue\Models\Modul  $modul
     * @return \Illuminate\Http\Response
     */
    public function show(Modul $modul)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laravue\Models\Modul  $modul
     * @return \Illuminate\Http\Response
     */
    public function edit(Modul $modul)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\Modul  $modul
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modul $modul)
    {
        {
            if ($modul === null) {
                return response()->json(['error' => 'Modul tidak ditemukan'], 404);
            }
    
            $validator = Validator::make(
                $request->all(),
                [
                    'nama_modul' => ['required']
                ]
            );
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 403);
            } else {
                $params = $request->all();
                $modul->nama_modul = $params['nama_modul'];
                $modul->id_praktikum = $params['id_praktikum'];
                $modul->jumlah_bab = $params['jumlah_bab'];
                $modul->materi = $params['materi'];
                $modul->save();
            }
    
            return new ModulResource($modul);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\Modul  $modul
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modul $modul)
    {
        try {
            $modul->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
        return response()->json(null, 204);
    }
}
