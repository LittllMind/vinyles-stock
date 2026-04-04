<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            margin-top: 5px;
            padding: 10px;
            background: white;
            border-radius: 4px;
            border-left: 3px solid #667eea;
        }
        .message {
            background: white;
            padding: 15px;
            border-radius: 4px;
            border-left: 3px solid #764ba2;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>💿 Nouveau message de contact - Fundisc</h1>
    </div>
    <div class="content">
        <div class="field">
            <div class="label">Nom :</div>
            <div class="value">{{ $contactData['nom'] }}</div>
        </div>

        <div class="field">
            <div class="label">Email :</div>
            <div class="value">{{ $contactData['email'] }}</div>
        </div>

        <div class="field">
            <div class="label">Sujet :</div>
            <div class="value">{{ $contactData['sujet'] }}</div>
        </div>

        <div class="field">
            <div class="label">Message :</div>
            <div class="message">{{ $contactData['message'] }}</div>
        </div>
    </div>
</body>
</html>
