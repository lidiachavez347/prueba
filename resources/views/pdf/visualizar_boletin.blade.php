<!DOCTYPE html>
<html>
<head>
    <title>Boletín del Estudiante</title>
</head>
<body style="margin:0; padding:0;">
    <iframe src="{{ secure_url('boletin/estudiante/'.$id.'/pdf') }}"

            width="100%" height="100%"
            style="border:none; position:fixed; top:0; left:0; bottom:0; right:0;">
    </iframe>
</body>
</html>
