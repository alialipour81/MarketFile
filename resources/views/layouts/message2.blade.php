<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 50000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
</script>
@if($errors->any())
    <script  class="largeee">
        Toast.fire({
            icon: 'error',
            title: '@php
                foreach ($errors->all() as $error){
                    echo $error.'<br>';
                }
            @endphp'
        });
    </script>
@endif
