@section('title', 'OOPS! - Could not Find it')
@section('content')
    <div layout="column" layout-align="center center" class="container-login">
        <h1>OOPS! - Could not Find it</h1>
        <img src="{{ asset('vendor/adtech-cms-material/default/images/404.png') }}"/>
        <div class="message">
            <md-input-container>
                <md-button class="md-raised md-primary" href="{{ route('backend.homepage') }}"
                           md-no-ink="md-no-ink">{{ __('adtech-core::buttons.back') }}</md-button>
            </md-input-container>
        </div>
    </div>
@stop