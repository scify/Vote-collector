@extends('app')

@section('content')
    <h1>Επεξεργασία {{ $member->first_name }} {{ $member->last_name }}</h1>

    @include('errors.list')

    {!! Form::model($member, ['route' => ['members.update', $member->id], 'method' => 'PUT']) !!}
        @include('members._form', ['submitButtonText' => 'Αποθήκευση'])
    {!! Form::close() !!}
@stop