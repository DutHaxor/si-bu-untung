<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class StaffController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required', // Can be email or username
            'password' => 'required'
        ]);

        // Check if identifier is an email or username
        if (filter_var($request->identifier, FILTER_VALIDATE_EMAIL)) {
            // If it's an email, find staff by email
            $staff = Staff::where('email', $request->identifier)->first();
        } else {
            // If it's not an email, find staff by username
            $staff = Staff::where('username', $request->identifier)->first();
        }

        // If staff not found
        if (!$staff) {
            return back()->withErrors(['identifier' => 'Username or Email not found.']);
        }

        // Check password
        if (!Hash::check($request->password, $staff->password)) {
            return back()->withErrors(['password' => 'Invalid credentials.']);
        }

        // Store staff data in session
        Session::put('staff_id', $staff->id_staff);
        Session::put('staff_role', $staff->role);
        Session::put('staff_username', $staff->username);

        // Redirect to dashboard
        return redirect()->route('dashboard');
    }

    // Logout
    public function logout()
    {
        // Clear all session data
        Session::flush();

        return redirect()->route('login.form')->with('success', 'You have logged out successfully.');
    }

    // Show create staff form
    public function create()
    {
        return view('staff.create');
    }

    // Store new staff
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:staff,username',
            'email' => 'required|email|max:255|unique:staff,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:owner,manager,karyawan',
        ]);

        // Generate ID: S + timestamp + random (simple unique string)
        // Or better: S001, S002... but finding the max is tricky with string type.
        // Let's use a simple unique ID strategy: "S" . time() . rand(10,99)
        // Or just UUID if the column supports it, but let's stick to a short string if possible.
        // Looking at other migrations, it seems to be string.
        
        $id_staff = 'S' . date('ymd') . rand(100, 999);

        Staff::create([
            'id_staff' => $id_staff,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('dashboard')->with('success', 'Staff berhasil ditambahkan!');
    }
}
