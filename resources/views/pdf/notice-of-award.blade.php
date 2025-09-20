<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Notice of Award</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12pt; line-height: 1.6; }
    .center { text-align: center; }
    .mt-4 { margin-top: 20px; }
    .signature { margin-top: 50px; font-weight: bold; }
  </style>
</head>
<body>

  <h2 class="center">NOTICE OF AWARD</h2>
  <h3 class="center">{{ $winner->supplier->first_name ?? '' }}</h3>
  <p class="center"><strong>Subject:</strong> Supplier of {{ $ppmp->project_title }}</p>

  <p>Dear Sir/Madam:</p>

  <p>We are pleased to inform you that your bid proposal has been accepted.</p>

  <p>
    Ref No.: {{ $ppmp->invitations->first()->reference_no }}<br>
    Project: {{ $ppmp->project_title }}<br>
    Amount: ₱{{ number_format($winner->bid_amount, 2) }}
  </p>

  <p>
    Please acknowledge receipt and acceptance of this award by signing
    and returning a copy of this Notice of Award.
  </p>

  <p>Again, congratulations and we look forward to working with you.</p>

  <p class="mt-4">Sincerely,</p>
  <p class="signature">Signature</p>

</body>
</html>
