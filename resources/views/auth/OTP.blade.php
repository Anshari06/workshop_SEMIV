<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<div class="container">
    <div class="row justify-content-center pt-5">
        <div class="col-md-6">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header bg-primary text-white py-4">
                    <h4 class="mb-0">Verifikasi OTP</h4>
                </div>

                <div class="card-body p-5">
                    <p class="text-muted mb-4">Masukkan kode OTP 6 digit yang telah dikirim ke email Anda.</p>

                    <form method="POST" action="{{ route('otp.verify') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="otp_code" class="form-label">Kode OTP</label>
                            <input id="otp_code" type="text"
                                class="form-control form-control-lg @error('otp_code') is-invalid @enderror text-center"
                                name="otp_code" placeholder="000000" maxlength="6" pattern="[0-9]{6}" required
                                autocomplete="off" autofocus>

                            @error('otp_code')
                                <div class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror

                            @error('otp')
                                <div class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Verifikasi OTP
                        </button>
                    </form>

                    <div class="text-center">
                        <form method="POST" action="{{ route('otp.resend') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                Kirim Ulang OTP
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>
