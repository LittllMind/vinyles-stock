<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmez votre inscription</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="color: #2c3e50;">Bienvenue chez Vinyles Stock ! 🎵</h1>
    
    <p>Merci de votre intérêt pour notre newsletter.</p>
    
    <p>Pour finaliser votre inscription, veuillez cliquer sur le lien ci-dessous :</p>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ route('newsletter.confirm', $subscriber->confirmation_token) }}" 
           style="background-color: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Confirmer mon inscription
        </a>
    </p>
    
    <p style="font-size: 14px; color: #666;">
        Ou copiez ce lien dans votre navigateur :<br>
        <code style="word-break: break-all;">{{ route('newsletter.confirm', $subscriber->confirmation_token) }}</code>
    </p>
    
    <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
    
    <p style="font-size: 12px; color: #999;">
        Vous recevez cet email car vous avez demandé à être inscrit à notre newsletter.<br>
        Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email.
    </p>
</body>
</html>
