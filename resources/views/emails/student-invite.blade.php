<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daleel Mentor Connection Key</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#172033;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f7fb;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e6ecf5;box-shadow:0 18px 45px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="background:#111827;padding:28px 32px;">
                            <div style="font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#a5b4fc;margin-bottom:8px;">Daleel Mentor</div>
                            <h1 style="margin:0;color:#ffffff;font-size:26px;line-height:1.25;font-weight:800;">
                                {{ $isRegenerated ? 'Your new connection key is ready' : 'You are invited to connect' }}
                            </h1>
                            <p style="margin:10px 0 0;color:#cbd5e1;font-size:15px;line-height:1.6;">
                                {{ $isRegenerated ? 'Use the key below to reconnect your extension or desktop app.' : 'Use the key below to link your browser extension or desktop app.' }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;color:#334155;font-size:15px;line-height:1.7;">
                                Hello <strong>{{ $studentName }}</strong>,
                            </p>
                            <p style="margin:0 0 24px;color:#334155;font-size:15px;line-height:1.7;">
                                {{ $managerName }} {{ $isRegenerated ? 'generated a new Daleel Mentor connection key for you.' : 'invited you to connect your Daleel Mentor activity tracking.' }}
                            </p>

                            <div style="background:#eef2ff;border:1px solid #c7d2fe;border-radius:16px;padding:22px;text-align:center;margin-bottom:26px;">
                                <div style="font-size:12px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#4f46e5;margin-bottom:10px;">Connection Key</div>
                                <div style="font-family:'Courier New',monospace;font-size:28px;line-height:1.2;font-weight:800;color:#111827;letter-spacing:0.04em;word-break:break-word;">
                                    {{ $connectionCode }}
                                </div>
                            </div>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:28px;">
                                <tr>
                                    <td style="padding:0 0 12px;">
                                        <h2 style="margin:0;color:#111827;font-size:17px;font-weight:800;">Setup steps</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#475569;font-size:14px;line-height:1.7;">
                                        <div style="margin-bottom:8px;"><strong>1.</strong> Install the Chrome extension or desktop app.</div>
                                        <div style="margin-bottom:8px;"><strong>2.</strong> Open the app and choose <strong>Connect Account</strong>.</div>
                                        <div><strong>3.</strong> Paste the connection key above and start syncing.</div>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin-bottom:26px;">
                                <tr>
                                    <td style="padding:0 10px 10px 0;">
                                        <a href="{{ $chromeExtensionUrl }}" style="display:inline-block;background:#4f46e5;color:#ffffff;text-decoration:none;font-size:14px;font-weight:800;padding:12px 18px;border-radius:10px;">Chrome Extension</a>
                                    </td>
                                    <td style="padding:0 0 10px 0;">
                                        <a href="{{ $desktopAppUrl }}" style="display:inline-block;background:#0f172a;color:#ffffff;text-decoration:none;font-size:14px;font-weight:800;padding:12px 18px;border-radius:10px;">Desktop App</a>
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
