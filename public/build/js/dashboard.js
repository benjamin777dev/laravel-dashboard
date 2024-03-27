function updateProgress(currentValue) {
    const progressBar = document.querySelector('.goal-thermometer .progress');
    const goal = "{{ $progress }}";
    let progressPercentage = (currentValue / goal) * 100;
    progressBar.style.width = `${progressPercentage}%`;
}

// Example: update the progress bar
updateProgress(50000); // Replace with dynamic value

function datePickerRange(){
$(function() {
    $('input[name="daterange"]').DateRangePicker({
      opens: 'left'
    }, function(start, end, label) {
      console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
  });
}