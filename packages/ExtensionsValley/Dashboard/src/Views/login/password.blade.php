<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Reset Password' }} | LaFlux CMS</title>
    @if(\WebConf::get('fav_icon') != "")
        <link rel="shortcut icon" href="{{ URL::to('/') }}/{{ \WebConf::get('fav_icon') }}"/>
    @endif

    <link href="{{ asset('packages/extensionsvalley/dashboard/css/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: radial-gradient(circle at 20% 20%, #1e293b 0%, #0f172a 55%, #020617 100%);
            color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-shell {
            width: 100%;
            max-width: 920px;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
        }
        @media (max-width: 760px) {
            .auth-shell { grid-template-columns: 1fr; max-width: 440px; }
            .brand-panel { padding: 32px 28px !important; }
        }
        .brand-panel {
            background: linear-gradient(145deg, #1d4ed8 0%, #312e81 55%, #0f172a 100%);
            color: #ffffff;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }
        .brand-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
        }
        .brand-name { font-size: 26px; font-weight: 800; letter-spacing: -0.02em; line-height: 1.1; }
        .brand-sub { font-size: 12px; text-transform: uppercase; letter-spacing: 0.12em; color: #bfdbfe; margin-top: 2px; }
        .brand-copy h2 { font-size: 24px; font-weight: 700; margin: 0 0 12px 0; line-height: 1.3; }
        .brand-copy p { margin: 0; font-size: 14px; line-height: 1.65; color: #cbd5e1; }
        .brand-footer {
            margin-top: 32px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 12.5px;
            color: #cbd5e1;
        }
        .brand-footer strong { color: #ffffff; }
        .form-panel {
            padding: 48px 40px;
            color: #0f172a;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-panel h1 { margin: 0 0 6px 0; font-size: 24px; font-weight: 700; color: #0f172a; }
        .form-panel .subtitle { margin: 0 0 24px 0; font-size: 14px; color: #64748b; }
        .alert-box { padding: 11px 14px; border-radius: 8px; font-size: 13.5px; margin-bottom: 18px; font-weight: 500; }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .field-group { margin-bottom: 20px; }
        .field-group label { display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px; }
        .input-wrap { position: relative; }
        .input-wrap i.left-icon {
            position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 14px;
        }
        .input-wrap input {
            width: 100%; padding: 11px 14px 11px 38px; border: 1px solid #cbd5e1; border-radius: 9px;
            font-size: 14px; color: #0f172a; background: #f8fafc; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }
        .input-wrap input:focus {
            background: #ffffff; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14);
        }
        .field-error { display: block; margin-top: 5px; font-size: 12.5px; color: #dc2626; font-weight: 500; }
        .btn-submit {
            width: 100%; padding: 12px 18px; border: 0; border-radius: 9px;
            background: linear-gradient(135deg, #2563eb 0%, #4338ca 100%);
            color: #ffffff; font-size: 15px; font-weight: 600; cursor: pointer;
            transition: opacity 0.15s, transform 0.1s;
        }
        .btn-submit:hover { opacity: 0.94; }
        .back-row { margin-top: 18px; text-align: center; font-size: 13.5px; }
        .back-row a { color: #2563eb; text-decoration: none; font-weight: 500; }
        .back-row a:hover { text-decoration: underline; }
        .powered-mobile { margin-top: 24px; text-align: center; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
<div class="auth-shell">
    <div class="brand-panel">
        <div>
            <div class="brand-badge">
                <div class="brand-icon">
                    @if(\WebConf::get('site_logo') != "")
                        <img src="{{ URL::to('/') }}/{{ \WebConf::get('site_logo') }}" alt="LaFlux" style="max-width:30px; max-height:30px;">
                    @else
                        <i class="fa fa-bolt"></i>
                    @endif
                </div>
                <div>
                    <div class="brand-name">LaFlux</div>
                    <div class="brand-sub">Content Management System</div>
                </div>
            </div>

            <div class="brand-copy">
                <h2>Forgot your password?</h2>
                <p>No worries — enter the administrator email address associated with your account and we'll send you a secure reset link.</p>
            </div>
        </div>

        <div class="brand-footer">
            <div><strong>LaFlux</strong> &mdash; Powered By <strong>OrhaByte Software Labs LLP</strong></div>
        </div>
    </div>

    <div class="form-panel">
        <h1>Reset Password</h1>
        <p class="subtitle">Enter your email address to receive a reset link</p>

        @if(Session::has('message'))
            <div class="alert-box alert-success">{{ Session::get('message') }}</div>
        @endif
        @if(Session::has('error'))
            <div class="alert-box alert-danger">{{ Session::get('error') }}</div>
        @endif
        @if(Session::has('warning'))
            <div class="alert-box alert-warning">{{ Session::get('warning') }}</div>
        @endif

        <form action="{{ route('extensionsvalley.admin.passwordreset') }}" method="POST">
            @csrf
            <div class="field-group">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="fa fa-envelope-o left-icon"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                </div>
                @if ($errors->has('email'))
                    <span class="field-error">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <button class="btn-submit" type="submit">
                Send Reset Link <i class="fa fa-paper-plane-o" style="margin-left:6px;"></i>
            </button>
        </form>

        <div class="back-row">
            <a href="{{ route('extensionsvalley.admin.login') }}">
                <i class="fa fa-arrow-left" style="margin-right:4px;"></i> Back to Sign In
            </a>
        </div>

        <div class="powered-mobile">
            &copy; {{ date('Y') }} <strong>LaFlux</strong> &middot; Powered By <strong>OrhaByte Software Labs LLP</strong>
        </div>
    </div>
</div>
</body>
</html>
