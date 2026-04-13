<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #7c3aed 0%, #db2777 100%); color: white; padding: 30px; border-radius: 12px 12px 0 0; text-align: center; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 12px 12px; }
        .box { background: white; padding: 25px; border-radius: 8px; margin: 20px 0; text-align: center; }
        .icon { font-size: 48px; margin-bottom: 15px; }
        .highlight { color: #7c3aed; font-weight: 600; }
        .button { display: inline-block; background: #7c3aed; color: white; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 14px; }
        .social { margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 48px; margin-bottom: 10px;">✅</div>
            <h1 style="margin: 0; font-size: 24px;">Message bien reçu !</h1>
        </div>

        <div class="content">
            <div class="box">
                <p style="font-size: 18px; margin-bottom: 15px;">
                    Bonjour <span class="highlight">{{ $nom }}</span>,
                </p>
                <p>
                    Nous confirmons avoir bien reçu votre message. Notre équipe l'examine et vous répondra dans les <strong>48 heures</strong> maximum.
                </p>
            </div>

            <div class="box" style="border-left: 4px solid #7c3aed;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">
                    📍 Nos horaires d'ouverture
                </div>
                <p style="margin: 5px 0;">Lundi - Vendredi : 9h00 - 18h00</p>
                <p style="margin: 5px 0;">Samedi : 9h00 - 12h00</p>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/') }}" class="button">
                    Retourner sur le site
                </a>
            </div>
        </div>

        <div class="footer">
            <p>Merci de votre confiance ! 😊</p>
            <p style="font-size: 12px;">
                Vinyle Hydrodécoupé · contact@vinyle-hydrodecoupe.fr
            </p>
        </div>
    </div>
</body>
</html>
