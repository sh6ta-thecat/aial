<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Anime</title>
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="details-container">
        <?php
        $jsonUrl = 'https://sh6ta-thecat.github.io/cuddly/api.json';
        $jsonData = file_get_contents($jsonUrl);

        if ($jsonData === false) {
            echo '<p class="error">Error al obtener los datos de la API.</p>';
        } else {
            $data = json_decode($jsonData, true);
            $animeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $anime = null;

            foreach ($data as $item) {
                if ($item['id'] === $animeId) {
                    $anime = $item;
                    break;
                }
            }

            if ($anime) {
                echo '<img class="anime-image" src="' . htmlspecialchars($anime['imagen']) . '" alt="' . htmlspecialchars($anime['title']) . '">';
                echo '<h2 class="anime-title">' . htmlspecialchars($anime['title']) . '</h2>';
                echo '<p class="anime-info"><strong>Manga:</strong> <a href="' . htmlspecialchars($anime['manga']) . '" target="_blank">Leer aquí</a></p>';
                echo '<p class="anime-info"><strong>Anime:</strong> <a href="' . htmlspecialchars($anime['anime']) . '" target="_blank">Ver aquí</a></p>';
                echo '<p class="anime-tags"><strong>Tags:</strong> ' . htmlspecialchars($anime['tags']) . '</p>';
                
                // Reproductor de audio estilizado
                echo '<div class="audio-player">';
                echo '  <audio id="audio" src="' . htmlspecialchars($anime['cancion']) . '"></audio>';
                echo '  <div class="audio-wave"></div>';
                echo '  <div class="audio-controls">';
                echo '      <button class="audio-btn shuffle"><i class="fas fa-random"></i></button>';
                echo '      <button class="audio-btn backward"><i class="fas fa-backward"></i></button>';
                echo '      <button class="audio-btn play-pause" onclick="togglePlayPause()"><i id="playPauseIcon" class="fas fa-play"></i></button>';
                echo '      <button class="audio-btn forward"><i class="fas fa-forward"></i></button>';
                echo '      <button class="audio-btn repeat"><i class="fas fa-redo"></i></button>';
                echo '  </div>';
                echo '  <div class="progress-container">';
                echo '      <span id="currentTime" class="time">0:00</span>';
                echo '      <input type="range" id="progress-bar" class="progress-bar" min="0" max="100" value="0">';
                echo '      <span id="remainingTime" class="time">0:00</span>';
                echo '  </div>';
                echo '</div>';
            } else {
                echo '<p>No se encontraron detalles para este anime.</p>';
            }
        }
        ?>
        <a href="index.php" class="back-button">Volver a la lista</a>
    </div>
    <script src="script.js"></script>
</body>
</html>
