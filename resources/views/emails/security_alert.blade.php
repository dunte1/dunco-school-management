<h2>Security Alert</h2>
<p>An event requiring attention occurred in the application.</p>
<ul>
    @foreach ($payload as $key => $value)
        <li><strong>{{ $key }}:</strong> {{ is_scalar($value) ? $value : json_encode($value) }}</li>
    @endforeach
</ul>


