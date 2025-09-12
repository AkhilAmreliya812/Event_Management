<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration Confirmation</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #e9ecef;
            color: #2c3e50;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .header {
            background: linear-gradient(90deg, #3498db, #2ecc71);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 18px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .content p {
            line-height: 1.8;
            font-size: 16px;
            margin: 15px 0;
        }
        .event-details {
            background-color: #f1f8ff;
            padding: 25px;
            border-left: 4px solid #3498db;
            border-radius: 8px;
            margin: 20px 0;
        }
        .event-details h2 {
            font-size: 22px;
            color: #3498db;
            margin: 0 0 15px;
        }
        .event-details table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }
        .event-details table td {
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .event-details table td:first-child {
            font-weight: bold;
            color: #2c3e50;
            width: 40%;
        }
        .button {
            display: inline-block;
            padding: 14px 30px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
            color: #ffffff;
            text-decoration: none;
            border-radius: 50px;
            margin: 20px 0;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 20px;
                width: calc(100% - 40px);
            }
            .header h1 {
                font-size: 26px;
            }
            .header p {
                font-size: 16px;
            }
            .content {
                padding: 20px;
            }
            .event-details {
                padding: 15px;
            }
            .event-details table td {
                font-size: 14px;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>You're Registered for {{ $event_title }}!</h1>
            <p>We’re excited to have you join us!</p>
        </div>
        <div class="content">
            <p>Dear {{ $name }},</p>
            <p>Thank you for registering for <strong>{{ $event_title }}</strong>. Your spot is confirmed, and we can’t wait to see you there! Below are the key details of your registration:</p>
            <div class="event-details">
                <h2>Event Details</h2>
                <table>
                    <tr>
                        <td>Event Name</td>
                        <td>{{ $event_title }}</td>
                    </tr>
                    <tr>
                        <td>Start Date</td>
                        <td>{{ $start_date }}</td>
                    </tr>
                    <tr>
                        <td>End Date</td>
                        <td>{{ $end_date }}</td>
                    </tr>
                    <tr>
                        <td>Registration ID</td>
                        <td>{{ $registration_id }}</td>
                    </tr>
                </table>
            </div>
            <p>Please keep your Registration ID handy for check-in. Stay tuned for further updates, including event schedules and additional information.</p>
        </div>
        <div class="footer">
            <p>Best regards,</p>
            <p>Event Manager & Team</p>
        </div>
    </div>
</body>
</html>