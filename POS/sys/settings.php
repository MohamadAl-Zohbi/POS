    <?php
    include_once '../common/connect.php';
    include_once './checkLogin.php';
    include_once './onlyAdmin.php';

    $getData = $db->prepare("SELECT * FROM data");
    $data;
    if ($getData->execute()) {
        $getData = $getData->fetchAll(PDO::FETCH_ASSOC);
        if (count($getData)) {
            $data = $getData[0];
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="../assets/pos-icon-2.jpg">
        <title>الإعدادات</title>
        <link href="../common/bootstrap.css" rel="stylesheet">

    </head>

    <body>
        <?php include_once 'navbar.php' ?>
        <div class="container mt-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-center">تعديل بيانات المؤسسة</h5>
                </div>

                <div class="card-body">
                    <form action="./save.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">لوغو المؤسسة</label>
                            <input type="file" name="logo" class="form-control" id="logo">
                        </div>
                        <div class="mb-3">
                            <img id="logoPreview" src="../uploads/<?php echo $data['logo'] ?>" alt="Logo preview" class="img-thumbnail" style="max-width: 200px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">إسم المؤسسة</label>
                            <input type="text" value="<?php echo $data['company_name'] ?>" class="form-control" id="company_name" name="company_name" placeholder="أدخل إسم المؤسسة">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">الموقع</label>
                            <input type="text" class="form-control" id="address" value="<?php echo $data['address'] ?>" name="address" placeholder="البلدة,الشارع...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">الهاتف</label>
                            <input type="text" class="form-control" value="<?php echo $data['tel'] ?>" name="tel" id="tel" placeholder="+961 ...">
                        </div>

                        <div class="mb-3">
                            <div style="width: 300px;">
                                <label class="form-check-label" for="auto">اغلاق اليوم تلقئي ام يدوي</label>
                                <select class="form-select" name="endOfDay" id="endDay"> 
                                    <option value="auto">Auto</option>
                                    <option value="manual">Manual</option>
                                </select>
                                <script>
                                    document.getElementById("endDay").value = "<?php echo $data['endOfDay'] ?>";
                                </script>

                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">حفظ التغييرات</button>

                    </form>
                </div>
            </div>
        </div>

    </body>
    <script src="../common/bootstrap.js"></script>
    <script>
        document.getElementById("logo").addEventListener("change", function() {
            const file = this.files[0];
            const preview = document.getElementById("logoPreview");

            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove("d-none");
            }
        });
    </script>

    </html>