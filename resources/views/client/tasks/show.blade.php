@extends('layouts.admin')

@section('content')
    <h4 class="mb-5"><b>{{ $task->taskName }}</b>-baholash uchun xodimlar ro'yxati</h4>
    <table class="table table-bordered mt-5" id="myTable2">
        <thead>
            <tr>
                <th>#</th>
                <th>Ismi</th>
                <th>Berilgan loyiha</th>
                <th>Baxolash</th>
                <th>Lavozim</th>
                <th>Telfon raqam</th>
                <th>Umumiy baxosi</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($staffUsers as $index => $staffUser)
                @php
                    $roles = $staffUser->getRoleNames();
                @endphp

                @if ($roles->contains('Super Admin') || $roles->contains('Admin'))
                    @continue
                @endif
                <tr>
                    <th>{{ $i++ }}</th>
                    <td>{{ $staffUser->firstName }} {{ $staffUser->lastName }}</td>
                    <td> {{ $staffUser->project ? $staffUser->project->name : 'Loyiha biriktirilmagan' }} </td>
                    <td>
                        <form action="{{ route('tasks.assign', [$task->id, $staffUser->id]) }}" method="GET">
                            @csrf
                            <button class="btn btn-info" type="submit">Baho berish</button>
                        </form>
                    </td>
                    <td> {{ $staffUser->position }} </td>
                    <td> {{ $staffUser->phone }} </td>
                    <td>{{ isset($ratings[$staffUser->id]) ? $ratings[$staffUser->id] . ',0' : '0,0' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    <!-- DataTables va eksport qilish uchun kerakli js fayllarini qo'shamiz -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                ordering: true,  // Tizimlash (birinchi ustun bo'yicha)
                order: [[0, 'asc']], // Birinchi ustun (ID) bo'yicha tartibni o'rnatish
                paging: false, // Sahifalashni o'chirish
                lengthChange: false, // Sahifa uzunligini tanlashni olib tashlash
                language: {
                    search: "Qidiruv:",
                    info: "_TOTAL_ ta yozuvdan _START_ dan _END_ gacha ko'rsatilmoqda",
                    zeroRecords: "Hech qanday mos yozuv topilmadi",
                },
                dom: 'Bfrtip',  // Bu yerda 'B' tugmalarni ifodalash uchun ishlatiladi
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Nusxalash',
                        className: 'btn btn-info'  // Bootstrap klassi qo'shildi
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'btn btn-info'  // Bootstrap klassi qo'shildi
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn btn-info'  // Bootstrap klassi qo'shildi
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'btn btn-info'  // Bootstrap klassi qo'shildi
                    },
                    {
                        extend: 'print',
                        text: 'Chop etish',
                        className: 'btn btn-info'  // Bootstrap klassi qo'shildi
                    }
                ]
            });
        });
    </script>
@endpush
