<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Item;
use App\Brand;
use App\Subcategory;
use App\Http\Resources\ItemResource;

class ItemController extends Controller
{
    public function __construct($value='')
    {
       // $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items=Item::all();
        return ItemResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);

        // Validation
        $request->validate([
            'codeno'=>'required|min:4',
            'name'=>'required',
            'photo'=>'required',
            'price'=>'required',
            'discount'=>'required',
            'description'=>'required',
            'brand'=>'required',
            'subcategory'=>'required'
        ]);

        // If include file, upload
        // File Updoad 
        $imgName=time().'.'.$request->photo->extension();
        $request->photo->move(public_path('backend/itemimg/'),$imgName);
        $myfile='backend/itemimg/'.$imgName;
        

        // Data insert
        $item=new Item;
        $item->codeno=$request->codeno;
        $item->name=$request->name;
        $item->photo=$myfile;
        $item->price=$request->price;
        $item->discount=$request->discount;
        $item->description=$request->description;
        $item->brand_id=$request->brand;
        $item->subcategory_id=$request->subcategory;
        $item->save();

        // Redirect
        // return $item;
        return new ItemResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item=Item::findOrFail($id);
        return new ItemResource($item);
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
        
        // dd($request);
        //Validation
        $request->validate([
            'codeno'=>'required|min:4',
            'name'=>'required',
            'photo'=>'sometimes',
            'price'=>'required',
            'discount'=>'required',
            'description'=>'required',
            'brand'=>'required',
            'subcategory'=>'required'
        ]);

        //if include file,upload
        //file upload
        if ($request->hasFile('photo')) {
            $imgName=time().'.'.$request->photo->extension();
            $request->photo->move(public_path('backend/itemimg/'),$imgName);
            $myfile='backend/itemimg/'.$imgName;
        //delete old photo (unlink)
            unlink($request->oldphoto);
        }else{
            $myfile=$request->oldphoto;
        }
        

        //Data Update
        $item=Item::find($id);
        $item->codeno=$request->codeno;
        $item->name=$request->name;
        $item->photo=$myfile;
        $item->price=$request->price;
        $item->discount=$request->discount;
        $item->description=$request->description;
        $item->brand_id=$request->brand;
        $item->subcategory_id=$request->subcategory;
        $item->save();

        //redirect
        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
