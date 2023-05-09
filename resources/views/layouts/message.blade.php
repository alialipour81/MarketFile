@if(session()->has('success'))

<script>
    Swal.fire(
        'موفقیت آمیز',
        '{{ session()->get('success') }}',
        'success',
    )
    {{--const Toast = Swal.mixin({--}}
    {{--    toast: true,--}}
    {{--    position: 'top-end',--}}
    {{--    showConfirmButton: false,--}}
    {{--    timer: 9000,--}}
    {{--    timerProgressBar: true,--}}
    {{--    didOpen: (toast) => {--}}
    {{--        toast.addEventListener('mouseenter', Swal.stopTimer)--}}
    {{--        toast.addEventListener('mouseleave', Swal.resumeTimer)--}}
    {{--    }--}}
    {{--})--}}

    {{--Toast.fire({--}}
    {{--    icon: 'success',--}}
    {{--    title: '{{ session()->get('success') }}'--}}
    {{--})--}}
</script>
@endif
@if(session()->has('error'))

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 9000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    Toast.fire({
        icon: 'error',
        title: '{{ session()->get('error') }}'
    })
</script>
@endif
@if(session()->has('success_dashboard'))
{{--    <div class="alert alert-success">{{ session()->get('success_dashboard') }}</div>--}}
    <script>
        Swal.fire(
            'موفقیت آمیز',
            '{{ session()->get('success_dashboard') }}',
            'success',
        )
    </script>
@endif
@if(session()->has('error_dashboard'))
{{--    <div class="alert alert-warning">{{ session()->get('error_dashboard') }}</div>--}}
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 37000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'error',
            title: '{{ session()->get('error_dashboard') }}'
        })
    </script>
@endif
