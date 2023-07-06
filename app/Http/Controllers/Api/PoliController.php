<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $polis = Poli::latest()->get();

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Poli', $polis);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //define validation rules
         $validator = Validator::make($request->all(), [
            'icon'    => 'required',
            'title'   => 'required',
            'desc'    => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $polis = Poli::create([
           
            'icon'     => $request->icon,
            'title'    => $request->title,
            'desc'     => $request->desc,
        ]);

        //return response
        return new PostResource(true, 'Data Poli Berhasil Ditambahkan!', $polis);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $polis = Poli::find($id);

        //return single post as a resource
        return new PostResource(true, 'Detail Data Poli!', $polis);
    }   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'icon'     => 'required',
            'title'    => 'required',
            'desc'     => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $polis = Poli::find($id);

            //update post without image
        $polis->update([
                'icon'     => $request->icon,
                'title'   => $request->title,
                'desc'   => $request->desc,
            ]);

        //return response
        return new PostResource(true, 'Data Poli Berhasil Diubah!', $polis);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $polis = Poli::find($id);

        

        //delete post
        $polis->delete();

        //return response
        return new PostResource(true, 'Data Poli Berhasil Dihapus!', null);
    }
}
