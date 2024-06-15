@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Footer</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Footer Info</h4>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.footer-info.update', 1) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <img src="{{ asset(@$footerInfo->lgog) }}" width="100px" alt="">
                                    <br>
                                    <label>Footer Logo</label>
                                    <input type="file" class="form-control" name="logo">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" class="form-control" name="phone" value="{{ @$footerInfo->phone }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="email" value="{{ @$footerInfo->email }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" value="{{ @$footerInfo->address }}">
                                </div>
                                <div class="form-group">
                                    <label>Copyright</label>
                                    <input type="text" class="form-control" name="copyright" value="{{ @$footerInfo->copyright }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
