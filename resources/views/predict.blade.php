<form id="predictForm" method="POST" action="/predict">
    @csrf
    <input type="number" name="data" placeholder="Introduce un valor" required>
    <button type="submit">Predecir</button>
</form>


<div id="predictionResult"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#predictForm').on('submit', function (e) {
            e.preventDefault();
            let data = $('input[name="data"]').val();
            $.ajax({
                url: '/predict',
                method: 'POST',
                data: {data: data, _token: '{{ csrf_token() }}'},
                success: function (response) {
                    $('#predictionResult').html('Predicción: ' + response.prediction);
                }
            });
        });
    });
</script>
<script>
    $.ajax({
    url: '/predict',
    method: 'POST', // Aquí el método POST está especificado correctamente
    data: {data: data, _token: '{{ csrf_token() }}'},
    success: function (response) {
        $('#predictionResult').html('Predicción: ' + response.prediction);
    }
});

</script>
