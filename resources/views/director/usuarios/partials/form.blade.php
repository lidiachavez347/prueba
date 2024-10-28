<div class="row">
    <div class="col">
        <div class="form group">
            {!! Form::label('nombres', 'Nombres:') !!}
            {!! Form::text('nombres', null, [
                'class' => 'form-control',
                'placeholder' => 'Nombre Completo del Usuario',
            ]) !!}
            @error('nombres')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col">
        <div class="form group">
            {!! Form::label('apellidos', 'Apellidos:') !!}
            {!! Form::text('apellidos', null, [
                'class' => 'form-control',
                'placeholder' => 'Apellido Completo del Usuario',
            ]) !!}
            @error('apellidos')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="form group">
            {!! Form::label('direccion', 'Direccion:') !!}
            {!! Form::textarea('direccion', null, [
                'class' => 'form-control',
                'placeholder' => 'Direccion del Usuario',
                'rows' => '3',
                'cols' => '40',
                'style' => 'resize: none',
            ]) !!}
            @error('direccion')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-4">
        <div class="form group">
            {!! Form::label('genero', 'Genero:') !!}
            {!! Form::select('genero', [null => 'SELECCIONE GENERO', '1' => ' MASCULINO', '0' => 'FEMENINO'], null, [
                'class' => 'form-control',
            ]) !!}
            @error('genero')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="form-group">
    @foreach ($cursos as $curso)
        {!! Form::label('id_curso', $curso, null, ['class' => 'form-control']) !!}
    @endforeach
</div>
<div class="row">
    <div class="col">
        <div class="form group">
            {!! Form::label('email', 'Email:') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col">
        <div class="form group">
            {!! Form::label('password', 'Contraseña: ') !!}
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="form-group">
            {!! Form::label('roles', 'Rol:') !!}
            {!! Form::select('roles', $roles, null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col">
        <div class="form group">
            {!! Form::label('estado_user', 'Estado:') !!}
            {!! Form::select('estado_user', [null => 'SELECCIONE ESTADO', '0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, [
                'class' => 'form-control',
                '',
            ]) !!}
            @error('estado_user')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
