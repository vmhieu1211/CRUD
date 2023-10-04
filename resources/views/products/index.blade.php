@extends('products.layout')

@section('content')
    @csrf
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Laravel 8 CRUD Example from scratch - ItSolutionStuff.com</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Product</a>

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
            <a class="btn btn-success" href="{{ route('roles.index') }}"> Role</a>
            <a class="btn btn-success" href="{{ route('permissions.index') }}"> Permission</a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <form action="{{ route('products.search') }}" method="GET">
                <div class="input-group">
                    <input type="date" id="from_date" name="from_date" required class="form-control"
                        placeholder="From Date" value="{{ request('from_date') }}">
                    <span class="input-group-addon">to</span>
                    <input type="date" id="to_date" name="to_date" required class="form-control" placeholder="To Date"
                        value="{{ request('to_date') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Details</th>
            <th>Date</th>
            <th>Author</th>
            <th>Image used</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($products as $product)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $product->name }}</td>
                <td>{!! $product->detail !!}</td>
                <td>{{ $product->created_at }}</td>

                <td>
                    @if ($product->author)
                        <span>{{ $product->user->name }}</span>
                    @else
                        Null
                    @endif
                </td>


                <td>
                    @if ($product->images)
                        <img alt="" style="max-width: 150px; margin-bottom: 10px;" src="{{ $product->images }}">
                    @else
                        No Image
                    @endif
                </td>
                <td>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('products.show', $product->id) }}">Show</a>
                        @can('product-edit')
                            <a class="btn btn-primary" href="{{ route('products.edit', $product->id) }}">Edit</a>
                        @endcan
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    {!! $products->links() !!}

@endsection
