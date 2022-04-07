<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Permission;
use DB;
use Log;

class PermissionController extends Controller
{
    public function create()
    {
        $modulesTmp = [];
        foreach (config('permission.modules') as $key => $mod) {
            $modulesTmp[] = $key;
        }

        $Arrpers = [];
        foreach (Permission::all() as $per) {
            $nameTmp = explode(' ', $per->name);
            $nameModule = end($nameTmp);
            if(!in_array($nameModule, $Arrpers)) {
                $Arrpers[] = $nameModule;
            }
        }

        $modules = array_diff($modulesTmp, $Arrpers);

        return view('admin.permission.create', compact('modules'));
    }

    public function get_actions(Request $request)
    {
        $actions = config('permission.modules.'.$request->module);
        return response()->json($actions);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required'
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->name as $value) {
                Permission::firstOrCreate(['name' => $value]);
            }
            DB::commit();
            return redirect()->route('admin.permission.create');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
            return back();
        }
    }
}
