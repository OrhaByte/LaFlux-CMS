<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New Submission: {{ $form->name }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background:#f8fafc; padding:24px; color:#0f172a;">
    <div style="max-width:620px; margin:0 auto; background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden;">
        <div style="background:#1e293b; color:#ffffff; padding:18px 24px;">
            <h2 style="margin:0; font-size:18px;">New Form Submission: {{ $form->name }}</h2>
        </div>
        <div style="padding:22px 24px;">
            <p style="margin-top:0; color:#475569; font-size:14px;">
                A visitor submitted the <strong>{{ $form->name }}</strong> form on {{ \WebConf::get('site_name') ?: config('app.name') }}.
            </p>
            <table style="width:100%; border-collapse:collapse; margin-top:14px;">
                @foreach($payload as $fieldKey => $fieldVal)
                    <tr>
                        <td style="padding:9px 12px; border:1px solid #e2e8f0; background:#f8fafc; font-weight:600; width:35%; font-size:13.5px;">
                            {{ ucwords(str_replace('_', ' ', $fieldKey)) }}
                        </td>
                        <td style="padding:9px 12px; border:1px solid #e2e8f0; font-size:13.5px;">
                            {{ is_array($fieldVal) ? implode(', ', $fieldVal) : $fieldVal }}
                        </td>
                    </tr>
                @endforeach
            </table>
            <p style="margin:18px 0 0 0; font-size:12px; color:#64748b;">
                Submitted at {{ now()->format('M j, Y H:i') }} &middot; IP: {{ $ipAddress ?? 'N/A' }}
            </p>
        </div>
    </div>
</body>
</html>

