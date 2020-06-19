@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Token</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <github-token-form></github-token-form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
        </div>
    </div>
</div>
@endsection
