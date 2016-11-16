@extends('app_auth')

@section('content')

<div id="login-page" class="row Login__wrapper">
    <div class="col s12 z-depth-4 card-panel">

		<form class="login-form right-alert Login__form form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
			{{ csrf_field() }}
			
	    	<div class="row">
	            <div class="input-field col s12 center">
	                <img src="/images/favicon/apple-touch-icon-152x152.png" alt=""
	                     class="circle responsive-img valign profile-image-login">
	                <p class="center login-form-text">WebEfficiency</p>
	            </div>
	        </div>	

			<div class="row margin">
                <div class="input-field col s12">
                    <i class="mdi-social-person-outline prefix"></i>
                    <input id="email" type="email" name="email" class="Login__email_input" value="{{ old('email') }}">
                    <label for="email" class="">E-mail</label>
                </div>
            </div>

			<div class="form-group">
				<div class="col-md-6 col-md-offset-4">
					<button type="submit" class="btn btn-primary btn-block">
						Enviar Link Para Redefinir a Senha
					</button>
				</div>
			</div>

			<div class="row">
				@if (session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
				@endif

                @include('partials.form-error-messages')
            </div>

		</form>
			
	</div>
</div>
@endsection
