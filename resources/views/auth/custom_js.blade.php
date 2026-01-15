<script>
    $(document).ready(function() {
        // 1. Auto Fill NIP
        $('#input-nama').on('input', function() {
            var val = $(this).val();
            var list = $('#list-users option');
            var match = list.filter(function() { return this.value === val; });

            if (match.length > 0) {
                var nip = match.data('nip');
                $('#input-nip').val(nip).addClass('bg-blue-50/50 text-blue-700 font-bold border-blue-200');
                $('#password').focus();
            } else {
                $('#input-nip').val('').removeClass('bg-blue-50/50 text-blue-700 font-bold border-blue-200');
            }
        });

        // 2. Toggle Password
        $('#toggle-password').on('click', function() {
            var passwordInput = $('#password');
            var icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>