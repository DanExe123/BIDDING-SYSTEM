<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Notice of Award</title>

  <style>
    @page {
      size: A4;
      margin: 20mm 20mm; /* top/bottom, left/right */
    }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 11pt;
      line-height: 1.3;
      margin: 0; /* use @page margins */
    }

    .center {
      text-align: center;
    }

    .header {
      text-align: center;
      margin-bottom: 10px;
    }

    .header img {
      width: 70px; /* smaller logo */
      height: auto;
      margin-bottom: 5px;
    }

    .title {
      font-size: 14pt;
      font-weight: bold;
      margin: 15px 0;
      text-align: center;
    }

    
    .signature-block {
      margin-top: 30px;
      font-weight: bold;
      page-break-inside: avoid;
    }

    .conforme-block {
      margin-top: 40px;
      page-break-inside: avoid;
    }

    p {
      margin: 5px 0;
    }
  </style>
</head>
<body>

  <div class="header">
    <img src="file://{{ public_path('icon/bagologo1.svg') }}" alt="Logo">
    <div>Republic of the Philippines</div>
    <div><strong>City of Bago</strong></div>
    <div>Bago City, Negros Occidental</div>
    <div>Tel # 4610-409</div>
    <div>www.bagocity.gov.ph</div>
    <div>Bacoffice@gmail.com</div>
  </div>

  <div class="title">NOTICE OF AWARD</div>

  <p class="center">
    Series of {{ date('Y') }} BAC-NOA-{{ $ppmp->invitations->first()->reference_no ?? 'XXX' }}
  </p>

  <p>
    The Manager<br>
    <strong>{{ $winner->supplier->first_name ?? '' }}</strong><br>
    {{ $winner->supplier->address ?? '' }}
  </p>
  <br>

  <p>Dear Sir/Madam:</p><br>

  <p>
    We are happy to notify you that your quote opened on 
    <strong>{{ \Carbon\Carbon::parse($ppmp->opening_date)->format('F d, Y') }}</strong>,
    the Lumpsum/Contract Price of equivalent (Procurement of Goods):
  </p>

  <div>
    <table style="width:100%; border-collapse: collapse;">
      <tr>
        <td style="width:70%; padding: 0 10px; border: 1px solid black; font-size: 11pt;">
            1. <strong>{{ $ppmp->project_title }}</strong>
        </td>
        <td style="width:35%; padding: 0 10px; border: 1px solid black; font-size: 11pt;">
            <strong>{{ $amountInWords }} pesos (₱{{ number_format($winner->bid_amount, 2) }})</strong>
        </td>
      </tr>
    </table>

  </div>

  <p>
    Kindly acknowledge receipt hereof and signify your concurrence by signing under
    the Conforme portion below and return the same to the Bids and Awards Committee Office.
  </p><br>

  <p>Very truly yours,</p>

  <p class="signature-block"> 
      <br>
      <span style="text-decoration: underline; display: block; margin-bottom: 8px;">_______________________________</span>
      <strong>[ Mayor's Name ]</strong><br>
      <strong style="text-decoration: underline;">City Mayor</strong>
  </p>


  <div class="conforme-block">
    Conforme:<br>
   
    Manager<br>
    {{ $winner->supplier->first_name ?? '' }}<br>
    Date: ______________________
  </div>

</body>
</html>
