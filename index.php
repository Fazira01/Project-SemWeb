<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libraria</title>
    
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/c3c1353c4c.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Connector untuk menghubungkan PHP dan SPARQL -->
    <?php
        require_once("sparqllib.php");
        $test = "";
        if (isset($_POST['search'])) {
            $test = $_POST['search'];
            $data = sparql_get(
            "http://localhost:3030/libraria",
            "
            PREFIX id: <https://libraria.com/>
            PREFIX item: <https://libraria.com/ns/item#>
            PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

            SELECT ?Judul ?Penulis ?Penerbit ?Harga ?Bahasa ?Genre ?TahunRilis
            WHERE
            { 
                ?items
                    item:Judul          ?Judul ;
                    item:Penulis        ?Penulis ;
                    item:Penerbit       ?Penerbit ;
                    item:Harga          ?Harga ;
                    item:Bahasa         ?Bahasa ;
                    item:Genre          ?Genre ;
                    item:TahunRilis     ?TahunRilis .
                    FILTER 
                    (regex   (?Judul, '$test', 'i') 
                    || regex (?Penulis, '$test', 'i') 
                    || regex (?Penerbit, '$test', 'i') 
                    || regex (?Harga, '$test', 'i') 
                    || regex (?Bahasa, '$test', 'i') 
                    || regex (?Genre, '$test', 'i') 
                    || regex (?TahunRilis, '$test', 'i'))
                    }"
            );
        } else {
            $data = sparql_get(
            "http://localhost:3030/libraria",
            "
                PREFIX id: <https://libraria.com/>
                PREFIX item: <https://libraria.com/ns/item#>
                PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
                
                SELECT ?Judul ?Penulis ?Penerbit ?Harga ?Bahasa ?Genre ?TahunRilis
                WHERE
                { 
                    ?items
                        item:Judul          ?Judul ;
                        item:Penulis        ?Penulis ;
                        item:Penerbit       ?Penerbit ;
                        item:Harga          ?Harga ;
                        item:Bahasa         ?Bahasa ;
                        item:Genre          ?Genre ;
                        item:TahunRilis     ?TahunRilis .
                }
            "
            );
        }

        if (!isset($data)) {
            print "<p>Error: " . sparql_errno() . ": " . sparql_error() . "</p>";
        }
    ?>

    <!-- Navbar -->
    <nav style= "background-color: #dee2e6" class="navbar navbar-expand-lg">
        <div class="container container-fluid">
            <a class="navbar-brand" href="index.php"><img src="src/img/logo.png" style="width:100px;;" alt="Logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 h5">
                    <li class="nav-item px-2">
                        <a class="nav-link active text-black" aria-current="page" href="index.php">Home</a>
                    </li>
                </ul>
                <form class="d-flex" role="search" action="" method="post" id="nameform">
                    <input class="form-control me-2" type="search" placeholder="Ketik buku disini" aria-label="Search" name="search">
                    <button class="btn btn-outline-info" type="submit">Cari</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container container-fluid mt-3  ">
        <?php
            if ($test != NULL) {
                ?> <i class="fa-solid fa-magnifying-glass"></i><span>Menampilkan pencarian  <b>"<?php echo $test; ?>"</b> <br><br></span><?php
            } 
        ?>
        <table class="table table-bordered table-striped table-hover text-center">
            <thead class="table-secondary">
                <tr>
                    <th>No.</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Bahasa</th>
                    <th>Genre</th>
                    <th>TahunRilis</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                <?php foreach ($data as $dat) : ?>
                <tr>
                    <td><?= ++$i ?></td>
                    <td><?= $dat['Judul'] ?></td>
                    <td><?= $dat['Penulis'] ?></td>
                    <td><?= $dat['Penerbit'] ?></td>
                    <td><?= $dat['Bahasa'] ?></td>
                    <td><?= $dat['Genre'] ?></td>
                    <td><?= $dat['TahunRilis'] ?></td>
                    <td><?= $dat['Harga'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>