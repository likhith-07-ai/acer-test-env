<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission - ACER Ratings</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f5f5f5;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <!-- Main Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    
                    <!-- Header with Logo -->
                    <tr>
                        <td style="background-color: #54b69c; padding: 30px 40px; border-radius: 8px 8px 0 0; text-align: center;">
                            <img src="{{ config('app.url') }}/assets/images/acer/logo.svg" alt="ACER Ratings" style="max-width: 180px; height: auto; display: block; margin: 0 auto;">
                        </td>
                    </tr>
                    
                    <!-- Content Section -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Title -->
                            <h1 style="margin: 0 0 10px 0; font-size: 24px; font-weight: bold; color: #202020; line-height: 1.3;">
                                New Contact Form Submission
                            </h1>
                            <p style="margin: 0 0 30px 0; font-size: 14px; color: #666666; line-height: 1.5;">
                                You have received a new message from the ACER Ratings contact form.
                            </p>
                            
                            <!-- Contact Details Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-left: 4px solid #54b69c; border-radius: 4px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <strong style="color: #202020; font-size: 14px; display: inline-block; min-width: 120px;">Name:</strong>
                                                    <span style="color: #202020; font-size: 14px;">{{ $name }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <strong style="color: #202020; font-size: 14px; display: inline-block; min-width: 120px;">Email:</strong>
                                                    <a href="mailto:{{ $email }}" style="color: #54b69c; font-size: 14px; text-decoration: none;">{{ $email }}</a>
                                                </td>
                                            </tr>
                                            @if($phone)
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <strong style="color: #202020; font-size: 14px; display: inline-block; min-width: 120px;">Phone:</strong>
                                                    <a href="tel:{{ $phone }}" style="color: #54b69c; font-size: 14px; text-decoration: none;">{{ $phone }}</a>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($organization)
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <strong style="color: #202020; font-size: 14px; display: inline-block; min-width: 120px;">Organization:</strong>
                                                    <span style="color: #202020; font-size: 14px;">{{ $organization }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <strong style="color: #202020; font-size: 14px; display: inline-block; min-width: 120px;">Subject:</strong>
                                                    <span style="color: #202020; font-size: 14px;">{{ ucfirst($subject) }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Message Section -->
                            <div style="margin-bottom: 30px;">
                                <h2 style="margin: 0 0 15px 0; font-size: 18px; font-weight: bold; color: #202020; line-height: 1.3;">
                                    Message
                                </h2>
                                <div style="background-color: #f9fafb; padding: 20px; border-radius: 4px; border: 1px solid #e5e7eb;">
                                    <p style="margin: 0; font-size: 14px; color: #202020; line-height: 1.6; white-space: pre-wrap;">{{ $contactMessage }}</p>
                                </div>
                            </div>
                            
                            <!-- Action Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-top: 20px;">
                                        <a href="mailto:{{ $email }}?subject=Re: {{ $subject }}" style="display: inline-block; padding: 12px 30px; background-color: #54b69c; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">
                                            Reply to {{ $name }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px 40px; border-top: 1px solid #e5e7eb; border-radius: 0 0 8px 8px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="text-align: center; padding-bottom: 20px;">
                                        <img src="{{ config('app.url') }}/assets/images/acer/footer-logo_68a83bac52644.svg" alt="ACER Ratings" style="max-width: 120px; height: auto; display: block; margin: 0 auto;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding-bottom: 15px;">
                                        <p style="margin: 0; font-size: 13px; color: #666666; line-height: 1.5;">
                                            <strong style="color: #202020;">ACER - Accurité Credit & Economic Ratings</strong><br>
                                            SEBI-registered credit rating agency
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding-bottom: 15px;">
                                        <p style="margin: 0; font-size: 12px; color: #666666; line-height: 1.5;">
                                            <strong>Head Office:</strong><br>
                                            Unit-808, 8th Floor, Tower -B, Signature Tower,<br>
                                            South City I, Sector 30, Gurugram, Haryana 122022<br>
                                            Phone: +91 124 460 7887
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding-bottom: 10px;">
                                        <p style="margin: 0; font-size: 12px; color: #666666; line-height: 1.5;">
                                            <strong>Branch Office (Mumbai):</strong><br>
                                            1513-14, C Wing, One BKC, Bandra Kurla Complex, Mumbai 400051<br>
                                            Phone: +91 22 6232 3333
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                                        <p style="margin: 0; font-size: 11px; color: #999999; line-height: 1.5;">
                                            This email was sent from the ACER Ratings contact form at <a href="{{ config('app.url') }}" style="color: #54b69c; text-decoration: none;">{{ config('app.url') }}</a><br>
                                            Please do not reply directly to this email. Use the reply button above to respond to the sender.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
