<nav class="navbar navbar-expand-sm bg-primary navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">LTC Tyres</a>
    <ul class="navbar-nav me-auto">
      <li class="nav-item">
        <a class="nav-link" href="stock.php">Tyre Search</a>
      </li>
    <?php if($_SESSION['usertype']=="T") { ?>
      <li class="nav-item">
        <a class="nav-link" href="basket.php">Basket</a>
      </li>
    <?php }
        if($_SESSION['usertype']=="A") { ?>
      <li class="nav-item">
        <a class="nav-link" href="orders.php">Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="rim.php">Rim/Markup data</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="users.php">Users</a>
      </li>
    <?php } ?>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>