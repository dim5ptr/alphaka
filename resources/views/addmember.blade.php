@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Member ke Organisasi</h3>

    <!-- Form Pencarian Member -->
    <form action="{{ route('organization.searchMember', $id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="find">Cari Member:</label>
            <input type="text" name="find" class="form-control" placeholder="Masukkan nama atau ID member">
        </div>
        <button type="submit" class="btn btn-primary">Cari Member</button>
    </form>

    <!-- Tampilkan Hasil Pencarian -->
    @if(isset($members) && !empty($members))
        <h5>Hasil Pencarian:</h5>
        <form action="{{ route('organization.addMember', $id) }}" method="POST">
            @csrf
            <div class="list-group">
                @foreach($members['data'] as $member)
                    <div class="list-group-item">
                        <input type="checkbox" name="user_ids[]" value="{{ $member['id'] }}">
                        {{ $member['name'] }} ({{ $member['email'] }})
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-success mt-3">Tambah Member Terpilih</button>
        </form>
    @elseif(isset($members) && empty($members['data']))
        <p>Tidak ada member yang ditemukan.</p>
    @endif
</div>
@endsection
