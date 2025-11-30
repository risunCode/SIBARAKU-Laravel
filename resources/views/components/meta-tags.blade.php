{{-- Common meta tags for the application --}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="theme-color" content="#4f46e5">

<!-- Fix for CSRF token in ngrok -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle logout clicks
    document.querySelectorAll('.logout-button').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Create logout form with CSRF token and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('logout') }}';
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        });
    });
});
</script>
