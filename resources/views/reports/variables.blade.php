<h3 class="card-title center-align">{{ $company->name }}</h3>
<h4 class="card-title center-align">{{ $variable->description }}</h4>

<table class="striped">
    <thead>
        <tr>
            <th>Data</th>
            <th>Hora</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $reading)
            <tr>
                <td>{{ $reading['date'] }}</td>
                <td>{{ $reading['time'] }}</td>
                <td>{{ $reading[$variable->tag] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
