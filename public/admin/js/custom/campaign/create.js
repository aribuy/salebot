// Define the countContact function
function countContact(url, contact_list_id, segment_id) {
    axios.get(url, {
        params: {
            contact_list_id: contact_list_id,
            segment_id: segment_id
        }
    })
    .then(response => {
        let total = response.data;
        $('#total-display').text(total);
    })
    .catch(error => {
        toastr.error(error.message);
    });
}
$(document).ready(function() {
    let contact_list_id = $('#contact_list_id').val();
    let segment_id = $('#segment_id').val();
    countContact(contact_count_url, contact_list_id, segment_id);
});
$('#contact_list_id, #segment_id').on('change', function(e) {
    let contact_list_id = $('#contact_list_id').val();
    let segment_id = $('#segment_id').val();
    countContact(contact_count_url, contact_list_id, segment_id);
});
