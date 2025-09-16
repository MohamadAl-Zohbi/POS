<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f8fa;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .card {
        background: #fff;
        width: 100%;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        overflow: hidden;
    }

    .card-header {
        background-color: #007bff;
        color: #fff;
        padding: 16px;
        font-size: 18px;
        font-weight: bold;
    }

    .card-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        color: #333;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 8px 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
    }

    .card-footer {
        padding: 12px 20px;
        background: #f1f1f1;
        text-align: right;
    }

    .btn {
        padding: 8px 16px;
        font-size: 14px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-left: 5px;
        transition: background-color 0.3s;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-default {
        background-color: #e0e0e0;
        color: #333;
    }

    .btn-default:hover {
        background-color: #c7c7c7;
    }
</style>
<div class="card">
    <div class="card-header">Category Form</div>
    <form action="" id="manage-category">
        <div class="card-body">
            <input type="hidden" name="id">
            <div class="form-group">
                <label class="control-label">Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label class="control-label">Description</label>
                <textarea name="description" cols="30" rows="4" class="form-control"></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary">Save</button>
            <button class="btn btn-default" type="button" onclick="document.getElementById('manage-category').reset()">Cancel</button>
        </div>
    </form>
</div>