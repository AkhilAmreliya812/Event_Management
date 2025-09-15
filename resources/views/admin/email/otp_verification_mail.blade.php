<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification Code</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f3f7fa; color: #1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f3f7fa; padding: 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(90deg, #4c51bf, #7c3aed); padding: 25px; text-align: center; border-radius: 12px 12px 0 0;">
                            <h1 style="color: #ffffff; font-size: 24px; font-weight: 700; margin: 0;">Account Verification Code</h1>
                            <p style="color: #ffffff; font-size: 15px; margin: 5px 0 0;">Secure your access now!</p>
                        </td>
                    </tr>
                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 30px 25px; text-align: left;">
                            <p style="font-size: 17px; color: #1f2937; margin: 0 0 15px; font-weight: 500;">Hello {{ $name }},</p>
                            <p style="font-size: 15px; color: #4b5563; line-height: 1.6; margin: 0 0 20px;">
                                To access <b>Event Manager's</b> pages and resources, please use the code below for account verification. This code is valid for the next 10 minutes only.
                            </p>
                            <div style="background-color: #eef2ff; border-left: 4px solid #4c51bf; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                                <p style="font-size: 14px; color: #4b5563; margin: 0 0 10px; font-weight: 600;">Verification Code</p>
                                <h2 style="font-size: 28px; color: #1f2937; margin: 0; font-weight: 700;">{{ $otp }}</h2>
                            </div>
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin: 0;">
                                If you didn’t request a code, you can ignore this email or <a href="mailto:support@eventmanager.com" style="color: #4c51bf; text-decoration: none;">contact our support team</a>.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #2d3748; padding: 15px; text-align: center; border-radius: 0 0 12px 12px;">
                            <p style="color: #ffffff; font-size: 14px; margin: 0 0 5px; font-weight: 500;">Best regards,</p>
                            <p style="color: #ffffff; font-size: 14px; margin: 0;">Event Manager Team</p>
                            <p style="color: #a0aec0; font-size: 12px; margin: 10px 0 0;">
                                <a href="https://eventmanager.com" style="color: #a0aec0; text-decoration: none;">Visit Us</a> | 
                                <a href="mailto:support@eventmanager.com" style="color: #a0aec0; text-decoration: none;">Support</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
