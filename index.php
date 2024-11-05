<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Anime</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Anime Finder</h1>
        <form method="GET" action="">
            <input type="text" name="query" placeholder="Buscar anime o tag..." required>
            <select name="filter">
                <option value="all">Todo</option>
                <option value="name">Nombre</option>
                <option value="tags">Tags</option>
            </select>
            <button type="submit">Buscar</button>
        </form>
    </header>
    <main>
        <div class="results">
            <?php
            $jsonUrl = 'https://sh6ta-thecat.github.io/cuddly/api.json';
            $jsonData = file_get_contents($jsonUrl);

            if ($jsonData === false) {
                echo '<p class="error">Error al obtener los datos de la API.</p>';
            } else {
                $data = json_decode($jsonData, true);
                $query = isset($_GET['query']) ? strtolower(trim($_GET['query'])) : '';
                $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
                $results = [];

                if ($query !== '') {
                    foreach ($data as $item) {
                        if ($filter === 'name' && stripos($item['title'], $query) !== false) {
                            $results[] = $item;
                        } elseif ($filter === 'tags' && stripos($item['tags'], $query) !== false) {
                            $results[] = $item;
                        } elseif ($filter === 'all' && (stripos($item['title'], $query) !== false || stripos($item['tags'], $query) !== false)) {
                            $results[] = $item;
                        }
                    }
                } else {
                    $results = $data;
                }

                if (count($results) > 0) {
                    foreach ($results as $item) {
                        echo '<a href="details.php?id=' . $item['id'] . '" class="card">';
                        echo '<img src="' . htmlspecialchars($item['imagen']) . '" alt="' . htmlspecialchars($item['title']) . '">';
                        echo '<div class="card-content">';
                        echo '<h2>' . htmlspecialchars($item['title']) . '</h2>';
                        echo '<p>Tags: ' . htmlspecialchars($item['tags']) . '</p>';
                        echo '</div></a>';
                    }
                } else {
                    echo '<p>No se encontraron resultados para "' . htmlspecialchars($query) . '"</p>';
                }
            }
            ?>
        </div>
    </main>
</body>
</html>
