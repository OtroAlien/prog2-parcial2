<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="logo">
    <a class="navbar-brand" href="index.php?sec=dashboard"><h1>NATURE</h1></a>
  </div>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="index.php?sec=admin_ordenes">
        Órdenes
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?sec=admin_usuarios">
        Usuarios
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?sec=admin_productos">
        Productos
        </a>
      </li>
    </ul>
  </div>
  <div class="admin-actions">
    <a href="../index.php" class="btn btn-outline-light btn-sm me-2">
      <i class="fas fa-home"></i> Ver Sitio
    </a>
    <form action="actions/auth_logout.php" method="POST" class="d-inline">
      <button type="submit" class="btn btn-danger btn-sm">
        <i class="fas fa-sign-out-alt"></i> Salir
      </button>
    </form>
  </div>
</nav>