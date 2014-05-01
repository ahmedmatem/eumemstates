<nav class="clearfix">
    <ul>
        <li><a href="index.php">Countries</a></li>
        <?php
        if(isset($_SESSION['loggedIn'])){
            echo '<li><a href="manage.php">Manage</a></li>';
            if(isset($_SESSION['admin'])){
                echo '<li><a href="users.php">Users</a></li>';
            }
        }
        ?>
    </ul>
    <span>
        <?php
        if(isset($_SESSION['loggedIn'])) {
            if(isset($_SESSION['username'])) {
                echo '<span class="welcome-user">Welcome, <strong>' . $_SESSION['username'] . '</strong>!</span>';
            }
            echo '<a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>';
        }
        else {
            echo '<a href="login.php"><i class="fa fa-sign-in"></i> Login</a>';
        }
        ?>
    </span>
</nav>

