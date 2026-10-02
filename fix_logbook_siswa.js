    const fs = require('fs');
let c = fs.readFileSync('app/Views/logbook_siswa.php', 'utf8');

const replacement = `</script>
<script>
$(document).ready(function() {
    // Add is-filled to all inputs that have a value
    $('.form-control').each(function() {
        if ($(this).val() !== "") {
            $(this).parent().addClass('is-filled');
        }
    });

    // Handle focus and blur for floating labels
    $('.form-control').on('focus', function() {
        $(this).parent().addClass('is-focused');
    }).on('blur', function() {
        $(this).parent().removeClass('is-focused');
        if ($(this).val() !== "") {
            $(this).parent().addClass('is-filled');
        } else {
            $(this).parent().removeClass('is-filled');
        }
    }).on('change keyup input', function() {
        if ($(this).val() !== "") {
            $(this).parent().addClass('is-filled');
        } else {
            $(this).parent().removeClass('is-filled');
        }
    });
});
</script>`;

c = c.replace('</script>', replacement);
fs.writeFileSync('app/Views/logbook_siswa.php', c);
console.log("Fixed logbook_siswa.php");
