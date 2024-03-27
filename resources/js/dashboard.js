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

    // $(document).ready(function() {
        $('.dtabsbtn').on('click', function() {
          console.log('dtabsbtn-click')
            var tab = $(this).attr('data-tab');
            console.log('tab++++', tab)
            // $.ajax({
            //     url: '/records/' + tab,
            //     type: 'GET',
            //     success: function(data) {
            //         // Handle the data returned from the server, populate the appropriate tab content
            //         console.log(data);
            //     },
            //     error: function(xhr, status, error) {
            //         // Handle errors
            //         console.error(xhr.responseText);
            //     }
            // });
        });

        var defaultTab = "{{ $tab }}";
        console.log(defaultTab,'dafaulttab')
    // });
