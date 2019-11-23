<table>
    <thead>
      <tr>
        <th colspan="5">{{__('fee_report.heading_title')}}</th>
      </tr>
    </thead>
</table>
<table>
  <thead>
  <tr>
    <th>{{ $start_date }}</th>
    <th>{{ __('fee_report.To')  }}</th>
    <th>{{ $end_date  }}</th>
  </tr>
  </thead>
</table>
<table>
    <thead>
    <tr>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data1 as $item)
        <tr>
            @for($index = 0; $index < count($item); $index++)
                <td>{{ $item[$index] }}</td>
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>
<table>
    <thead>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data2 as $item)
        <tr>
            @for($index = 0; $index < count($item); $index++)
                <td>{{ $item[$index] }}</td>
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>
<table>
    <thead>
    <tr>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data3 as $item)
        <tr>
            @for($index = 0; $index < count($item); $index++)
                <td>{{ $item[$index] }}</td>
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>