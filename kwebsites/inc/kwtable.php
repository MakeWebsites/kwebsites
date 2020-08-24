<?php
global $wpdb;

$tname = $wpdb->prefix . 'posts';
$sql = $wpdb->prepare("SELECT `ID` FROM {$tname} WHERE `post_type` = 'kwebsite'");
$kw_ids = $wpdb->get_col( $sql );

    foreach($kw_ids as $id) {
        $kwname = get_post_meta($id, 'Name', true);
        $kwurl = get_post_meta($id, 'URL', true);
    ?>
        <tr valign="top">
        <td><a href="<?php echo get_edit_post_link( $id)?>">Post id: <?php echo $id ?></a></td>
        <td><?php echo $kwname ?></td>
        <td><?php echo $kwurl ?></td>
        </tr>
    <?php } ?>
    </table>

</form>
