<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact');
    }

    public function store(Request $request)
    {
        // Handle contact form submission
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'phone' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
        ], [
            'email.regex' => 'Please enter a valid email address, such as name@example.com.',
        ]);

        try {
            // Get admin email from config or use default from address
            $adminEmail = config('mail.contact.admin_email') ?? config('mail.from.address');
            
            // Log mail configuration for debugging (remove in production)
            Log::info('Sending contact form email', [
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'username' => config('mail.mailers.smtp.username'),
                'from_address' => config('mail.from.address'),
                'to_address' => $adminEmail,
            ]);
            
            // Send email notification
            Mail::to($adminEmail)->send(new ContactFormNotification(
                $request->name,
                $request->email,
                $request->subject,
                $request->message,
                $request->phone,
                $request->organization
            ));

            Log::info('Contact form email sent successfully', ['to' => $adminEmail]);

            return redirect()->route('public.contact')
                ->with('success', __('messages.success.contact.sent'));
                
        } catch (\Exception $e) {
            // Log detailed error for debugging
            Log::error('Contact form email failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'mail_config' => [
                    'mailer' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'username' => config('mail.mailers.smtp.username'),
                ],
                'request_data' => $request->except(['_token']),
            ]);
            
            return redirect()->route('public.contact')
                ->with('error', 'Sorry, there was an error sending your message. Please try again later.');
        }
    }
}
