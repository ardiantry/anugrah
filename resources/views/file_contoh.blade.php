<?php 
print_r(array_keys($get_dt));
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=temptalte-{$type_table}.csv");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style type="text/css">
	table td
	{
		border: 1;
	}
</style>
<table border="1">
	<tr>
		@foreach(@$get_dt[0] as $label => $dat)
		<td>{{$label}}</td>
		@endforeach
	</tr>
	<tr>
		@foreach(@$get_dt[0] as $label => $dat)
		<td>{{$dat}}</td>
		@endforeach
	</tr>
</table>