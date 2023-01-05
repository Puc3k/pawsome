<section class="container-fluid form-container">
    <div class="row form-container">
        <div class="col-12 col-md-6 offset-md-3 d-flex justify-content-center align-items-center">
            <div class="card mt-3">
                <h5 class="card-header">{{ $user->name }}</h5>
                <div class="card-body">
                    @if($user->avatar)
                    <!--        <img src="{{ asset('assets/' . $user->avatar) }}" class="rounded mx-auto d-block" width="360" height="360">-->
                    @else
                    <img src="../images/dog-avatar.jpg" class="rounded rounded-circle mx-auto d-block p-4" width="360"
                         height="360" alt="User avatar placeholder">
                    @endif

                    <ul>
                        <li>Nazwa: {{ $user->name }}</li>
                        <li>Email: {{ $user->email }}</li>
                        <li>Telefon: {{ $user->phone }}</li>
                    </ul>

                    <a href="{{ route('me.edit') }}" class="btn btn-warning">Edytuj dane</a>
                </div>
            </div>
        </div>
    </div>
</section>