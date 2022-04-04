<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Avatar;
use Datatables;
use Role;
use Log;
use DB;
use Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('type', 0)->get();
        return DataTables::of($users)
            
            ->addColumn('action', function ($user) {
                return '<a href="'.route('admin.user.edit', ['id' => $user->id]).'" class="btn btn-info">Edit</a>
                        <a data-href="'.route('admin.user.delete', ['id' => $user->id]).'" class="btn btn-danger action-delete">Delete</a>';
            })
            ->make(true);
    }

    public function customers()
    {
        $users = User::where('type', '>', 0)->get();
        return DataTables::of($users)
            ->addColumn('orders', function ($user) {
                return $user->orders->count();
            })
            ->addColumn('amount', function ($user) {
                return $user->orders->sum('amount');
            })
            ->addColumn('level', function ($user) {
                $level = $user->type == 1 ? '<span class="btn btn-primary">Normal</span>' : '<span class="btn btn-danger">VIP</span>';
                return $level;
            })
            ->addColumn('check', function ($user) {
                return '<input type="checkbox" name="user_id[]" class="item" value="'.$user->id.'" />';
            })
            ->rawColumns(['check','level','orders','amount'])
            ->make(true);
    }

    public function cus_update(Request $request)
    {
        foreach($request->user_id as $id) {
            User::find($id)->update([
                'type' => 2
            ]);
        }
        return response()->json([
            'message' => 'success',
            'code' => 200
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['bail','required','min:10','max:255'],
            'email' => ['bail','required','email'],
            'role_id' => ['bail','required','array','max:5'],
            'password' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $photo = Avatar::create($request->name)->toBase64();
            $basephoto = base64_decode(explode('base64,', $photo)[1]);
            $extension = explode('/', explode(';base64', $photo)[0])[1];
            $path = 'public/avatar/'.time().'.'.$extension;
            \Storage::put($path, $basephoto);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile_photo_path' => \Storage::url($path),
            ]);
    
            $user->roles()->attach($request->role_id);
            DB::commit();
            return redirect()->route('admin.user.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::get();
        return view('admin.user.edit', compact('user','roles'));
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
        $request->validate([
            'name' => 'required',
            'email' => ['bail','required','email','unique:users,email,'.User::find($id)->email.',email'],
            'role_id' => 'required',
            'password' => 'required'
        ]);

        try {
            DB::beginTransaction();
            User::find($id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $user = User::find($id);
            $user->roles()->sync($request->role_id);
            DB::commit();
            return redirect()->route('admin.user.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();
        return response()->json($user);
    }
}
