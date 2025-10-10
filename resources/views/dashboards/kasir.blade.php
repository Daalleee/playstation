@extends('layouts.app')
@section('content')
@include('kasir.partials.nav')
<h1>Dashboard Kasir</h1>
@if(session('impersonate_admin_id'))
    <form action="{{ route('admin.impersonate.leave') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" style="background:#fd7e14; color:white; border:none; padding:4px 12px; border-radius:4px;">Kembali ke Admin</button>
    </form>
@endif
