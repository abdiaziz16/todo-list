<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Item;
use App\UserItems;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        return view('home');
    }

    /**
     * This end point creates a new item
     * It also creates a relationship for the person that created the item
     * @return \Illuminate\Http\JsonResponse
     */

    public function createItem(Request $request){
        $validator = Validator::make($request->all(),[
           'item'=>'required|max:255'
        ]);

        if($validator->fails()){
            # Validator fail resposne
            return response()->json([
                'success'=>false,
                'message' => $validator->messages()
            ]);
        }else{
            // try catch for making sure items and relationship to user are entered correctly.
            try{
                #Create the new item
                $new_item = new Item();
                $new_item->name = $request->item;
                $new_item->created_at = Carbon::now();
                $new_item->save();

                # Create user connection with the new item
                $user_item = new UserItems();
                $user_item->user_id = Auth::id();
                $user_item->item_id = $new_item->id;
                $user_item->save();
                return response()->json([
                    'success'=>true,
                    'message' => 'New todo has been added'
                ]);
            } catch (\Exception $e) {
                log::error($e);
                return response()->json([
                    'success'=>false,
                    'message' => 'Something went wrong. Please try again later.'
                ]);
            }

        }
    }

    /**
     * This end point returns all the items
     * If a user has a roleID of 1. They are an admin and we return all the items in the todo table
     * If the user is not an admint, we return only their items and we return only the items that have an active=1 and are not 'deleted'
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllItems(Request $request){
        if(Auth::user()->roleID == 1){
            $todos = DB::table('userItems')
                ->join('item', 'userItems.item_id', '=', 'item.id')
                ->join('users','userItems.user_id','=', 'users.id')
                ->select('users.name','item.name as itemName','item.id as itemID','item.completed as status','item.active as active')
                ->get('user.id');
        }else{
            $todos = DB::table('userItems')
                ->join('item', 'userItems.item_id', '=', 'item.id')
                ->join('users','userItems.user_id','=', 'users.id')
                ->select('item.name as itemName','item.id as itemID','item.completed as status','item.active as active')
                ->where('userItems.user_id','=',Auth::id())
                ->where('item.active','=',1)
                ->get();
        }
        return $todos;
    }

    /**
     * This end point deletes an item from the DB based on ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request){
        $id = $request->id;
        try {
            if (is_numeric($id)) {
                DB::table('item')
                    ->where('id', '=', $id)
                    ->update(['active' => 0]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully'
            ]);
        } catch (\Exception $e) {
            log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Could not mark item as comlete'
            ]);
        }
    }

    /**
     * This end point updates an item based on ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request){
        $id = $request->id;
        $item = $request->item;
        try {
            if (is_numeric($id)) {
                DB::table('item')
                    ->where('id', '=', $id)
                    ->update(['name' => $item]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Item successfully updated'
            ]);
        } catch (\Exception $e) {
            log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Could not edit item'
            ]);
        }
    }
    /**
     * This end point marks an item as compelted based on ID
     * This item also keeps track of the time stamp for when an item was complete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeItem(Request $request)
    {
        $id = $request->id;
        try {
            if (is_numeric($id)) {
                DB::table('item')
                    ->where('id', '=', $id)
                    ->update(['completed' => 1, 'completed_at' => Carbon::now()]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Item completed successfully'
            ]);
        } catch (\Exception $e) {
            log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Could not mark item as complete'
            ]);
        }
    }

}
