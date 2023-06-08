<?php
session_start();

// Xử lý yêu cầu sửa task
if (isset($_POST['edit']) && isset($_POST['task']) && isset($_POST['task_index'])) {
    $taskIndex = $_POST['task_index'];
    $task = $_POST['task'];

    if (isset($_SESSION['tasks'][$taskIndex])) {
        $_SESSION['tasks'][$taskIndex] = $task;
    }
}

// Xử lý yêu cầu xóa task
if (isset($_GET['delete'])) {
    $deleteIndex = $_GET['delete'];
    if (isset($_SESSION['tasks'][$deleteIndex])) {
        unset($_SESSION['tasks'][$deleteIndex]);
        $_SESSION['tasks'] = array_values($_SESSION['tasks']); // Đánh lại chỉ mục mảng
    }
}

// Xử lý yêu cầu thêm task hoặc sửa task
if (isset($_POST['task'])) {
    $task = $_POST['task'];
    $editIndex = isset($_POST['editIndex']) ? $_POST['editIndex'] : null;

    // Kiểm tra nếu session chưa được khởi tạo, khởi tạo một mảng mới
    if (!isset($_SESSION['tasks'])) {
        $_SESSION['tasks'] = array();
    }

    if ($editIndex !== null && isset($_SESSION['tasks'][$editIndex])) {
        // Sửa task
        $_SESSION['tasks'][$editIndex] = $task;
    } else {
        // Thêm task mới vào mảng
        array_push($_SESSION['tasks'], $task);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('taskInput').focus();
        });

        function editTask(index, task) {
            document.getElementById('taskInput').value = task;
            document.getElementById('editIndex').value = index;
            document.getElementById('addButton').style.display = 'none';
            document.getElementById('saveButton').style.display = 'inline';
            document.getElementById('cancelButton').style.display = 'inline';
            document.getElementById('taskInput').focus();
        }

        function cancelEdit() {
            document.getElementById('taskInput').value = '';
            document.getElementById('editIndex').value = '';
            document.getElementById('addButton').style.display = 'inline';
            document.getElementById('saveButton').style.display = 'none';
            document.getElementById('cancelButton').style.display = 'none';
            document.getElementById('taskInput').focus();
        }
    </script>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Todo List</h1>
        <h6 class="text-center">Made by: Xuân Quân</h6>

        <form method="POST" action="index.php" class="mb-3">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <?php
                    $editTask = "";
                    $editIndex = null;

                    if (isset($_GET['edit'])) {
                        $editIndex = $_GET['edit'];
                        if (isset($_SESSION['tasks'][$editIndex])) {
                            $editTask = $_SESSION['tasks'][$editIndex];
                        }
                    }
                    ?>
                    <input id="taskInput" type="text" name="task" class="form-control" placeholder="Thêm công việc" required value="<?php echo $editTask; ?>">
                    <input id="editIndex" type="hidden" name="editIndex" value="<?php echo $editIndex; ?>">
                </div>
                <div class="col-md-2">
                    <button id="addButton" type="submit" class="btn btn-primary btn-block" name="add">Thêm</button>
                    <button id="saveButton" type="submit" class="btn btn-primary btn-block" name="edit" style="display: none;">Lưu</button>
                    <button id="cancelButton" type="button" class="btn btn-secondary btn-block" onclick="cancelEdit()" style="display: none;">Hủy</button>
                </div>
            </div>
        </form>

        <h2>Tasks:</h2>
        <ul class="list-group">
            <?php
            // Hiển thị danh sách tasks
            if (isset($_SESSION['tasks'])) {
                foreach ($_SESSION['tasks'] as $index => $task) {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                    echo $task;
                    echo '<div>';
                    echo '<button onclick="editTask(' . $index . ', \'' . $task . '\')" class="btn btn-primary btn-sm mr-2">Sửa</button>';
                    echo '<a href="index.php?delete=' . $index . '" class="btn btn-danger btn-sm">Xóa</a>';
                    echo '</div>';
                    echo '</li>';
                }
            }
            ?>
        </ul>
    </div>
</body>
</html>
