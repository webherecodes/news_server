<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsUtilities;
use Illuminate\Support\Facades\Validator;
class NewsController extends Controller
{
    public function getData(){
        $news = News::with('utils')->get();
        $data = [
            'status' => 200,
            'news' => $news
        ];
        return response()->json($data,200);
    }

    public function getPaginateData(){
        return News::query()->paginate(2);
    }

    public function postData(Request $request){
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'headline' => 'required|string',
            'news_content' => 'required|string',
            'file' => 'required',
            'file_type' => 'required'
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->messages(),
            ];
            return response()->json($data, 400);
        }
        else{
            if($request->hasFile('file')){
                $rand = rand();
                $ex = '.' . $request->file('file')->getClientOriginalExtension();
                $filename = $rand.$ex;
                $path = $request->file('file')->move(public_path('uploads/news/'),$filename);
                $newsUrl = url('/uploads/news/'.$filename);   
            }

            $news = new News;
            $news->user_id = $request->user_id;
            $news->headline = $request->headline;
            $news->news_content = $request->news_content;
            $news->file = $newsUrl;
            $news->file_type = $request->file_type;

            $news->save();
            $data = [
                'status' => 200,
                'news' => $news,
                'message' => 'News uploaded successfully'
            ];
            return response()->json($data,200);

        }
    }

    public function editData(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'headline' => 'required',
            'news_content' => 'required',
            'file' => 'required',
            'file_type' => 'required'
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->messages(),
            ];
            return response()->json($data, 400);
        }
        else{
            if($request->hasFile('file')){
                $rand = rand();
                $ex = '.' . $request->file('file')->getClientOriginalExtension();
                $filename = $rand.$ex;
                $path = $request->file('file')->move(public_path('uploads/news/'),$filename);
                $newsUrl = url('/uploads/news/'.$filename);   
            }

            $news = News::find($id);
            $news->headline = $request->headline;
            $news->news_content = $request->news_content;
            $news->file = $newsUrl;
            $news->file_type = $request->file_type;

            $news->save();
            $data = [
                'status' => 200,
                'message' => 'Data updated successfully'
            ];
            return response()->json($data,200);

        }

    }

    public function deleteData($id){
        $news = News::find($id);
            $news->delete();
            $data = [
                'status' => 200,
                'message' => 'Data deleted successfully'
            ];
            return response()->json($data,200);
    }
}
