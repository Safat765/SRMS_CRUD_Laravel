@include('layout.header')
    <div class="container">
        <br>
        @if(Session::has('message'))
            <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                <strong>{{ Session::get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                <strong>{{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        @yield('main')
    </div>
@include('layout.footer')