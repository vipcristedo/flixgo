<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.roles.index');
    }
    
    public function getData(Request $request){
        $roles = Role::select('id','name','description','status','created_at');
        if($request->has('sort')){
            if ($request->get('sort')=='Name') {
                $roles = $roles->orderByRaw('name ASC');
            }elseif ($request->get('sort')=='Date created') {
                $roles = $roles->orderByRaw('created_at DESC');
            }elseif($request->get('sort')=='Active'){
                $roles = $roles->where('status', '=', '1');
            }elseif($request->get('sort')=='Hidden'){
                $roles = $roles->where('status', '=', '0');
            }
        }
        if($request->has('name')){
            $roles = $roles->where('name','like',"%" . $request->get('name') . "%");
        }

        return DataTables::of($roles->get()->toArray())
        ->editColumn('id', function ($role){
            return '<div class="main__table-text">'. $role['id'] .'</div>';
        })
        ->editColumn('name', function ($role){
            return '<div class="main__table-text">'. $role['name'] .'</div>';
        })
        ->editColumn('description', function ($role){
            return '<div class="main__table-text">'. $role['description'] .'</div>';
        })
        ->editColumn('status', function ($role){
            if ($role['status'] == 1) {
                return '<div class="main__table-text main__table-text--green">Active</div>';
            }else
            return '<div class="main__table-text main__table-text--red">Hidden</div>';
        })

        ->addColumn('action', function ($role) {
            return '
            <div class="main__table-btns">
                <a href="'. route('backend.role.edit' , $role['id']) .'" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                        <i class="icon ion-ios-create"></i>
                    </a>
                <form action="'. route('backend.role.destroy' , $role['id']) .'" method="POST">
                    '. csrf_field() .'
                    '. method_field('DELETE') .'
                    <button type="submit" class="main__table-btn main__table-btn--delete" data-toggle="tooltip" title="Delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
            ';
        })
        ->rawColumns(['id','name','description','status','action'])

        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name'=>'required|min:2',
                'description'=>'required|min:8',
            ],
            [
                'name' => 'Name',
                'description' => 'Description',
            ]
        );
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = new Role();

        $role->name = $request->get('name');
        $role->description = $request->get('description');
        $role->status = 1;

        $role->user_created_id = Auth::user()->id; // Auth::user()->id;
        $role->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $role->save();

        return redirect()->route('backend.role.index');
    }

    public function getPermissions(Request $request, $roleId){
        $permissions = Permission::select('id', 'name', 'display_name', 'description');
        

        if($request->has('name')){
            $permissions = $permissions->where('name','like',"%" . $request->get('name') . "%");
        }
        $permissions = $permissions->get();
        foreach ($permissions as $permission) {
            $permission->role_id = $roleId;
        }

        return DataTables::of($permissions->toArray())
        ->addColumn('action', function ($permission) {
            $check = '';
            $rolePermissions = Role::findOrFail($permission['role_id'])->permissions()->get()->toArray();
            foreach ($rolePermissions as $value) {
                if ($value['id'] == $permission['id']) {
                    $check = 'checked';
                }
            }
            return '
            <div class="main__table-btns">
                <input type="checkbox" name="permissions[]" value="'. $permission['id'] .'"'.$check.' style=" width: 20px; height: 20px; -webkit-appearance: checkbox;" onchange="submitChange('. $permission['id'] .')">
            </div>
            ';
        })
        ->editColumn('id', function ($permission){
            return '<div class="main__table-text">'. $permission['id'] .'</div>';
        })
        ->editColumn('name', function ($permission){
            return '<div class="main__table-text">'. $permission['name'] .'</div>';
        })
        ->editColumn('display_name', function ($permission){
            return '<div class="main__table-text">'. $permission['display_name'] .'</div>';
        })
        ->editColumn('description', function ($permission){
            return '<div class="main__table-text">'. $permission['description'] .'</div>';
        })

        ->rawColumns(['action', 'id', 'name', 'display_name','description'])

        ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($roleId)
    {
        $role = Role::findOrFail($roleId);
        // return view('backend.roles.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($roleId)
    {
        $role = Role::findOrFail($roleId);
        return view('backend.roles.edit')->with([
            'role'=> $role->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $roleId)
    {
        $validator = Validator::make($request->all(),
            [
                'name'=>'required|min:2',
                'description'=>'required|min:8',
            ],
            [
                'name' => 'Name',
                'description' => 'Description',
            ]
        );
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::findOrFail($roleId);

        $role->name = $request->get('name');
        $role->description = $request->get('description');
        $role->status = $request->get('status');

        $role->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $role->save();

        return riderect()->route('backend.role.index');
    }

    public function admins($roleId){
        $role = Role::findOrFail($roleId);
        $roles = $role->admins()->get();
    }

    public function permissions($roleId){
        $role = Role::findOrFail($roleId);
        $roles = $role->permissions()->get();
    }

    public function changeStatus($roleId){
        $role = Role::findOrFail($roleId);
        $role->status = $role->status == 0 ? 1:0;
        $role->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $role->save();
        return redirect()->route('backend.role.index');
    }

    public function updatePermission(Request $request, $roleId){
        $rolePermissions = Role::findOrFail($roleId)->permissions();
        $permissionId = $request->get('permissionId');
        $rolePermissions->toggle($permissionId);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->user_updated_id = Auth::user()->id;
        $role->save();
        $rolePermissions = $role->permissions()->detach();
        $roleAdmins = $role->admins()->detach();
        $role = Role::destroy($roleId);
        return redirect()->route('backend.role.index');
    }
}
