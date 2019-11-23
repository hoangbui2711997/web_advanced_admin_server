<div id="guide-otp-modal" class="modal fade modal-guarotp" role="dialog">
    <div class="modal-dialog wrap-modal">

        <!-- Modal content-->
        <form id="otp-recovery-form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-left">
                        @lang('Stop using OTP')
                    </h4>
                </div>
                <div class="modal-body condition">
                    <div class="rule">
                        @lang('modal_otp.guide')
                    </div>
                    <div class="form-group">
                        <span>@lang('Recovery code')</span>
                        <input type="text" maxlength="16" class="form-control commom-otp" name="otp_recovery_code">
                        <span class="error-block hidden" id="error_otp_recovery_code">
                                <span class="error-message"></span>
                              </span>
                        <input type="hidden" name="email" value="{{old('email')}}">
                        <input type="hidden" name="password" value="{{old('password')}}">
                    </div>

                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-primary btn-block" @click="delGoogleAuth()">@lang('Stop using OTP')</button>
                    <p class="text-center">
                        <a href="#" data-toggle="modal" data-target="#agreement-otp-modal" class="underline">@lang('Lost your recovery code too?')</a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
