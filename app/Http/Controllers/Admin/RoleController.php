<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Datatables;
use Role;
use Permission;
use DB;
use Log;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::get();
        return DataTables::of($roles)
            ->addColumn('action', function ($role) {
                if(mb_strtolower($role->name) !== 'admin' && mb_strtolower($role->name) !== 'developer') {
                    return '<a href="'.route('admin.role.edit', ['id' => $role->id]).'" class="btn btn-info">Edit</a>
                    <a data-href="'.route('admin.role.delete', ['id' => $role->id]).'" class="btn btn-danger action-delete">Delete</a>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $permissions = Permission::all();
        $modules = [];
        foreach ($permissions as $permission) {
            $arrPers = explode(' ', $permission->name);
            if (!in_array(end($arrPers), $modules)) {
                $modules[] = end($arrPers);
            } 
        }
        return view('admin.role.create', compact('permissions', 'modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => ['bail','required','unique:roles','max:255'],
            'description' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            $role->permissions()->attach($request->permission_id);
    
            DB::commit();
            return redirect()->route('admin.role.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
            return back();
        }
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all();
        $modules = [];
        foreach ($permissions as $permission) {
            $arrPers = explode(' ', $permission->name);
            if (!in_array(end($arrPers), $modules)) {
                $modules[] = end($arrPers);
            } 
        }
        return view('admin.role.edit', compact('role','modules','permissions'));
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
        $role = Role::find($id);
        
        $validator = $request->validate([
            'name' => ['bail','required','unique:roles,name,'.$role->name.',name','max:255'],
            'description' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $role->update([
                'name' => $request->name,
                'description' => $request->description
            ]);
            $role->permissions()->sync($request->permission_id);
            DB::commit();
            return redirect()->route('admin.role.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
            return back();
        }
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->permissions()->delete();
        $role->delete();
        return response()->json($role);
    }
}
