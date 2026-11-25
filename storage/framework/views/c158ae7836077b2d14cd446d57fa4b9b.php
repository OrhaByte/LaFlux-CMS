<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title ?? 'Sign In'); ?> | LaFlux CMS</title>
    <?php if(\WebConf::get('fav_icon') != ""): ?>
        <link rel="shortcut icon" href="<?php echo e(URL::to('/')); ?>/<?php echo e(\WebConf::get('fav_icon')); ?>"/>
    <?php endif; ?>

    <link href="<?php echo e(asset('packages/extensionsvalley/dashboard/css/font-awesome.min.css')); ?>" rel="stylesheet">
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
            position: relative;
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
        .brand-name {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }
        .brand-sub {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #bfdbfe;
            margin-top: 2px;
        }
        .brand-copy h2 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 12px 0;
            line-height: 1.3;
        }
        .brand-copy p {
            margin: 0 0 24px 0;
            font-size: 14px;
            line-height: 1.65;
            color: #cbd5e1;
        }
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 13.5px;
            color: #e2e8f0;
        }
        .feature-list li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .feature-list i {
            color: #34d399;
            font-size: 14px;
        }
        .brand-footer {
            margin-top: 32px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 12.5px;
            color: #cbd5e1;
        }
        .brand-footer strong {
            color: #ffffff;
        }
        .form-panel {
            padding: 48px 40px;
            color: #0f172a;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-panel h1 {
            margin: 0 0 6px 0;
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }
        .form-panel .subtitle {
            margin: 0 0 24px 0;
            font-size: 14px;
            color: #64748b;
        }
        .alert-box {
            padding: 11px 14px;
            border-radius: 8px;
            font-size: 13.5px;
            margin-bottom: 18px;
            font-weight: 500;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .field-group { margin-bottom: 18px; }
        .field-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap i.left-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }
        .input-wrap input {
            width: 100%;
            padding: 11px 38px 11px 38px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            font-size: 14px;
            color: #0f172a;
            background: #f8fafc;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
            outline: none;
        }
        .input-wrap input:focus {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14);
        }
        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: 0;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
        }
        .field-error {
            display: block;
            margin-top: 5px;
            font-size: 12.5px;
            color: #dc2626;
            font-weight: 500;
        }
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            font-size: 13.5px;
        }
        .remember-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #475569;
            cursor: pointer;
        }
        .forgot-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover { text-decoration: underline; }
        .btn-submit {
            width: 100%;
            padding: 12px 18px;
            border: 0;
            border-radius: 9px;
            background: linear-gradient(135deg, #2563eb 0%, #4338ca 100%);
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.1s;
        }
        .btn-submit:hover { opacity: 0.94; }
        .btn-submit:active { transform: scale(0.99); }
        .powered-mobile {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
<div class="auth-shell">
    <div class="brand-panel">
        <div>
            <div class="brand-badge">
                <div class="brand-icon">
                    <?php if(\WebConf::get('site_logo') != ""): ?>
                        <img src="<?php echo e(URL::to('/')); ?>/<?php echo e(\WebConf::get('site_logo')); ?>" alt="LaFlux" style="max-width:30px; max-height:30px;">
                    <?php else: ?>
                        <i class="fa fa-bolt"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="brand-name">LaFlux</div>
                    <div class="brand-sub">Content Management System</div>
                </div>
            </div>

            <div class="brand-copy">
                <h2>Secure and Stable built with Laravel.</h2>
                <p>Sign in to publish posts, build pages, organize categories &amp; tags, and control site navigation with role-based permissions.</p>
                <ul class="feature-list">
                    <li><i class="fa fa-check-circle"></i> Posts, Pages, Categories &amp; Tags</li>
                    <li><i class="fa fa-check-circle"></i> Dynamic Menus &amp; Scheduled Publishing</li>
                    <li><i class="fa fa-check-circle"></i> Built-in SEO Meta &amp; Access Control</li>
                </ul>
            </div>
        </div>

        <div class="brand-footer">
            <div><strong>LaFlux</strong> &mdash; Powered By <strong>OrhaByte Software Labs LLP</strong></div>
        </div>
    </div>

    <div class="form-panel">
        <h1>Welcome back</h1>
        <p class="subtitle">Sign in to your administrator account</p>

        <?php if(Session::has('message')): ?>
            <div class="alert-box alert-success"><?php echo e(Session::get('message')); ?></div>
        <?php endif; ?>
        <?php if(Session::has('error')): ?>
            <div class="alert-box alert-danger"><?php echo e(Session::get('error')); ?></div>
        <?php endif; ?>
        <?php if(Session::has('warning')): ?>
            <div class="alert-box alert-warning"><?php echo e(Session::get('warning')); ?></div>
        <?php endif; ?>

        <form action="<?php echo e(route('extensionsvalley.admin.auth')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="field-group">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="fa fa-envelope-o left-icon"></i>
                    <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="admin@example.com" required autofocus>
                </div>
                <?php if($errors->has('email')): ?>
                    <span class="field-error"><?php echo e($errors->first('email')); ?></span>
                <?php endif; ?>
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fa fa-lock left-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <button type="button" class="toggle-pw" onclick="var p=document.getElementById('password');p.type=p.type==='password'?'text':'password';" title="Show/Hide password">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                <?php if($errors->has('password')): ?>
                    <span class="field-error"><?php echo e($errors->first('password')); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <label class="remember-label">
                    <input name="remember" type="checkbox" value="1">
                    <span>Remember me</span>
                </label>
                <a class="forgot-link" href="<?php echo e(route('extensionsvalley.admin.reset')); ?>">Forgot password?</a>
            </div>

            <button class="btn-submit" type="submit">
                Sign In <i class="fa fa-arrow-right" style="margin-left:6px;"></i>
            </button>
        </form>

        <div class="powered-mobile">
            &copy; <?php echo e(date('Y')); ?> <strong>LaFlux</strong> &middot; Powered By <strong>OrhaByte Software Labs LLP</strong>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH /var/www/html/laravel-admin/packages/ExtensionsValley/Dashboard/src/Views/login/index.blade.php ENDPATH**/ ?>