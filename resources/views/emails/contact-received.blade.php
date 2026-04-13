<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #7c3aed 0%, #db2777 100%); color: white; padding: 30px; border-radius: 12px 12px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 12px 12px; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #7c3aed; }
        .label { font-weight: 600; color: #6b7280; font-size: 12px; text-transform: uppercase; }
        .value { margin-top: 4px; color: #1f2937; }
        .message-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e5e7eb; }
        .button { display: inline-block; background: #7c3aed; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">📧 Nouveau message reçu</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">{{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM à HH:mm') }}</p>
        </div>

        <div class="content">
            <div class="info-grid" style="display: grid; gap: 15px;">
                <div class="info-box">
                    <div class="label">Expéditeur</div>
                    <div class="value" style="font-size: 18px;">{{ $contactMessage->nom }}</div>
                </div>

                <div class="info-box">
                    <div class="label">Email</div>
                    <div class="value">{{ $contactMessage->email }}</div>
                </div>

                @if($contactMessage->telephone)
                <div class="info-box">
                    <div class="label">Téléphone</div>
                    <div class="value">{{ $contactMessage->telephone }}</div>
                </div>
                @endif

                <div class="info-box">
                    <div class="label">Sujet</div>
                    <div class="value">{{ $contactMessage->sujet }}</div>
                </div>
            </div>

            <div class="message-box">
                <div class="label" style="margin-bottom: 10px;">Message</div>
                <div style="white-space: pre-wrap; color: #374151;">{{ $contactMessage->message }}</div>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/admin/contacts/' . $contactMessage->id) }}" class="button">
                    Répondre via l'admin
                </a>
            </div>
        </div>

        <div class="footer">
            <p>Message #{{ $contactMessage->id }} reçu sur vinyle-hydrodecoupe.fr</p>
        </div>
    </div>
</body>
</html>
