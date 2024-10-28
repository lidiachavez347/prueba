<div class="form-group">
    {!! Form::label('name','Nombre') !!}
    {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el nombre del rol']) !!}
    @error('name')
    <small class="text-danger">
        {{$message}}
    </small>
    @enderror
</div>

<div class="row">
 
    <div class="col-4">


        <div class="form group">
            {!! Form::label('estado', 'Estado:') !!}
            {!! Form::select('estado',[null => 'SELECCIONE ESTADO','0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, [
            'class' => 'form-control',
            ]) !!}
            @error('estado')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>



<br>

<div class="col">
    <div class="card">
        <div class="card-header">
            <h2 class="h3">Permisos</h2>
        </div>
        <div class="card-body">


            @foreach ($permissions as $permission)
            <div>
                <label>
                    {!! Form::checkbox('permissions[]', $permission->id, null, ['class'=>'mr-1']) !!}
                    {{$permission->description}}
                </label>
            </div>
            @endforeach
            
        </div>

    </div>
</div>