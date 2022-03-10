<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.permissions.index');
    }

    public function getData(Request $request){
        $permissions = Permission::select('id','name','display_name','status','created_at');
        
        if($request->has('sort')){
            if ($request->get('sort')=='Name') {
                $permissions = $permissions->orderByRaw('name ASC');
            }elseif ($request->get('sort')=='Date created') {
                $permissions = $permissions->orderByRaw('created_at DESC');
            }elseif($request->get('sort')=='Active'){
                $permissions = $permissions->where('status', '=', '1');
            }elseif($request->get('sort')=='Hidden'){
                $permissions = $permissions->where('status', '=', '0');
            }
        }
        if($request->has('name')){
            $permissions = $permissions->where('name','like',"%" . $request->get('name') . "%");
        }

        return DataTables::of($permissions->get()->toArray())
        ->editColumn('id', function ($permission){
            return '<div class="main__table-text">'. $permission['id'] .'</div>';
        })
        ->editColumn('name', function ($permission){
            return '<div class="main__table-text">'. $permission['name'] .'</div>';
        })
        ->editColumn('display_name', function ($permission){
            return '<div class="main__table-text">'. $permission['display_name'] .'</div>';
        })
        ->editColumn('status', function ($permission){
            if ($permission['status'] == 1) {
                return '<div class="main__table-text main__table-text--green">Active</div>';
            }else
            return '<div class="main__table-text main__table-text--red">Hidden</div>';
        })

        ->addColumn('action', function ($permission) {
            $status = '';
            if($permission['status'] == 1){
                $status = '<i class="icon ion-ios-lock" data-toggle="tooltip" title="Hidden"></i>';
            }
            else{
                $status = '<i class="fa fa-unlock-alt" data-toggle="tooltip" title="Activate"></i>';
            }
            return '
            <div class="main__table-btns">
                <form action="'. route('backend.permission.changeStatus', $permission['id']) .'" method="POST">
                    '. csrf_field() .'
                    <button type="submit" class="main__table-btn main__table-btn--banned" style="margin-right:10px;">
                        '. $status .'
                    </button>
                </form>
                <a href="'. route('backend.permission.show' , $permission['id']) .'" class="main__table-btn main__table-btn--view" data-toggle="tooltip" title="Show">
                        <i class="icon ion-ios-eye"></i>
                    </a>
            </div>
            ';
        })
        ->rawColumns(['id','name','display_name','status','action'])

        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('backend.permissions.create')->with([
            'roles'=>$roles
        ]);
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
                'display_name'=>'required|min:2',
                'description'=>'required|min:8',
            ],
            [
                'name' => 'Name',
                'display_name' => 'Display name',
                'description' => 'Description',
            ]
        );
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $permission = new Permission();

        $permission->name = $request->get('name');
        $permission->display_name = $request->get('display_name');
        $permission->description = $request->get('description');

        $permission->user_created_id = Auth::user()->id; // Auth::user()->id;
        $permission->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $permission->save();

        $roles = $request->get('roles');
        $permission->roles()->attach($roles,[
            'user_created_id'=>1,
            'user_updated_id'=>1
        ]);

        return redirect()->route('backend.permission.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show($permissionId)
    {
        $roles = Role::all();
        $permission = Permission::findOrFail($permissionId);
        $permissionRoles = $permission->roles;
        foreach ($permissionRoles as $role) {
            $roles = $roles->where('id', '!=', $role->id);
        }
        $permissionRoles = $permission->roles()->paginate(10);
        $links = str_replace("pagination","paginator", str_replace("page-item", "paginator__item", $permissionRoles->links()));
        $links = str_replace("active","paginator__item--active", $links);
        $links = str_replace('<span class="page-link">','<a class="page-link" href="#">', $links);
        $links = str_replace('</span>','</a>', $links);
        return view('backend.permissions.show')->with([
            'permission'=>$permission,
            'permissionRoles'=>$permissionRoles,
            'roles'=>$roles,
            'links'=>$links
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit($permissionId)
    {
        $roles = Role::all();
        $permission = Permission::findOrFail($permissionId);
        $permissionRoles = $permission->roles;
        foreach ($permissionRoles as $role) {
            $roles = $roles->where('id', '!=', $role->id);
        }
        $permissionRoles = $permission->roles()->paginate(10);
        $links = str_replace("pagination","paginator", str_replace("page-item", "paginator__item", $permissionRoles->links()));
        $links = str_replace("active","paginator__item--active", $links);
        $links = str_replace('<span class="page-link">','<a class="page-link" href="#">', $links);
        $links = str_replace('</span>','</a>', $links);
        return view('backend.permissions.edit')->with([
            'permission'=>$permission,
            'permissionRoles'=>$permissionRoles,
            'roles'=>$roles,
            'links'=>$links
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $permissionId)
    {
        $validator = Validator::make($request->all(),
            [
                'name'=>'required|min:2',
                'display_name'=>'required|min:2',
                'description'=>'required|min:8',
            ],
            [
                'name' => 'Name',
                'display_name' => 'Display name',
                'description' => 'Description',
            ]
        );
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $permission = Permission::findOrFail($permissionId);

        $permission->name = $request->get('name');
        $permission->display_name = $request->get('display_name');
        $permission->description = $request->get('description');
        $permission->status = $request->get('status');

        $permission->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $permission->save();

        $roles = $request->get('roles');
        $permission->roles()->sync($roles);

        return redirect()->route('backend.permission.index');
    }

    public function roles($permissionId){
        $permission = Permission::findOrFail($permissionId);
        $roles = $permission->roles()->get();
    }

    public function updateRole(Request $request, $permissionId){
        $roles = Permission::findOrFail($permissionId)->roles();
        if ($roles->detach($request->get('roleId')) == false) {
            $roles->attach($request->get('roleId'),[
                'user_created_id'=>1,
                'user_updated_id'=>1
            ]);
        }
        return redirect()->back();
    }

    public function changeStatus($permissionId){
        $permission = Permission::findOrFail($permissionId);
        $permission->status = $permission->status == 0 ? 1:0;
        $permission->user_updated_id = Auth::user()->id; // Auth::user()->id;
        $permission->save();
        return redirect()->route('backend.permission.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($permissionId)
    {
        $permission = Permission::findOrFail($permissionId)->roles()->detach();
        $permission = Permission::destroy($permissionId);
        return redirect()->route('backend.permission.index');
    }
}
