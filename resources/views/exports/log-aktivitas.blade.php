<table>
    <thead>
        <tr>
            <th>Tipe</th>
            <th>Nama Pengguna</th>
            <th>Lab</th>
            <th>Alat</th>
            <th>Tanggal</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Status</th>
            <th>Dibuat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->tipe }}</td>
                <td>{{ optional(\App\Models\User::find($log->user_id))->name }}</td>
                <td>{{ $log->lab_id ? optional(\App\Models\Lab::find($log->lab_id))->name : '-' }}</td>
                <td>{{ $log->tool_id ? optional(\App\Models\Tool::find($log->tool_id))->name : '-' }}</td>
                <td>{{ $log->tanggal }}</td>
                <td>{{ $log->waktu_mulai }}</td>
                <td>{{ $log->waktu_selesai }}</td>
                <td>{{ $log->status }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>