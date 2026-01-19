<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Step 1: basic validation
        $request->validateWithBag('updatePassword', [
            'current_password' => ['required'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        // Step 2: MANUAL md5 password check
        if ($user->password !== md5($request->current_password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'], 'updatePassword');
        }

        // Step 3: update password in md5
        $user->update([
            'password' => md5($request->password),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Password updated successfully.');
    }
}
