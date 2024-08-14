<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">
        <strong>ESTILOS 360</strong>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="index.php" class="nav-link active">Catálogo</a>
          </li>
          <li class="nav-item">
          </li>
        </ul>
        

        <div class="d-flex align-items-center ml-auto">

        <form action="index.php" method="get" autocomplete=off>
          <div class="input-group pe-3">
          <input type="text" name="q" id="q" class="form-control form-control-sm" placeholder="Buscar..." aria-describedby="icon-buscar">
          <button type="submit" id="icon-buscar" class="btn btn-outline-info">
            <i class="fas fa-search"></i>
          </button>
          </div>
        </form>

          <a href="checkout.php" class="btn btn-primary my-2 my-lg-0 mr-2"><i class="bi bi-cart"></i></i>Carrito <span id="num_cart" class="badge bg-secondary">
          <?php echo isset($_SESSION['carrito']['producto']) ? count($_SESSION['carrito']['producto']) : 0; ?></span></a>
          
          <?php if (isset($_SESSION['user_name'])): ?>
            <div class="dropdown">
              <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="btn_session" data-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person"></i> <?php echo $_SESSION['user_name']; ?>
              </button>
              <ul class="dropdown-menu" aria-labelledby="btn_session">
                <li><a class="dropdown-item" href="compras.php">Historial de compras</a></li>
                <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
              </ul>
            </div>
          <?php else: ?>
            <a href="login.php" class="btn btn-success ml-2"><i class="bi bi-person"></i> Ingresar</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>
</header>

<!-- Agregar enlaces para Bootstrap Icons y FontAwesome si no están incluidos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.4.1/font/bootstrap-icons.min.css">
