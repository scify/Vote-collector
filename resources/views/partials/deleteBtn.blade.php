{!! Form::open(['url' => $url . '/' . $id, 'onclick' => 'return confirm("Διαγραφή;")']) !!}
    {!! Form::hidden('_method', 'DELETE') !!}
    {!! Form::submit('Διαγραφή', ['class' => 'btn btn-danger btn-xs']) !!}
{!! Form::close() !!}