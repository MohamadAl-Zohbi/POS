<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>License Expired</title>

     <link href="../common/bootstrap.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #5a3fff, #6f9cff);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: "Inter", sans-serif;
      color: #fff;
      text-align: center;
      padding: 20px;
    }

    .box {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 40px 30px;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      animation: fadeIn 0.8s ease-out;
    }

    .box i {
      font-size: 55px;
      margin-bottom: 15px;
    }

    .btn-custom {
      background: #fff;
      color: #4a3bff;
      font-weight: 600;
      padding: 12px 18px;
      border-radius: 12px;
      font-size: 1rem;
      transition: 0.2s;
    }

    .btn-custom:hover {
      background: #e8e8ff;
      color: #3b2fff;
      transform: translateY(-2px);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

</head>
<body>
  <div class="box">
    <h2 class="fw-bold mb-3">Your License Has Expired</h2>
    <p class="mb-4">To continue using the POS system, please contact us for renewal or assistance.</p>

    <a href="mailto:support@example.com" class="btn btn-custom w-100 mb-3">
       Email Support
    </a>

    <a href="tel:+123456789" class="btn btn-light w-100" style="font-weight:600;">
      Call Us
    </a>

    <p class="mt-4 small" style="opacity:0.8;">We’re here to help you get back up and running.</p>
  </div>
</body>
</html>
