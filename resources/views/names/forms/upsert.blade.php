<div class="container pt-3">
    <form action="" class="row g-3" method="POST">
        @csrf()
        <div class="col-md-6">
            <label for="inputEmail4" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                id="inputEmail4" name="email">
            @error('email')
                <div class="invalid_feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="inputPassword4" class="form-label @error('password') is-invalid @enderror">Password</label>
            <input type="password" class="form-control" id="inputPassword4" name="password"
                value="{{ old('password') }}">
            @error('password')
                <div class="invalid_feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label for="inputAddress" class="form-label @error('address') is-invalid @enderror">Address</label>
            <input type="text" class="form-control" id="inputAddress" name="address" value="{{ old('address') }}">
            @error('address')
                <div class="invalid_feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Conferma</button>
        </div>
    </form>
</div>
