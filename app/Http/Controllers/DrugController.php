<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Models\Drug;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DrugController extends Controller
{
    public function add(Request $request){
        if(auth()->user()){
            $validators = Validator::make($request->all(),[
                'name'=>'required|string|min:5|max:100',
                'mrp'=>'required|numeric|min:0|not_in:0',
                'ptr'=>'required|numeric|min:0|not_in:0',
                'expiry'=>'required|date_format:Y-m-d H:i:s|after_or_equal:' . date(DATE_ATOM),
                'barcode'=>'required|integer|min:10000',
                'type'=> Rule::in(['generic','ethical','ayurvedic','surgical','otc','general'])
            ]);
    
            if($validators->fails()){
                return response()->json($validators->errors(),400);
            }
    
            $data = Drug::create([
                'name'=>$request->name,
                'mrp'=>$request->mrp,
                'ptr'=>$request->ptr,
                'expiry'=>$request->expiry,
                'barcode'=>$request->barcode,
                'type'=>$request->type
            ]);
    
            return response()->json([
                'message'=>'Drug Added',
                'data'=>$data
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Unauthorized'
            ]);
        }
    }

    public function update(Request $request){
        if(auth()->user()){
            $validators = Validator::make($request->all(),[
                'id'=>'required|integer|min:1',
                'name'=>'string|min:5|max:100',
                'mrp'=>'numeric|min:0|not_in:0',
                'ptr'=>'numeric|min:0|not_in:0',
                'expiry'=>'date_format:Y-m-d H:i:s|after_or_equal:' . date(DATE_ATOM),
                'barcode'=>'integer|min:10000',
                'type'=> Rule::in(['generic','ethical','ayurvedic','surgical','otc','general'])
            ]);
    
            if($validators->fails()){
                return response()->json($validators->errors(),400);
            }

            $drug = Drug::find($request->id);

            if(!empty($request->name)){
                $drug->name = $request->name;
            }
            if(!empty($request->mrp)){
                $drug->mrp = $request->mrp;
            }
            if(!empty($request->ptr)){
                $drug->ptr = $request->ptr;
            }
            if(!empty($request->expiry)){
                $drug->expiry = $request->expiry;
            }
            if(!empty($request->barcode)){
                $drug->barcode = $request->barcode;
            }
            if(!empty($request->type)){
                $drug->type = $request->type;
            }
            $drug->save();
            return response()->json([
                'success'=>true,
                'message'=>'Drug Information Updated'
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Unauthorized'
            ]);
        }
    }

    public function details($id){
        if(auth()->user()){
            $data = Drug::find($id);
            return response()->json([
                'success'=>true,
                'data'=>$data
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Unauthorized'
            ]);
        }
    }

    public function list(){
        if(auth()->user()){
            $data = Drug::paginate();
            return response()->json([
                'success'=>true,
                'data'=>$data
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Unauthorized'
            ]);
        }
    }

    public function delete($id){
        if(auth()->user()){
            $drug = Drug::find($id);
            if($drug){
                $drug->delete();
                return response()->json([
                    'success'=>true,
                    'message'=>'Record Deleted'
                ]);
            }else{
                return response()->json([
                    'success'=>false,
                    'message'=>'Record does not exist'
                ]);
            } 
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Unauthorized'
            ]);
        }
    }


}
