<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="session-status">
            <span class="material-icons">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">
                <span class="material-icons text-gray-500 mr-2" style="font-size: 18px;">email</span>
                Email Address
            </label>
            <input 
                id="email" 
                class="form-input @error('email') error @enderror" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="Enter your email address"
            />
            @error('email')
                <div class="error-message">
                    <span class="material-icons" style="font-size: 16px;">error</span>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">
                <span class="material-icons text-gray-500 mr-2" style="font-size: 18px;">lock</span>
                Password
            </label>
            <input 
                id="password" 
                class="form-input @error('password') error @enderror"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="Enter your password"
            />
            @error('password')
                <div class="error-message">
                    <span class="material-icons" style="font-size: 16px;">error</span>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="checkbox-group">
            <input 
                id="remember_me" 
                type="checkbox" 
                class="checkbox-input" 
                name="remember"
            />
            <label for="remember_me" class="checkbox-label">
                Remember me for 30 days
            </label>
        </div>

        <!-- Login Button -->
        <div class="auth-actions">
            <button type="submit" class="btn-primary">
                <span class="material-icons">login</span>
                Sign In
            </button>
        </div>

        <!-- Forgot Password -->
        @if (Route::has('password.request'))
            <div class="forgot-password">
                <a href="{{ route('password.request') }}">
                    <span class="material-icons" style="font-size: 16px; vertical-align: middle;">help</span>
                    Forgot your password?
                </a>
            </div>
        @endif

        <!-- Register Link -->
        <div class="auth-footer">
            <p class="text-gray-600 text-sm">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-medium">
                    Create one here
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
