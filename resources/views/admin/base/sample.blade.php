@extends('admin.layouts.main')


@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">Base Page</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Users</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Avatars</a>
            </li>
        </ul>
    </div>
@endsection
