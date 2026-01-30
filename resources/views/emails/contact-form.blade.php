<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submission</title>
</head>
<body>
 
    <h3>You have received a new message from Brand Fotos website contact form.</h3>
    
    <p><strong>Full Name:</strong> {{ $contactData['full_name'] }}</p>
    <p><strong>Business Name:</strong> {{ $contactData['business_name'] ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $contactData['email'] }}</p>
    <p><strong>Phone/WhatsApp:</strong> {{ $contactData['phone'] }}</p>
    <p><strong>Subject:</strong> {{ $contactData['subject'] }}</p>
    
    <h3>Message:</h3>
    <p>{{ $contactData['message'] }}</p>
</body>
</html>