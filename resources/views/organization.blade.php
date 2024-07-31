@extends('layout.layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"style="color: #3200af;">Organization</h1>
                </div>
                <div class="col-sm-6">
                    <form action="{{ route('showcreateorganization') }}" method="GET">
                        @csrf
                        <button type="submit" class="btn rounded float-right text-light" style="background-color: #7773d4;">
                            <i class="fas fa-plus"></i> 
                            Add Organization
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container">
            @if(isset($organizations))
                <div class="row">
                    <div class="card-group">
                        @foreach($organizations as $organization)
                            <div class="card ml-3 border border-secondary border-5 rounded-4" style="background-color: rgba(255, 0, 0, 0.5; border-radius: 15px;">
                                <div class="card-body d-flex ">
                                    <div class="col-6">
                                        <h3 class="card-title text-bold text-purple display-3">{{ $organization['organization_name'] }}</h3>
                                    </div>
                                    <div class="col-6 flex-grow-1 d-flex justify-content-end align-items-center">
                                        <i class="fa-solid fa-users fa-2xl" style="color: #3200af;"></i>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-end ">
                                        <a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" type="submit" class="btn btn-md px-5 rounded-pill text-light" style="background-color: #7773d4;">View</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif(isset($data['success']) && !$data['success'])
                <div class="alert alert-danger" role="alert">
                    Terjadi kesalahan saat mengambil data organisasi. Silakan coba lagi nanti.
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    Tidak ada data organisasi yang tersedia.
                </div>
            @endif
        </div>
    </section>
@endsection
