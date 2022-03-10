<?php

namespace App\Http\Controllers\Backend;

use App\Admin;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admins.index');
    }

    public function getData(Request $request){
        $admins = Admin::select('id','name','is_active','created_at');
        if($request->has('sort')){
            if ($request->get('sort')=='Name') {
                $admins = $admins->orderByRaw('name ASC');
            }elseif ($request->get('sort')=='Date created') {
                $admins = $admins->orderByRaw('created_at DESC');
            }elseif($request->get('sort')=='Active'){
                $admins = $admins->where('is_active', '=', 1);
            }elseif($request->get('sort')=='Hidden'){
                $admins = $admins->where('is_active', '=', 0);
            }
        }
        if($request->has('name')){
            $admins = $admins->where('name','like',"%" . $request->get('name') . "%");
        }

        return DataTables::of($admins->get()->toArray())
        ->editColumn('id', function ($admin){
            return '<div class="main__table-text">'. $admin['id'] .'</div>';
        })
        ->editColumn('name', function ($admin){
            return '<div class="main__table-text">'. $admin['name'] .'</div>';
        })
        ->editColumn('is_active', function ($admin){
            $active = $admin['is_active'] == 1 ? '<div class="main__table-text main__table-text--green">Active</div>':'<div class="main__table-text main__table-text--red">Hidden</div>';
            return $active;
        })

        ->addColumn('action', function ($admin) {
            return '
            <div class="main__table-btns">
                <a href="'. route('backend.admin.edit' , $admin['id']) .'" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                        <i class="icon ion-ios-create"></i>
                    </a>
                <form action="'. route('backend.admin.destroy' , $admin['id']) .'" method="POST">
                    '. csrf_field() .'
                    '. method_field('DELETE') .'
                    <button type="submit" class="main__table-btn main__table-btn--delete" data-toggle="tooltip" title="Delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
            ';
        })
        ->rawColumns(['id','name','is_active','action'])

        ->make(true);
    }

    public function getRoles(Request $request, $adminId){
        $roles = Role::select('id','name','description');


        if($request->has('name')){
            $roles = $roles->where('name','like',"%" . $request->get('name') . "%");
        }
        $roles = $roles->get();
        foreach ($roles as $role) {
            $role->admin_id = $adminId;
        }

        return DataTables::of($roles->toArray())
        ->addColumn('action', function ($role) {
            $check = '';
            $adminRoles = Admin::findOrFail($role['admin_id'])->roles()->get()->toArray();
            foreach ($adminRoles as $value) {
                if ($value['id'] == $role['id']) {
                    $check = 'checked';
                }
            }
            return '
            <div class="main__table-btns">
                <input type="checkbox" name="roles[]" value="'. $role['id'] .'"'.$check.' style=" width: 20px; height: 20px; -webkit-appearance: checkbox;" onchange="submitChange('. $role['id'] .')">
            </div>
            ';
        })
        ->editColumn('id', function ($role){
            return '<div class="main__table-text">'. $role['id'] .'</div>';
        })
        ->editColumn('name', function ($role){
            return '<div class="main__table-text">'. $role['name'] .'</div>';
        })
        ->editColumn('description', function ($role){
            return '<div class="main__table-text">'. $role['description'] .'</div>';
        })

        ->rawColumns(['action', 'id', 'name', 'description'])

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
        return view('backend.admins.create')->with([
            'roles'=>$roles->toArray()
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
                'email'=>'required|min:3',
                'phone'=>'required|min:8',
                'address'=>'required|min:2',
                'password'=>'required|min:6|confirmed'
            ],
            [
                'name' => 'Name',
                'email' => 'Email',
                'phone' => 'Phone number',
                'address' => 'Address',
                'password' => 'Password'
            ],
        );
        if (!count(Admin::where('email', '=', $request->get('email'))->get()) ==null) {
        $validator->after(function ($validator) {
            $validator->errors()->add('email','Email đã tồn tại');
        });
        }
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $admin = new Admin();

        $admin->name = $request->get('name');
        $admin->email = $request->get('email');
        $admin->phone = $request->get('phone');
        $admin->address = $request->get('address');
        $admin->password = bcrypt($request->get('password'));

        $admin->user_created_id = Auth::user()->id;
        $admin->user_updated_id = Auth::user()->id;
        $admin->save();
        $roles = $request->get('roles');
        $admin->roles()->attach($roles,[
            'user_created_id'=>1,
            'user_updated_id'=>1
        ]);
        $admin->save();

        return redirect()->route('backend.admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show($adminId)
    {
        $roles = Role::all();
        $admin = Admin::findOrFail($adminId);
        $adminRoles = $admin->roles;
        foreach ($adminRoles as $role) {
            $roles = $roles->where('id', '!=', $role->id);
        }
        $adminRoles = $admin->roles()->paginate(10);
        $links = str_replace("pagination","paginator", str_replace("page-item", "paginator__item", $adminRoles->links()));
        $links = str_replace("active","paginator__item--active", $links);
        $links = str_replace('<span class="page-link">','<a class="page-link" href="#">', $links);
        $links = str_replace('</span>','</a>', $links);
        return view('backend.admins.show')->with([
            'admin'=>$admin,
            'adminRoles'=>$adminRoles,
            'roles'=>$roles,
            'links'=>$links
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit($adminId)
    {
        $admin = Admin::findOrFail($adminId);
        return view('backend.admins.edit')->with([
            'admin'=>$admin->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $adminId)
    {
        $validator = Validator::make($request->all(),
            [
                'name'=>'required|min:2',
                'email'=>'required|min:3',
                'phone'=>'required|min:8',
                'address'=>'required|min:2',
            ],
            [
                'name' => 'Name',
                'email' => 'Email',
                'phone' => 'Phone number',
                'address' => 'Address',
            ]
        );
        if (!count(Admin::where('email', '=', $request->get('email'))->where('id', '!=', $adminId)->get()) ==null) {
        $validator->after(function ($validator) {
            $validator->errors()->add('email','Email đã tồn tại');
        });
        }
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $admin = Admin::findOrFail($adminId);

        $admin->name = $request->get('name');
        $admin->email = $request->get('email');
        $admin->phone = $request->get('phone');
        $admin->address = $request->get('address');

        $admin->user_updated_id = Auth::user()->id;
        $admin->save();

        return redirect()->route('backend.admin.index');
    }

    public function roles($adminId){
        $admin = Admin::findOrFail($adminId);
        $roles = $admin->roles()->get();
    }

    public function updateRole(Request $request, $adminId){
        $adminRoles = Admin::findOrFail($adminId)->roles();
        $roleId = $request->get('roleId');
        $adminRoles->toggle($roleId);

        return redirect()->back();
    }

    public function changeStatus($adminId){
        $admin = Admin::findOrFail($adminId);
        $admin->is_active = $admin->is_active == 0 ? 1:0;
        $admin->user_updated_id = Auth::user()->id;
        $admin->save();
        return redirect()->route('backend.admin.index');
    }

    public function changePassword(Request $request, $adminId){
        $validator = Validator::make($request->all(),
            [
                'password'=>'required|min:6|confirmed',
                'oldPassword'=>'required|min:6'
            ],
            [
                'password' => 'New Password',
                'oldPassword' => 'Old Password'
            ]
        );
        $admin = Admin::findOrFail($adminId);
        if ($admin->password != bcrypt($request->get('oldPassword'))) {
            $validator->after(function ($validator) {
                $validator->errors()->add('oldPassword','Old Password is incorrect');
            });
        }
        if ($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $admin->password = bcrypt($request->get('password'));
        $admin->user_updated_id = Auth::user()->id;
        $admin->save();
        return redirect()->route('backend.admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy($adminId)
    {
        $admin = Admin::findOrFail($adminId)->roles()->detach();
        $admin = Admin::destroy($adminId);
        return redirect()->route('backend.admin.index');
    }
}
