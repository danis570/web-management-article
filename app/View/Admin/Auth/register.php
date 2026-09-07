<?php if (isset($model['error'])) { ?>
    <div>
        <p>
            <?= $model['error'] ?>
        </p>
    </div>
<?php } ?>

<h2><?= $model['title'] ?></h2>

<form action="/register" method="post" enctype="multipart/form-data">
    <!-- Kolom Name -->
    <div>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= $_POST['name'] ?? '' ?>" required>
    </div>

    <!-- Kolom Position -->
    <div>
        <label for="position">Position</label>
        <input type="text" name="position" id="position" value="<?= $_POST['position'] ?? '' ?>" required>
    </div>

    <!-- Kolom Period -->
    <div>
        <label for="period">Period</label>
        <input type="text" name="period" id="period" value="<?= $_POST['period'] ?? '' ?>" required>
    </div>

    <!-- Kolom Role (Default 'user') -->
    <div>
        <label for="role">Role</label>
        <select name="role" id="role" required>
            <option value="user" selected>User</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <!-- Kolom Image (Opsional karena nilainya YES pada NULL) -->
    <div>
        <label for="img">Profile Image</label>
        <input type="file" name="img" id="img" accept="image/*">
    </div>

    <!-- Kolom Email -->
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= $_POST['email'] ?? '' ?>" required>
    </div>

    <!-- Kolom Password -->
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?= $_POST['password'] ?? '' ?>" required>
    </div>

    <!-- Tombol Submit -->
    <div style="margin-top: 15px;">
        <button type="submit">Register</button>
    </div>
</form>