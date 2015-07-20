{!! Form::open(['url' => $url . '/' . $id, 'onclick' => 'return confirm("Είστε σίγουροι;")']) !!}
    {!! Form::hidden('_method', 'DELETE') !!}
    {!! Form::submit('Διαγραφή', ['class' => 'btn btn-danger btn-xs']) !!}
{!! Form::close() !!}