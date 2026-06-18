<!--   Core JS Files   -->
<script src="{{ asset('yuukke/assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('yuukke/assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('yuukke/assets/js/core/bootstrap.min.js') }}"></script>

<!-- jQuery Scrollbar -->
<script src="{{ asset('yuukke/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

<!-- Chart JS -->
<script src="{{ asset('yuukke/assets/js/plugin/chart.js/chart.min.js') }}"></script>

<!-- jQuery Sparkline -->
<script src="{{ asset('yuukke/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

<!-- Chart Circle -->
<script src="{{ asset('yuukke/assets/js/plugin/chart-circle/circles.min.js') }}"></script>

<!-- Datatables -->
<script src="{{ asset('yuukke/assets/js/plugin/datatables/datatables.min.js') }}"></script>

<!-- Bootstrap Notify -->
{{-- <script src="{{ asset('yuukke/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script> --}}

<!-- jQuery Vector Maps -->
<script src="{{ asset('yuukke/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
<script src="{{ asset('yuukke/assets/js/plugin/jsvectormap/world.js') }}"></script>

<!-- Sweet Alert -->
{{-- <script src="{{ asset('yuukke/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script> --}}

<!-- main JS -->
<script src="{{ asset('yuukke/assets/js/main.min.js') }}"></script>
{{-- select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Kaiyuukke DEMO methods, don't include it in your project! -->
{{-- <script src="{{ asset('yuukke/assets/js/setting-demo.js') }}"></script>
<script src="{{ asset('yuukke/assets/js/demo.js') }}"></script> --}}
<script>
    $(document).ready(function() {
        $('#size').select2();
    });
    $(document).ready(function() {
        $("#brandsTable").DataTable({});
       
    });


</script>

<script>
    // ##== For Banner scripts
    // $(".delete-banner").click(function() {
    //     var bannerId = $(this).data('id');
    //     var userURL = $(this).data('url');
    //     var trObj = $(this);

    //     if (confirm("Are you sure you want to delete this banner?") == true) {
       
    //         $.ajax({
    //             url: userURL,
    //             type: 'DELETE',
    //             data: {
    //                 _token: '{{ csrf_token() }}'
    //             },
    //             dataType: 'json',
    //             success: function(data) {
    //                 console.log(data);
    //                 if (data.success) {
    //                     $('#banner-row-' + bannerId).fadeOut(500, function() {
    //                         $(this).remove();
    //                     });
    //                     $('#delete-message').html(
    //                         '<div class="alert alert-success">Banner deleted successfully!</div>'
    //                     );
    //                     // $('#bannersTable').DataTable().ajax.reload();
    //                     // Reload DataTable
                    
    //                     // let table = $('#bannersTable').DataTable(); // Get the DataTable instance
    //                     // table.ajax.reload(null, false);
    //                 } else {
    //                     $('#delete-message').html(
    //                         '<div class="alert alert-danger">An error occurred. Please try again.</div>'
    //                     );
    //                 }
    //             },
    //             error: function(xhr, status, error) {
    //                 console.log(xhr.responseText);
    //                 $('#delete-message').html(
    //                     '<div class="alert alert-danger">An error occurred. Please try again.</div>'
    //                 );
    //             }
    //         });
    //     }
    // });

   
</script>
