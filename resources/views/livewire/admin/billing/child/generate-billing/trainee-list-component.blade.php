<table class="table table-hover table-striped text-small border rounded-end">
    <thead>
        <tr>
            <th>Name</th>
            <th>Rank</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trainees as $trainee)
            <tr>
                <th>{{ $trainee->l_name }}, {{ $trainee->f_name }} {{ $trainee->m_name }} {{ $trainee->suffix }}</th>
                <th>{{ $trainee->rankacronym }}</th>
            </tr>
        @endforeach
    </tbody>
</table>
