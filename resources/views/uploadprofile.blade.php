  @extends('layout.settings')

  @section('content')
    <div class="container">
      <div class="row">
        <div class="col-md-6 offset-md-3" style="margin-top: 20vh;">
          <div class="card" style="background-color: #e1e5f8;">
            <div class="card-header" style="background-color: #cdd8f6;">Upload Profile Picture</div>

            <div class="card-body">
              <form method="POST" action="{{ route('upload.profile.picture') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <input type="file" name="profile_picture" class="form-control-file">
                </div>
                <div class="row">
                  <div class="col">
                    <button type="submit" class="btn btn-primary" style="background-color: #7f8de1; border-color: #7f8de1;">Upload</button>
                  </div>
                  <div class="col text-right">
                    <a href="{{ route('personal') }}" class="btn btn-secondary">Cancel</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection
