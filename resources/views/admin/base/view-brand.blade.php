@extends('admin.layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">All Brands</h4>
                </div>
                <div class="card-body">
                    <div id="delete-message" class="mb-5 mt-2"></div>

                    <div class="table-responsive">
                        <table id="brandsTable" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Brand Id</th>
                                    <th>Brand Name</th>
                                    <th>Brand Icon</th>
                                    <th>Brand Image</th>
                                    <th>Brand Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>101</td>
                                    <td>Apple</td>
                                    <td>
                                        <img src="https://via.placeholder.com/50" alt="Apple Icon" width="50">
                                    </td>
                                    <td>
                                        <img src="https://via.placeholder.com/50" alt="Apple Image" width="50">
                                    </td>
                                    <td>
                                        <button class="btn btn-success">
                                            Active
                                        </button>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="#" class="btn btn-primary mx-1">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger mx-1">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td>102</td>
                                    <td>Samsung</td>
                                    <td>
                                        <img src="https://via.placeholder.com/50" alt="Samsung Icon" width="50">
                                    </td>
                                    <td>
                                        <img src="https://via.placeholder.com/50" alt="Samsung Image" width="50">
                                    </td>
                                    <td>
                                        <button class="btn btn-danger">
                                            Inactive
                                        </button>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="#" class="btn btn-primary mx-1">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger mx-1">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>3</td>
                                    <td>103</td>
                                    <td>Sony</td>
                                    <td>
                                        <img src="https://via.placeholder.com/50" alt="Sony Icon" width="50">
                                    </td>
                                    <td>
                                        <img src="https://via.placeholder.com/50" alt="Sony Image" width="50">
                                    </td>
                                    <td>
                                        <button class="btn btn-success">
                                            Active
                                        </button>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="#" class="btn btn-primary mx-1">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger mx-1">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
