@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-3">Masuk</h1>
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <form method="POST" action="{{ route('login.post') }}" class="gy-3">
          @csrf
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" required class="form-control" />
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-primary">Masuk</button>
            <a href="{{ route('register.show') }}">Daftar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection


