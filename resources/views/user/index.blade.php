@extends('user.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Laravel 8 CRUD Example from scratch - ItSolutionStuff.com</h2>
            </div>
            <div class="pull-right">
                @can('user-create')
                    <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
                @endcan

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
            <a class="btn btn-success" href="{{ route('roles.index') }}"> Role</a>
            <a class="btn btn-success" href="{{ route('products.index') }}"> Products</a>
            <a class="btn btn-success" href="{{ route('permissions.index') }}"> Permission</a>
        </div>
    </div>

    <table class="table table-bordered">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Products</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if (!empty($user->getRoleNames()))
                        @foreach ($user->getRoleNames() as $role)
                            <span class="badge rounded-pill bg-dark">{{ $role }}</span>
                        @endforeach
                    @endif
                </td>
                <td>
                    @if ($user->products->isNotEmpty())
                        @foreach ($user->products as $product)
                            <p>{{ $product->name }}</p>
                        @endforeach
                    @else
                        <p>No products</p>
                    @endif
                </td>
                <td>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('users.show', $user->id) }}">Show</a>
                        @can('user-edit')
                            <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}">Edit</a>
                        @endcan

                        @can('user-delete')
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @endcan
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $users->links() !!}
@endsection
