<?php
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');
?>

<table border="1">
	<thead>
		<th>Matkul</th>
		<th>Judul</th>
		<th>Isi</th>
		<th>Tanggal</th>
		<th>Waktu</th>
		<th>Dosen</th>
	</thead>
	@foreach($pengumuman as $peng)
		<tr>
			<td>{{ $peng->matkul }}</td>
			<td>{{ $peng->judul_pengumuman }}</td>
			<td>{{ $peng->isi_pengumuman }}</td>
			<td>{{ date('M, d Y', strtotime($peng->tgl_pengumuman)) }}</td>
			<td>{{ date('h:i A', strtotime($peng->tgl_pengumuman)) }}</td>
			<td>{{ $peng->dosen }}</td>
		</tr>
@endforeach
</table>