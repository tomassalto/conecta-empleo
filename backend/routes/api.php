<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Models\Job;
use App\Models\Application;

Route::post('/register', function (Request $request) {
    $data = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'in:dev,company'
    ]);
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'role' => $data['role'] ?? 'dev'
    ]);
    return response()->json($user, 201);
});

Route::post('/login', function (Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);
    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 422);
    }
    // usa sesión + cookie; el middleware de Sanctum manejará el estado
    return response()->json(['message' => 'ok']);
});

Route::middleware('auth:sanctum')->get('/me', fn(Request $r) => $r->user());
Route::post('/logout', function (Request $request) {
    auth()->guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->json(['message' => 'bye']);
});

Route::get('/jobs', fn() => Job::with('user:id,name')->latest()->paginate(10));

Route::middleware('auth:sanctum')->post('/jobs', function (Request $r) {
    abort_unless($r->user()->role === 'company', 403);
    $data = $r->validate([
        'title' => 'required',
        'description' => 'required',
        'seniority' => 'nullable',
        'type' => 'nullable',
        'location' => 'nullable',
        'salary_min' => 'nullable|integer',
        'salary_max' => 'nullable|integer',
    ]);
    return Job::create($data + ['user_id' => $r->user()->id]);
});

Route::middleware('auth:sanctum')->post('/jobs/{job}/apply', function (Request $r, Job $job) {
    abort_unless($r->user()->role === 'dev', 403);
    $data = $r->validate(['cover_letter' => 'nullable|string']);
    return Application::create([
        'job_id' => $job->id,
        'user_id' => $r->user()->id,
        'cover_letter' => $data['cover_letter'] ?? null,
    ]);
});
