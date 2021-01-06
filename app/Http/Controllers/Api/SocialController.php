<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Posts;
use App\Models\Follows;
use App\Models\Comments;
use App\Models\User;
use App\Models\Likes;
use Carbon\Carbon;
class SocialController extends Controller
{
    public function AddPost(Request $request) {
        if ($request->hasFile('image')) {
            //  Let's do everything here
            if ($request->file('image')->isValid()) {
                //
                $validated = $request->validate([
                    'image' => 'mimes:jpeg,png,jpg',
                ]);
                $extension = $request->image->extension();
                $filename = 'user_id-'.$request->user_id.'-'.Carbon::now()->format('YmdHs').'.'.$extension;
                $request->image->storeAs('/public/posts', $filename);
                $url = 'storage/posts/'.$filename;
                
                $post = Posts::create([
                    'user_id' => $request->user_id,
                    'body' => $request->body,
                    'image' => $url
                ]);

                return response()->json($post);
               
                
            }
        }
        abort(500, 'Could not upload image :(');
    }

    public function index(Request $request) {
        // TODO: Change To Paignate
        $explorPosts = Posts::with('User')->inRandomOrder()->take(15)->get();

        $followIds = Follows::where('user_id',$request->user_id)->pluck('follow_id');
        

        $posts = Posts::with('User')->inRandomOrder()->whereIn('user_id',$followIds)->take(15)->get();

        return response()->json([
            'explorPosts' => $explorPosts,
            'posts' => $posts,
            'followIds' => $followIds
        ]);
    }

    public function getProfile(Request $request,$id) {
        $profile = User::where('id',$id)->with('Posts')->first();

        $follow = Follows::where('user_id',$request->user_id)->where('follow_id',$id)->first();

        if($follow) {
            $isFollow = true;
        }else {
            $isFollow = false;
        }

        $follows = Follows::where('user_id',$id)->count();
        $followers = Follows::where('follow_id',$id)->count();

        return response()->json(['profile' => $profile,'isFollow' => $isFollow,'follows' => $follows,'followers' => $followers]);
    }

    public function follow(Request $request) {
        $follow = Follows::where('user_id',$request->user_id)->where('follow_id',$request->follow_id)->first();
        if(!$follow) {
            Follows::create([
                'user_id' => $request->user_id,
                'follow_id' => $request->follow_id
            ]);
        }

        return response()->json(['message' => 'success']);
        
    }

    public function unFollow (Request $request) {
        Follows::where('user_id',$request->user_id)->where('follow_id',$request->follow_id)->delete();
        return response()->json(['message' => 'success']);
    }


    public function Like(Request $request) {
        $like = Likes::where('user_id',$request->user_id)->
        where('posts_id',$request->posts_id)->
        first();

        if(!$like) {
            $__like = Likes::create([
                'user_id' => $request->user_id,
                'posts_id' => $request->posts_id
            ]);
        }
        $_like = Likes::where('id',$__like->id)->first();
        return response()->json($_like);
    }

    public function UnLike(Request $request) {
       Likes::where('user_id',$request->user_id)->
        where('posts_id',$request->posts_id)->
        delete();

        return response()->json(['message' => 'success']);
    }

    public function comment(Request $request) {
        $comment = Comments::create([
            'comment' => $request->comment,
            'user_id' => $request->user_id,
            'posts_id' => $request->post_id
        ]);
        $_comment = Comments::where('id',$comment->id)->first();

        return response()->json($_comment);
    }

    public function test() {
        return response()->json(Comments::get());
    }

    public function search(Request $request) {
        $users = User::where('name','like','%'.$request->search.'%')->get();

        return response()->json($users);
    }
}
