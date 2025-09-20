<!-- 

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
</nav> -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">

        <?php
        $url = parse_url($_SERVER['REQUEST_URI']);
        if(str_contains($url['path'], "dashboard")){
            echo "الصفحة الرئيسية";
        }else if(str_contains($url['path'], "products")){
            echo "الاصناف";
        }else if(str_contains($url['path'], "category")){
            echo "الفئة";
        }else if(str_contains($url['path'], "users")){
            echo "المستخدمين";
        }
        
        ?>


        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./dashboard.php">الصفحة الرئيسية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./products.php">الاصناف/products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./category.php">الفئة/category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">العملاء/customers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./users.php">المستخدمين/users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">الفواتير/bills</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        المزيد
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">الرصيد اليومي</a></li>
                        <li><a class="dropdown-item" href="#">تقرير الارباح اليومية</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">logout</a></li>
                    </ul>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li> -->
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" style="text-align: center;" type="number" readonly placeholder="Dollar"/>
                &nbsp;
                <button class="btn btn-outline-warning" type="submit">Logout</button>
            </form>
        </div>
    </div>
</nav>