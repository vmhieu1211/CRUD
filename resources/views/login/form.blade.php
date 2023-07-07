@extends('login.layout')
@section('content')
<form method="POST" action="{{ route('login.submit') }}">
  @csrf
  <div>
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

        @error('email')
            <span>{{ $message }}</span>
        @enderror
  </div>
  <div>
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>
  </div>
  <div>
      <button type="submit">Đăng nhập</button>
  </div>
</form>
  @endsection