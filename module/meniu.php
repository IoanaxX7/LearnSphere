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
                            <li><a class="dropdown-item" href="<?php echo url() ?>/admin/materii.php">Materii</a></li>
                            <li><a class="dropdown-item" href="<?php echo url() ?>/admin/categorii.php">Categorii</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="d-flex flex-grow-1 justify-content-center my-2 my-lg-0">
                <form class="input-group w-50" role="search">
                    <input type="search" class="form-control" placeholder="Search" aria-label="Caută">
                    <button class="btn meniuBtn" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <?php
            if (!empty($_SESSION["username"]) || in_array($_SESSION["rol"], $Roluri)) {
                $imgSrc = url() . '/uploads/' . $_SESSION['username'] . '/' . $_SESSION['pozaProfil'];
                $setariUrl = url() . '/setari/setari.php';
                $logoutUrl = url() . '/logout.php';

                echo '<div class="dropdown ms-3 mt-2 mt-lg-0">
                    <a href="#" class="btn p-0 border-0 bg-transparent" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="' . htmlspecialchars($imgSrc) . '" alt="Profile" class="rounded-circle" style="width: 30px; height: 30px;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="' . htmlspecialchars($setariUrl) . '">Setări</a></li>
                        <li><a class="dropdown-item" href="' . htmlspecialchars($logoutUrl) . '">Deconectare</a></li>
                    </ul>
                </div>';
            } else {
                $loginUrl = url() . '/login.php';
                $signupUrl = url() . '/signin.php';

                echo '<div class="ms-3 mt-2 mt-lg-0 d-flex gap-2">
                    <a href="' . htmlspecialchars($loginUrl) . '" style="background-color: white; color: #438bab;" class="btn fw-semibold autentificare">Autentificare</a>
                    <a href="' . htmlspecialchars($signupUrl) . '" style="background-color: #4CAF50; color: white;" class="btn fw-semibold inregistrare">Înregistrare</a>
                </div>';
            }

            ?>
        </div>
    </div>
</nav>