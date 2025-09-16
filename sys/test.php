<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Custom Yes/No Dialog</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal {
      background-color: white;
      padding: 20px 30px;
      border-radius: 8px;
      max-width: 400px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    .modal h2 {
      margin-bottom: 15px;
    }

    .modal-buttons {
      margin-top: 20px;
      display: flex;
      justify-content: space-around;
    }

    .modal-buttons button {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-yes {
      background-color: #3498db;
      color: white;
    }

    .btn-no {
      background-color: #e74c3c;
      color: white;
    }

    .btn-yes:hover {
      background-color: #2980b9;
    }

    .btn-no:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body>

  <button onclick="openDialog()">Open Yes/No Dialog</button>

  <div class="modal-overlay" id="dialog">
    <div class="modal">
      <h2>Are you sure?</h2>
      <p>This action cannot be undone.</p>
      <div class="modal-buttons">
        <button class="btn-yes" onclick="handleYes()">Yes</button>
        <button class="btn-no" onclick="closeDialog()">No</button>
      </div>
    </div>
  </div>

  <script>
    function openDialog() {
      document.getElementById("dialog").style.display = "flex";
    }

    function closeDialog() {
      document.getElementById("dialog").style.display = "none";
    }

    function handleYes() {
      closeDialog();
      console.log("You clicked Yes!");
      // Add your action here
    }
  </script>

</body>
</html>
