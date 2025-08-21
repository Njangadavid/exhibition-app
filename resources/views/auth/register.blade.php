<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">
                <span class="material-icons text-gray-500 mr-2" style="font-size: 18px;">person</span>
                Full Name
            </label>
            <input 
                id="name" 
                class="form-input @error('name') error @enderror" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="Enter your full name"
            />
            @error('name')
                <div class="error-message">
                    <span class="material-icons" style="font-size: 16px;">error</span>
                    {{ $message }}
                </div>
            @enderror
        </div>

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
                autocomplete="new-password"
                placeholder="Create a strong password"
            />
            @error('password')
                <div class="error-message">
                    <span class="material-icons" style="font-size: 16px;">error</span>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">
                <span class="material-icons text-gray-500 mr-2" style="font-size: 18px;">lock_reset</span>
                Confirm Password
            </label>
            <input 
                id="password_confirmation" 
                class="form-input"
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="Confirm your password"
            />
        </div>

        <!-- Register Button -->
        <div class="auth-actions">
            <button type="submit" class="btn-primary">
                <span class="material-icons">person_add</span>
                Create Account
            </button>
        </div>

        <!-- Login Link -->
        <div class="auth-footer">
            <p class="text-gray-600 text-sm">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-medium">
                    Sign in here
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
