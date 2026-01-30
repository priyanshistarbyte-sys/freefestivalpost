<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function sendMail(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to('customer.brandfotos@gmail.com')->send(new ContactFormMail($request->all()));
            return redirect()->route('contact-us')->with('success', 'Thank you! Your message has been sent successfully.');
        } catch (\Exception $e) {
            return redirect()->route('contact-us')->with('error', 'Email sending failed. Please try again.');
        }
    }
}