<?php

namespace App\Http\Controllers\ReplyProduct;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

use App\Http\Resources\ReplyCommentResource;

use App\Models\replyComment;
use App\Models\replyComment as ModelsReplyComment;

class ReplyCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reply = replyComment::all();
        $reply = ReplyCommentResource::collection($reply);
        return response()->json(['data' => $reply, 'message' => 'Reply comment is successfully']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = $request->user_id;
        $comment_id = $request->comment_id;
        $text = $request->text;

        $reply = new replyComment();

        $reply->user_id = $user_id;
        $reply->comment_id = $comment_id;
        $reply->text = $text;
        try{
            $reply->save();
            return response()->json(['data' => $reply, 'message' => 'Reply comment is successfully']);
        }catch(Exception $e){
            return response()->json(['data' => $e, 'message' => 'Reply comment is not successfully']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reply = replyComment::find($id);
        return response()->json(['comment' => $reply,'message' => 'You can see the comment'], status:200);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $request->id;
        $user_id = $request->user_id;
        $comment_id = $request->comment_id;
        $text = $request->text;

        $reply = replyComment::where('id', $id)->first();
        $reply->user_id = $user_id;
        $reply->comment_id = $comment_id;
        $reply->text = $text;
        try{
            $reply->save();
            return response()->json(['data' => $reply, 'message' => 'Reply comment is update successfully']);
        }catch(Exception $error){
            return response()->json(['data' => $error, 'message' => 'Reply comment is not update successfully']);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reply = replyComment::find($id);
        $reply->delete();
        return response()->json(['data' => $reply, 'message' => 'Reply comment is delete successfully']);
    }
}
