function showToast(message, duration = 3000, backgroundColor = "#222", stopOnFocus = true) {
    Toastify({
        text: message,
        duration: duration,
        backgroundColor: backgroundColor,
        stopOnFocus: stopOnFocus
    }).showToast();
}

function showToastError(message, duration = 3000, backgroundColor = "red", stopOnFocus = true) {
    Toastify({
        text: message,
        duration: duration,
        backgroundColor: backgroundColor,
        stopOnFocus: stopOnFocus
    }).showToast();
}

// Function to show a confirmation message using Toastify
function showConfirmation() {
    // Display the confirmation message as a toast notification
    Swal.fire({
        title: "Do you want to save the changes?",
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: "Save",
        customClass: {
            confirmButton: 'custom-save-button'
          }
        // denyButtonText: `Don't save`
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          Swal.fire("Saved!", "", "success");
        } else if (result.isDenied) {
          Swal.fire("Changes are not saved", "", "info");
        }
      });
}