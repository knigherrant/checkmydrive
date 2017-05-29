<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head><title>Welcome to <?php echo $site_name; ?>!</title></head>
    <body>
        <div style="max-width: 800px; margin: 0; padding: 30px 0;">
            <table width="80%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="5%"></td>
                    <td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
                    <h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Welcome to <?php echo $site_name; ?>!</h2>
                    Thanks for joining <?php echo $site_name; ?>. We listed your sign in details below, make sure you keep them safe.<br />
                    To verify your email address, please follow this link:<br />
                    <br />
                    <big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo Checkmydrive::route('activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;">Finish your registration...</a></b></big><br />
                    <br />
                    Link doesn't work? Copy the following link to your browser address bar:<br />
                    <nobr><a href="<?php echo Checkmydrive::route('activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo Checkmydrive::route('/activate/'.$user_id.'/'.$new_email_key); ?></a></nobr><br />
                    <br />
                    Please verify your email within <?php echo $activation_period; ?> hours, otherwise your registration will become invalid and you will have to register again.<br />
                    <br />
                    <br />
                    Account Dashboard : 
                    <nobr><a href="<?php echo Checkmydrive::route("$username"); ?>" style="color: #3366cc;"><?php echo Checkmydrive::route($username); ?></a></nobr>
                    <br />
                    Admin Dashboard: 
                    <nobr><a href="<?php echo Checkmydrive::route($username.'/admin'); ?>" style="color: #3366cc;"><?php echo Checkmydrive::route($username.'/admin'); ?></a></nobr>
                    <br />
                    <br />
                    <?php if (strlen($username) > 0) { ?>Your username: <?php echo $username; ?><br /><?php } ?>
                    Your email address: <?php echo $email; ?><br />
                    <?php if (isset($password)) { /* ?>Your password: <?php echo $password; ?><br /><?php */ } ?>
                    <br />
                    <a href="https://docs.google.com/document/d/1hNFsqc4ZDuzf9D9Py1lGFP3GeBlkSmyxt6Tee4qwO4w/edit">Documentation Here</a>
                    <br />
                    Have fun!<br />
                    The <?php echo $site_name; ?> Team
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>