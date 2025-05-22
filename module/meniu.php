<nav class="navbar navbar-expand-lg navbar-dark px-3 py-2">
    <div class="container-fluid">
        <a class="navbar-brand me-3" href="<?php echo url() ?>/index.php">LearnSphere</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mb-2 mb-lg-0 me-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="materialeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Materiale
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="materialeDropdown">
                        <li><a class="dropdown-item" href="<?php echo url() ?>/postari/materiale.php">Caută materiale</a></li>
                        <li><a class="dropdown-item" href="<?php echo url() ?>/postari/form_material.php">Încarcă un material</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="intrebariDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Întrebări
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="intrebariDropdown">
                        <li><a class="dropdown-item" href="<?php echo url() ?>/postari/intrebari.php">Caută întrebări</a></li>
                        <li><a class="dropdown-item" href="<?php echo url() ?>/postari/form_intrebare.php">Pune o întrebare</a></li>
                    </ul>
                </li>
                <?php if (in_array($_SESSION["rol"], [1])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Admin
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="<?php echo url() ?>/admin/utilizatori.php">Utilizatori</a></li>
                            <li><a class="dropdown-item" href="<?php echo url() ?>/admin/materiale.php">Materiale</a></li>
                            <li><a class="dropdown-item" href="<?php echo url() ?>/admin/intrebari.php">Întrebări</a></li>
                            <li><a class="dropdown-item" href="<?php echo url() ?>/admin/categorii.php">Categorii</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="d-flex flex-grow-1 justify-content-center my-2 my-lg-0">
                <form class="input-group w-50" role="search">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="search" class="form-control" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>

            <div class="dropdown ms-3 mt-2 mt-lg-0">
                <a href="#" class="btn p-0 border-0 bg-transparent" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo url() . '/uploads/' . $_SESSION['username'] . '/' . $_SESSION['pozaProfil']; ?>" alt="Profile" class="rounded-circle" style="width: 30px; height: 30px;">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="<?php echo url() ?>/setari/setari.php">Setări</a></li>
                    <li><a class="dropdown-item" href="<?php echo url() ?>/logout.php">Deconectare</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>