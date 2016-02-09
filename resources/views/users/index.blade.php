@extends('app')

@section('content')
    <div class="container">
        {!! $datatables->table(['class' => 'striped', 'id' => 'users']) !!}
    </div>
@endsection
@section('runtime_scripts')
    {!! $datatables->scripts() !!}
@endsection
