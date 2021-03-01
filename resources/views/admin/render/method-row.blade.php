<td>{{ $method->methodLabel }}</td>
<td>{{ $method->created_at->format('d.m.Y H:i:s') }}</td>
<td>{{ $method->updated_at->format('d.m.Y H:i:s') }}</td>
<td>{{ $method->last_sync_at ? $method->last_sync_at->format('d.m.Y H:i:s') : '-' }}</td>
<td>{!! $method->statusUI !!}</td>
<td>{{ $method->value }}</td>
<td><button class="reload-ui" data-method="{{ $method->method }}"></button></td>
