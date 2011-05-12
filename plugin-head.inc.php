<?php $options = get_option('newsletter_profile'); ?>
<script type="text/javascript">
function newsletter_check(f) {
    var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-]{2,})+\.)+([a-zA-Z0-9]{2,})+$/;
    if (!re.test(f.elements["ne"].value)) {
        alert("<?php echo addslashes($options['email_error']); ?>");
        return false;
    }
    if (f.elements["ny"] && !f.elements["ny"].checked) {
        alert("<?php echo addslashes($options['privacy_error']); ?>");
        return false;
    }
    return true;
}
</script>
<style type="text/css">
<?php echo $this->options_main['css']; ?>
</style>