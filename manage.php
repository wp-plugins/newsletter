<?php

if ($_POST['a'] == 'remove')
{
    newsletter_delete($_POST['email']);
}

if ($_POST['a'] == 'search')
{
    $list = newsletter_search($_POST['text']);
}

?>
<script type="text/javascript">
function newsletter_remove(email)
{
    document.getElementById("email").value = email;
    document.getElementById("channel").submit();
}
</script>
<div class="wrap">
        <h2>Subscribers Management</h2>
        <p>Still in developement, any ideas will be great: write me to info@satollo.com.</p>
    <form method="post" action="">
        <input type="hidden" name="a" value="search"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label>Search by email</label></th>
                <td>
                    <input name="text" type="text" size="50" value="<?php echo htmlspecialchars($options['query'])?>"/>
                </td>
            </tr>
        </table>
    </form>

    <h2>Results</h2>
    <?php
        if ($list)
        {
            foreach($list as $s)
            {
                echo $s->email . ' [' . $s->status . ']';
                echo ' <a href="javascript:void(newsletter_remove(\'' . $s->email . '\'))">delete</a>';
                echo '<br />';
            }
        }
    ?>

    <form id="channel" action="" method="post">
    <input type="hidden" id="email" name="email" value=""/>
    <input type="hidden" name="a" value="remove"/>
    </form>

</div>
