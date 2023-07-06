<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LayananController extends Controller
{
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $layanans = Layanan::latest()->get();

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Layanan ', $layanans);
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
        $layanans = Layanan::create([
           
            'icon'     => $request->icon,
            'title'    => $request->title,
            'desc'     => $request->desc,
        ]);

        //return response
        return new PostResource(true, 'Data Layanan Berhasil Ditambahkan!', $layanans);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $layanans = Layanan::find($id);

        //return single post as a resource
        return new PostResource(true, 'Detail Data Layanan!', $layanans);
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
        $layanans = Layanan::find($id);

            //update post without image
        $layanans->update([
                'icon'     => $request->icon,
                'title'   => $request->title,
                'desc'   => $request->desc,
            ]);

        //return response
        return new PostResource(true, 'Data Layanan Berhasil Diubah!', $layanans);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $layanans = Layanan::find($id);

        

        //delete post
        $layanans->delete();

        //return response
        return new PostResource(true, 'Data Layanan Berhasil Dihapus!', null);
    }
}
