<table>
    <thead>
      <tr>
        <th colspan="5">{{__('summary_report.heading_title')}}</th>
      </tr>
    </thead>
</table>
<table>
  <thead>
  <tr>
    <th>{{ $start_date }}</th>
    <th>{{ __('summary_report.To')  }}</th>
    <th>{{ $end_date  }}</th>
  </tr>
  </thead>
</table>
<table>
  <thead>
    <tr>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data1 as $item)
    <tr>
      <td>{{ $item['field_name'] }}</td>
      <td>{{ $item['value'] }}</td>
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
  @foreach ($data4 as $item)
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
    <th></th>
    <th></th>
    <th></th>
  </tr>
  </thead>
  <tbody>
  @foreach ($data5 as $item)
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
    <th></th>
    <th></th>
    <th></th>
  </tr>
  </thead>
  <tbody>
  @foreach ($data6 as $item)
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
    <th></th>
    <th></th>
    <th></th>
  </tr>
  </thead>
  <tbody>
  @foreach ($data7 as $item)
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
    <th></th>
    <th></th>
    <th></th>
  </tr>
  </thead>
  <tbody>
  @foreach ($data8 as $item)
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
    <th></th>
    <th></th>
    <th></th>
  </tr>
  </thead>
  <tbody>
  @foreach ($data9 as $item)
    <tr>
      @for($index = 0; $index < count($item); $index++)
        <td>{{ $item[$index] }}</td>
      @endfor
    </tr>
  @endforeach
  </tbody>
</table>
