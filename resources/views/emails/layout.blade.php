<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
    <style>
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F9FAFB;
            color: #374151;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(11, 19, 43, 0.08);
        }

        .header {
            padding: 30px;
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: #0B132B;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .logo span {
            color: #F1C40F;
        }

        .hero {
            background-color: #0B132B;
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
            background-image: linear-gradient(135deg, #0B132B 0%, #1e2a4a 100%);
        }

        .hero h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .content {
            padding: 40px 30px;
        }

        .footer {
            padding: 30px;
            background-color: #F3F4F6;
            text-align: center;
            font-size: 13px;
            color: #6B7280;
        }

        .cta-button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #F1C40F;
            color: #0B132B;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            margin-top: 20px;
            transition: transform 0.2s ease;
        }

        .footer-links {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
        }

        .footer-links a {
            color: #0B132B;
            text-decoration: none;
            font-weight: 600;
            margin: 0 10px;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: rgba(241, 196, 15, 0.2);
            color: #F1C40F;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <a href="{{ config('app.url') }}" class="logo">
                Resev<span>it</span>
            </a>
        </div>

        @if(isset($title))
            <div class="hero">
                <div class="badge">{{ $badge ?? 'NOTIFICATION' }}</div>
                <h1>{{ $title }}</h1>
            </div>
        @endif

        <div class="content">
            {!! clean($body ?? $slot ?? '') !!}

            @if(isset($actionUrl))
                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ $actionUrl }}" class="cta-button">
                        {{ $actionText ?? 'Click Here' }}
                    </a>
                </div>
            @endif

            @if(isset($subcopy))
                <div
                    style="margin-top: 30px; border-top: 1px solid #E5E7EB; padding-top: 20px; font-size: 12px; color: #6B7280;">
                    {!! clean($subcopy) !!}
                </div>
            @endif
        </div>

        <div class="footer">
            <p>You received this email because you are part of the {{ config('app.name') }} platform.</p>
            <div class="footer-links">
                <a href="{{ config('app.url') }}/privacy">Privacy Policy</a>
                <a href="{{ config('app.url') }}/terms">Terms of Service</a>
            </div>
            <p style="margin-top: 20px;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>