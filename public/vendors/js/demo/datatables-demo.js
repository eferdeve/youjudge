// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('.dataTable').DataTable({
    columnDefs: [
      { "orderable": false, "targets": 3 }
    ],
    language: {
        "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
    },
    ordering: true,
    responsive: true
});
});
