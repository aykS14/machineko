@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('email_message.Verify.Title') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('email_message.Verify.NewUrl') }}
                        </div>
                    @endif

                    {{ __('email_message.Verify.SendActionUrl') }}<br/>
                    @lang('email_message.Verify.NotEmail', ['url' => route('verification.resend')])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
