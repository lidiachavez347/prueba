<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('roles', 'Rol:') !!}
                {!! Form::select('roles', $roles, null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('ci', 'CI: ') !!}
                {!! Form::number('ci', null, ['class' => 'form-control', 'placeholder' => 'Cedula de identidad']) !!}
                @error('ci') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
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
        <div class="col-md-6">
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
        <div class="col-md-6">
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
        <div class="col-md-6">
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


    <div class="row">
        <div class="col-md-6">
            <div class="form group">
                {!! Form::label('email', 'Email:') !!}
                {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form group">
                {!! Form::label('password', 'Contraseña: ') !!}
                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password-input', 'name' => 'password']) !!}
                <small id="password-strength" class="form-text"></small>
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 d-none">

        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('telefono', 'Teléfono') !!}
                {!! Form::tel('telefono', old('telefono', $users->telefono), ['class' => 'form-control', 'placeholder' => 'Número de teléfono']) !!}
                @error('telefono')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('fecha_nac', 'Fecha Nacimiento:') !!}
                {!! Form::date('fecha_nac', null, ['class' => 'form-control', 'placeholder' => 'Fecha']) !!}
                @error('fecha_nac') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @if ($users->imagen > 0)
            <center>
                <div class="grid grid-cols-1 mt-5 mx-7">
                    <img id="imagenseleccionada" src="{{ URL::asset("images/{$users->imagen}") }}"
                        style="max-height: 100px">
                </div>
            </center>
            @else
            <center>
                <div class="grid grid-cols-1 mt-5 mx-7">
                    <img id="imagenseleccionada"
                        style="max-height: 100px">
                </div>
            </center>
            @endif
            <div class="form-group">
                {!! Form::label('imagen', 'Subir Imagen') !!}
                {!! Form::file('imagen', ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-6">
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
</div>
<br>
<script>
    $(document).ready(function() {
        const emailInput = $('input[name="email"]'); // Definir emailInput correctamente
        const emailOriginal = emailInput.val();

        // Crear elemento para feedback de email si no existe
        let feedbackEmail = $('#email-feedback');
        if (feedbackEmail.length === 0) {
            feedbackEmail = $('<small id="email-feedback" class="form-text"></small>');
            emailInput.after(feedbackEmail);
        }

        const passwordInput = $('#password-input');
        const feedbackPassword = $('#password-strength');

        let typingTimerEmail;
        let typingTimerPass;
        const delay = 1000; // ms

        // Función para evaluar fuerza de contraseña
        function evaluarFuerza(password) {
            let fuerza = 0;
            if (password.length >= 8) fuerza++;
            if (/[A-Z]/.test(password)) fuerza++;
            if (/[a-z]/.test(password)) fuerza++;
            if (/[0-9]/.test(password)) fuerza++;
            if (/[\W_]/.test(password)) fuerza++; // caracteres especiales

            if (fuerza <= 2) return {
                msg: 'Contraseña muy débil',
                color: 'red'
            };
            if (fuerza === 3 || fuerza === 4) return {
                msg: 'Contraseña aceptable',
                color: 'orange'
            };
            if (fuerza === 5) return {
                msg: 'Contraseña fuerte',
                color: 'green'
            };
        }

        // Validación contraseña en tiempo real
        passwordInput.on('keyup', function() {
            clearTimeout(typingTimerPass);
            const pass = $(this).val();

            if (pass.length === 0) {
                feedbackPassword.text('');
                // Al borrar la contraseña, si el email fue modificado, validar email
                emailInput.trigger('keyup');
                return;
            }

            typingTimerPass = setTimeout(() => {
                const resultado = evaluarFuerza(pass);
                feedbackPassword.text(resultado.msg).css('color', resultado.color);
            }, delay);
        });

        // Validación email solo si contraseña está vacía y email modificado
        emailInput.on('keyup', function() {
            clearTimeout(typingTimerEmail);
            const email = $(this).val();
            const pass = passwordInput.val();

            if (pass.length > 0) {
                // Si hay contraseña, no validar email
                feedbackEmail.text('');
                return;
            }

            // Solo verificar si el email fue modificado respecto al original
            if (email !== emailOriginal) {
                if (email.length > 5) {
                    feedbackEmail.text('Verificando correo... 🔄').css('color', 'gray');
                    typingTimerEmail = setTimeout(() => verificarEmail(email), delay);
                } else {
                    feedbackEmail.text('');
                }
            } else {
                // Si email es igual al original, limpiar feedback
                feedbackEmail.text('');
            }
        });

        function verificarEmail(email) {
            $.ajax({
                url: '{{ route('verificar.email') }}',
                type: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.valid) {
                        feedbackEmail.text(response.message).css('color', 'green');
                    } else {
                        feedbackEmail.text(response.message).css('color', 'red');
                    }
                },
                error: function() {
                    feedbackEmail.text('⚠️ Error al verificar el correo.').css('color', 'orange');
                }
            });
        }

        // Validación final antes de enviar el formulario
        $('form').on('submit', function(e) {
            const pass = passwordInput.val();
            const emailMsg = feedbackEmail.text();

            if (pass.length > 0) {
                const resultado = evaluarFuerza(pass);
                if (resultado.msg === 'Contraseña muy débil') {
                    e.preventDefault();
                    alert('La contraseña debe ser aceptable o fuerte para continuar.');
                    return false;
                }
            } else {
                // Si no hay contraseña, el email debe estar validado correctamente
                if (emailMsg.includes('no existe') || emailMsg.includes('Error') || emailMsg === '') {
                    e.preventDefault();
                    alert('Por favor, ingrese un correo válido antes de guardar.');
                    return false;
                }
            }
        });
    });
</script>