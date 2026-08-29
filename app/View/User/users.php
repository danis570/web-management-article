<?php

require __DIR__ . '/../GetDataFromSql/users.php';

$coon = getConnection();

$users = getUsers($coon);

?>

<html>
<header>
    <title>Users Data</title>
    <link rel="stylesheet" href="/Style/app.css">
    <style>
        table {
            margin: 20px 0;
            width: 100%;
        }

        th {
            text-transform: uppercase;
        }

        th,
        td {
            border: 2px solid black;
            padding: 8px;
        }

        .text-center {
            text-align: center;
        }

        ul {
            list-style-type: none;
        }
    </style>
</header>

<body>
    <nav>
        <ul>
            <li><a href="/users.php">Users</a></li>
            <li><a href="/articles.php">Articles</a></li>
        </ul>
    </nav>
    <h1>Data User</h1>
    <a href="/user-add.php" class="a-add">Add User</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Id user</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $index => $user) { ?>
                <tr>
                    <td class="text-center"><?= ++$index ?></td>
                    <td class="text-center"><?= $user['id'] ?></td>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td>
                        <ul>
                            <li><a href="">Edit</a></li>
                            <li><a href="">Delete</a></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>