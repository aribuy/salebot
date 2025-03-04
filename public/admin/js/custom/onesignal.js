$(document).ready(function() {
    // Function to check if notifications are allowed
    function checkNotificationPermission(callback) {
        if ("Notification" in window) {
            Notification.requestPermission().then(function(permission) {
                callback(permission);
            });
        } else {
            console.error("Browser does not support Notifications.");
            callback("unsupported");
        }
    }

    // Check OneSignal Credentials
    $('#check_onesignal_credentials').click(function() {
        axios.get(check_credentials)
            .then(function(response) {
                console.log(response.data);

                if (response.data.status) {
                    Swal.fire({
                        title: 'OneSignal Notification',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'OneSignal Notification',
                        text: response.data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }

            })
            .catch(function(error) {
                console.error(error);
                toastr.error(
                    'Failed to check OneSignal credentials. Check console for details.');
            });
    });

    // Test OneSignal Notification
    $('#test_onesignal_notification').click(function() {
        // Check notification permission before sending
        checkNotificationPermission(function(permission) {
            if (permission === "granted") {
                axios.get(test_onesignal)
                    .then(function(response) {
                        if (response.data.status) {
                            toastr.success(response.data.message);
                        } else {
                            toastr.error(response.data.message);
                        }
                    })
                    .catch(function(error) {
                        console.error(error);
                        toastr.error(
                            'Failed to send test OneSignal notification. Check console for details.'
                            );
                    });
            } else if (permission === "denied") {
                toastr.error('Please allow notifications to send test notifications.');
            } else {
                toastr.error('Browser does not support notifications.');
            }
        });
    });
});