function updateProgress(currentValue) {
    const progressBar = document.querySelector('.goal-thermometer .progress');
    const goal = "{{ $progress }}";
    let progressPercentage = (currentValue / goal) * 100;
    progressBar.style.width = `${progressPercentage}%`;
}

// Example: update the progress bar
updateProgress(50000); // Replace with dynamic value
