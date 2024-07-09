<!DOCTYPE html>
<html>
<head>
    <title>Invoice Payment</title>
</head>
<body>
    <h1>Merci pour votre paiement.</h1>
    <p>Billed to: {{ $invoice->student->name }}</p>
    <p>Address: {{ $invoice->student->address }}</p>
    <p>Service: {{ $invoice->subject }}</p>
    <p>Price: {{ $invoice->amount }} DJF</p>
    <p>Amount Paid: {{ $payment->amount }} DJF</p>
    <p>Subtraction: {{ $payment->amount - $invoice->amount }} DJF</p>
</body>
</html>
