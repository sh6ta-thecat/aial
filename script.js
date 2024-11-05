const audio = document.getElementById("audio");
const playPauseIcon = document.getElementById("playPauseIcon");
const progressBar = document.getElementById("progress-bar");
const currentTimeDisplay = document.getElementById("currentTime");
const remainingTimeDisplay = document.getElementById("remainingTime");

function togglePlayPause() {
    if (audio.paused) {
        audio.play();
        playPauseIcon.classList.replace("fa-play", "fa-pause");
    } else {
        audio.pause();
        playPauseIcon.classList.replace("fa-pause", "fa-play");
    }
}

audio.addEventListener("timeupdate", updateProgressBar);
audio.addEventListener("ended", () => {
    playPauseIcon.classList.replace("fa-pause", "fa-play");
});

function updateProgressBar() {
    const currentTime = audio.currentTime;
    const duration = audio.duration;
    const remainingTime = duration - currentTime;
    
    // Actualizar la posición de la barra de progreso
    progressBar.value = (currentTime / duration) * 100;

    // Actualizar el tiempo transcurrido y el tiempo restante
    currentTimeDisplay.textContent = formatTime(currentTime);
    remainingTimeDisplay.textContent = formatTime(remainingTime);
}

function formatTime(time) {
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
}

// Permitir que el usuario cambie el tiempo de reproducción al mover el slider
progressBar.addEventListener("input", () => {
    const duration = audio.duration;
    audio.currentTime = (progressBar.value / 100) * duration;
});
