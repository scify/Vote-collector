<!--todo: add confirmation for deleting things instead of deleting them with one click-->
{!! Form::open(['url' => $url . '/' . $id]) !!}
    {!! Form::hidden('_method', 'DELETE') !!}
    {!! Form::submit('Διαγραφή', ['class' => 'btn btn-danger btn-xs']) !!}
{!! Form::close() !!}