

<style>
    :root {
        --main_color: #007bff;
        --secondary_color: #5fa6f1;
        --hover_bg: rgba(0, 123, 255, 0.1);
        --text_color: #333;
    }

    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f8fa;
    }

    nav {
        width: 100%;
        background-color: #fff;
        border-right: 2px solid var(--main_color);
        min-height: 100vh;
        padding: 10px 5px;
        box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }

    .nav_row, details summary {
        display: flex;
        align-items: center;
        padding: 10px;
        margin: 5px 0;
        border-radius: 6px;
        cursor: pointer;
        color: var(--text_color);
        text-decoration: none;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .nav_row:hover, details[open] summary {
        background-color: var(--hover_bg);
        color: var(--main_color);
    }

    .inline {
        margin-left: 10px;
    }

    .link {
        text-decoration: none;
        color: inherit;
        width: 100%;
        display: flex;
        align-items: center;
    }

    details {
        margin: 5px 0;
    }

    details summary::-webkit-details-marker {
        display: none;
    }

    details summary::after {
        content: "▾";
        margin-right: auto;
        font-size: 12px;
        color: var(--secondary_color);
        transition: transform 0.2s;
    }

    details[open] summary::after {
        transform: rotate(180deg);
    }

    details div.nav_row {
        margin-left: 15px;
        padding-left: 5px;
    }

    img {
        filter: brightness(0) saturate(100%) invert(37%) sepia(90%) saturate(2041%) hue-rotate(194deg) brightness(96%) contrast(97%);
    }
</style>


<nav>
    <div class="nav_row">
        <a class="link" href="">
            <span class="inline">
                <img width="20" src="../assets/dashboard.png" alt="">
            </span>
            الصفحة الرئيسية
        </a>
    </div>
    <div class="nav_row">
        <a class="link" href="products.php">
            <span class="inline">
                <img width="20" src="../assets/dashboard.png" alt="">
            </span>
            الاصناف / products
        </a>
    </div>
    <div class="nav_row">
        <a class="link" href="">
            <span class="inline">
                <img width="20" src="../assets/dashboard.png" alt="">
            </span>
            الفئة / category
        </a>
    </div>
    <div class="nav_row">
        <a class="link" href="">
            <span class="inline">
                <img width="20" src="../assets/dashboard.png" alt="">
            </span>
            العملاء / customers
        </a>
    </div>
    <div class="nav_row">
        <a class="link" href="">
            <span class="inline">
                <img width="20" src="../assets/dashboard.png" alt="">
            </span>
            المستخدمين / users
        </a>
    </div>
    <div class="nav_row">
        <a class="link" href="">
            <span class="inline">
                <img width="20" src="../assets/dashboard.png" alt="">
            </span>
            الفواتير / bills
        </a>
    </div>

    <details>
        <summary class="nav_row">التقارير / reports</summary>
        <div class="nav_row">
            <a class="link" href="" title="daily report">
                <span class="inline">
                    <img width="20" src="../assets/reports.png" alt="">
                </span>
                تقرير بالارباح اليومية
            </a>
        </div>
        <div class="nav_row">
            <a class="link" href="" title="sales report">
                <span class="inline">
                    <img width="20" src="../assets/reports.png" alt="">
                </span>
                تقرير المبيعات
            </a>
        </div>
    </details>
    <div class="nav_row">
        <a class="link" href="pos.php" style="color: red;" target="_blank">
            <span class="inline">
                <img width="20" src="../assets/pos.png" alt="">
            </span>
            POS
        </a>
    </div>
</nav>
