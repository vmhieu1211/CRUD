<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessRequestJob;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-create', ['only' => ['create']]);
    }

    public function index()
    {
        $users = User::paginate(5);
        // foreach($users as $user){
        //     dd($user->products);
        // }
        return view('user.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'roles' => 'required'
            ]
        );
        $input = $request->all();
        $user = User::create($input);   
        $roles = $request->input('roles', []);
        $user->assignRole($roles);

        dispatch(new ProcessRequestJob($user))->delay(now()->addSeconds(10));


        return redirect()->route('users.index')
            ->with('success', 'User create successfully.');
    }


    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    public function edit(Request $request, User $user)
    {
        $user = User::find($user->id);
        // $roles = $request->roles;
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        // $user->assignRole($roles);
        // dd($userRole);
        return view('user.edit', compact('user', 'roles', 'userRole'));
    }


    public function update(Request $request, $id)
    {
        // dd($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'roles' => 'required'
        ]);

        $input  = $request->all();
        if (empty($input['password'])) {
            $input = Arr::except($input, ['password']);
        }
        $user = User::find($id);
        $user->update($input);

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $roles = $request->roles;
        $user->assignRole($roles);
        $user->syncRoles($roles);

        return redirect()->route('users.index')
            ->with('success', 'user updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'user deleted successfully');
    }
}
