<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library Management</title>
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

        .btn-3d-secondary {
            background: rgba(30, 41, 59, 0.8);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.1),
                        inset 0 -2px 4px rgba(0, 0, 0, 0.3),
                        0 8px 10px -2px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
        }

        .btn-3d-secondary:active {
            transform: translateY(2px);
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.5),
                        0 2px 4px -2px rgba(0, 0, 0, 0.3);
        }

        /* Custom Checkbox */
        .custom-checkbox {
            appearance: none;
            background-color: rgba(15, 23, 42, 0.6);
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 1.15em;
            height: 1.15em;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.25em;
            display: grid;
            place-content: center;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.4);
        }

        .custom-checkbox::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em #818cf8;
            background-color: #818cf8;
            transform-origin: center;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }

        .custom-checkbox:checked::before {
            transform: scale(1);
        }
    </style>
</head>
<body>

    <!-- Back Button -->
    <a href="{{ url('/') }}" class="absolute top-6 left-6 flex items-center justify-center text-slate-400 hover:text-white transition-colors group bg-slate-800/50 hover:bg-slate-700/50 rounded-full px-4 py-2 border border-slate-700/50 backdrop-blur-md z-50 shadow-lg">
        <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span class="text-sm font-medium">Back to Home</span>
    </a>
    <!-- Ambient Lighting -->
    <div class="ambient-light-1"></div>
    <div class="ambient-light-2"></div>

    <!-- Login Container -->
    <div class="glass-card w-full max-w-md rounded-[2.5rem] p-10 pt-16 flex flex-col items-center">
        
        <!-- 3D Avatar -->
        <div class="avatar-3d w-24 h-24 rounded-full flex flex-col items-center justify-end overflow-hidden mb-8 -mt-24 absolute top-12 border-4 border-slate-800">
            <!-- Head -->
            <div class="w-10 h-10 rounded-full bg-indigo-100 mb-1 shadow-[inset_0_-2px_4px_rgba(0,0,0,0.2)]"></div>
            <!-- Body -->
            <div class="w-20 h-10 rounded-t-full bg-indigo-200 shadow-[inset_0_-4px_6px_rgba(0,0,0,0.3)]"></div>
        </div>

        <!-- Headings -->
        <h2 class="text-2xl font-bold text-white tracking-wide mt-4">Welcome Back</h2>
        <p class="text-slate-400 text-sm mt-1 mb-6">Please sign in to your account</p>

        <!-- Demo Access Info -->
        <div class="w-full bg-indigo-950/40 border border-indigo-500/20 rounded-2xl p-4 mb-6 flex items-start space-x-3 text-left">
            <div class="text-indigo-400 mt-0.5 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-5-3a5 5 0 11-5 5 5 5 0 015-5zM4 13a1 1 0 011-1h1a1 1 0 011 1v3a1 1 0 001 1h3a1 1 0 001-1v-3a1 1 0 011-1h1a1 1 0 011 1v6a3 3 0 01-3 3H7a3 3 0 01-3-3v-6z"></path></svg>
            </div>
            <div>
                <span class="text-indigo-300 font-bold text-xs block mb-0.5">Demo Admin Credentials</span>
                <span class="text-[11px] text-slate-400 block font-medium">Email: <strong class="text-indigo-200 select-all">admin@example.com</strong></span>
                <span class="text-[11px] text-slate-400 block font-medium">Password: <strong class="text-indigo-200 select-all">password</strong></span>
            </div>
        </div>

        <!-- Form -->
        <form class="w-full space-y-5" action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <!-- Email/Username -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input type="email" name="email" value="{{ old('email', 'admin@example.com') }}" class="input-3d w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-slate-500 text-sm @error('email') border-red-500 @enderror" placeholder="Email Address" required>
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
                <input type="password" name="password" value="password" class="input-3d w-full pl-12 pr-12 py-3.5 rounded-xl text-white placeholder-slate-500 text-sm" placeholder="Password" required>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer">
                    <svg class="h-5 w-5 text-slate-400 hover:text-slate-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>

            <!-- Options -->
            <div class="flex items-center justify-between text-sm mt-2">
                <label class="flex items-center text-slate-300 cursor-pointer group">
                    <input type="checkbox" name="remember" class="custom-checkbox mr-2">
                    <span class="group-hover:text-white transition-colors">Remember me</span>
                </label>
                <a href="#" class="text-indigo-400 hover:text-indigo-300 transition-colors">Forgot Password?</a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn-3d-primary w-full text-white font-semibold rounded-xl py-3.5 mt-6 tracking-wide text-sm">
                Login
            </button>

            <!-- Divider -->
            <div class="flex items-center justify-center space-x-3 my-6">
                <div class="h-px w-full bg-slate-700"></div>
                <span class="text-slate-500 text-xs font-semibold">OR</span>
                <div class="h-px w-full bg-slate-700"></div>
            </div>

            <!-- Google Button -->
            <button type="button" class="btn-3d-secondary w-full text-slate-300 font-medium rounded-xl py-3 flex items-center justify-center hover:text-white group">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
            </button>

            <!-- Links -->
            <div class="text-center mt-6 text-sm text-slate-400">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-indigo-400 font-semibold hover:text-indigo-300 transition-colors">Register here</a>
            </div>
            
        </form>
    </div>

</body>
</html>
