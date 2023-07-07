    @extends('role.layout')
    @section('content')
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Role Management</h2>
                </div>
                <div class="pull-right">

                    <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role</a>

                    @if (Auth::check())
                        <a class="btn btn-success" href="{{ route('logoutSubmit') }}"> Logout</a>
                    @endif

                    <p>Logged in as: {{ auth()->user()->name }}</p>
                    <p>Role: {{ auth()->user()->roles->pluck('name')->implode(', ') }}</p>
                    
                </div>

            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <a class="btn btn-success" href="{{ route('users.index') }}"> User</a>
                <a class="btn btn-success" href="{{ route('products.index') }}"> Products</a>
                <a class="btn btn-success" href="{{ route('permissions.index') }}"> Permission</a>
            </div>
        </div>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Permission</th>
                <th width="280px">Action</th>
            </tr>

            @foreach ($roles as $key => $role)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        @if (!empty($role->permissions))
                            @foreach ($role->permissions as $permission)
                                <label class="label label-success">{{ $permission->name }},</label>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-info" href="{{ route('roles.show', $role->id) }}">Show</a>

                        <a class="btn btn-primary" href="{{ route('roles.edit', $role->id) }}">Edit</a>

                        {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}

                    </td>
                </tr>
            @endforeach
        </table>
        {!! $roles->render() !!}
    @endsection
