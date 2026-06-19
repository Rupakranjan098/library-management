<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Library Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Ambient background lighting */
        .ambient-light-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(0,0,0,0) 70%);
            z-index: 0;
        }
        
        .ambient-light-2 {
            position: absolute;
            bottom: -20%;
            right: -10%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, rgba(0,0,0,0) 70%);
            z-index: 0;
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 10;
        }

        /* 3D Profile Avatar */
        .avatar-3d {
            background: linear-gradient(145deg, #6366f1, #4f46e5);
            box-shadow: inset 0 4px 6px rgba(255, 255, 255, 0.3),
                        inset 0 -6px 8px rgba(0, 0, 0, 0.4),
                        0 15px 25px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255,255,255,0.1);
        }

        /* 3D Input Fields */
        .input-3d {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.4),
                        0 1px 0 rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .input-3d:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.4),
                        0 0 15px rgba(99, 102, 241, 0.3);
        }

        /* 3D Buttons */
        .btn-3d-primary {
            background: linear-gradient(180deg, #6366f1 0%, #4338ca 100%);
            box-shadow: inset 0 2px 2px rgba(255, 255, 255, 0.3),
                        inset 0 -2px 4px rgba(0, 0, 0, 0.3),
                        0 10px 15px -3px rgba(0, 0, 0, 0.4);
            border: 1px solid #3730a3;
            transition: all 0.2s ease;
        }

        .btn-3d-primary:active {
            transform: translateY(2px);
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.5),
                        0 4px 6px -2px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>

    <!-- Ambient Lighting -->
    <div class="ambient-light-1"></div>
    <div class="ambient-light-2"></div>

    <!-- Register Container -->
    <div class="glass-card w-full max-w-md rounded-[2.5rem] p-10 pt-16 flex flex-col items-center mt-12 mb-12">
        
        <!-- 3D Avatar (Plus icon for register) -->
        <div class="avatar-3d w-24 h-24 rounded-full flex items-center justify-center overflow-hidden mb-8 -mt-24 absolute top-12 border-4 border-slate-800">
            <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
        </div>

        <!-- Headings -->
        <h2 class="text-2xl font-bold text-white tracking-wide mt-4">Create Account</h2>
        <p class="text-slate-400 text-sm mt-1 mb-8">Join the library management system</p>

        <!-- Form -->
        <form class="w-full space-y-4" action="{{ route('register.post') }}" method="POST">
            @csrf
            
            <!-- Full Name -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input type="text" name="name" value="{{ old('name') }}" class="input-3d w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-slate-500 text-sm @error('name') border-red-500 @enderror" placeholder="Full Name" required>
            </div>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- Email -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" class="input-3d w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-slate-500 text-sm @error('email') border-red-500 @enderror" placeholder="Email Address" required>
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- Password -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input type="password" name="password" class="input-3d w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-slate-500 text-sm @error('password') border-red-500 @enderror" placeholder="Password" required>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- Confirm Password -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <input type="password" name="password_confirmation" class="input-3d w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-slate-500 text-sm" placeholder="Confirm Password" required>
            </div>

            <!-- Register Button -->
            <button type="submit" class="btn-3d-primary w-full text-white font-semibold rounded-xl py-3.5 mt-4 tracking-wide text-sm">
                Create Account
            </button>

            <!-- Links -->
            <div class="text-center mt-6 text-sm text-slate-400">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-indigo-400 font-semibold hover:text-indigo-300 transition-colors">Login here</a>
            </div>
            
        </form>
    </div>

</body>
</html>
