<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DokterController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all dokters
        $dokters = Dokter::latest()->get();

        //return collection of dokters as a resource
        return new PostResource(true, 'List Data Dokter', $dokters);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name'     => 'required',
            'role'   => 'required',
            'instagram'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/dokters', $image->hashName());

        //create post
        $dokters = Dokter::create([
            'image'     => $image->hashName(),
            'name'     => $request->name,
            'role'   => $request->role,
            'instagram'   => $request->instagram,
        ]);

        //return response
        return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $dokters);
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show($id)
    {
        //find post by ID
        $dokters = Dokter::find($id);

        //return single post as a resource
        return new PostResource(true, 'Detail Data Layanan!', $dokters);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'role'   => 'required',
            'instagram'  => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $dokters = Dokter::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/dokters', $image->hashName());

            //delete old image
            Storage::delete('public/dokters/'.$dokters->image);

            //update post with new image
            $dokters->update([
                'image'     => $image->hashName(),
                'name'     => $request->name,
                'role'   => $request->role,
                'instagram'   => $request->instagram,
            ]);

        } else {

            //update post without image
            $dokters->update([
                'name'     => $request->name,
                'role'   => $request->role,
                'instagram'   => $request->instagram,
                'category'   => $request->category,
            ]);
        }

        //return response
        return new PostResource(true, 'Data Layanan Berhasil Diubah!', $dokters);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy($id)
    {

        //find post by ID
        $dokters = Dokter::find($id);

        //delete image
        Storage::delete('public/dokters/'.$dokters->image);

        //delete post
        $dokters->delete();

        //return response
        return new PostResource(true, 'Data Layanan Berhasil Dihapus!', null);
    }
}
