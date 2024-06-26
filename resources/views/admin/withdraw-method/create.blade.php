@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Withdraw Methods</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Method</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.withdraw-method.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" value="">
                                </div>
                                <div class="form-group">
                                    <label>Minimum Amount</label>
                                    <input type="text" class="form-control" name="minimum_amount" value="">
                                </div>
                                <div class="form-group">
                                    <label>Maximum Amount</label>
                                    <input type="text" class="form-control" name="maximum_amount" value="">
                                </div>
                                <div class="form-group">
                                    <label>Withdraw Charge (%)</label>
                                    <input type="text" class="form-control" name="withdraw_charge" value="">
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"></textarea>
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
