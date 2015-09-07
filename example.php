<?php
/**
 * Created by PhpStorm.
 * User: Psarmidani
 * Date: 03-09-15
 * Time: 09:07
 */
require_once dirname(__FILE__) . '/function/functions.php';
/**
 * Ininitalize the Neostrada API client
 */
$DomainApi = new domainAPI();
/**
 * @return array 
 */
list($Error, $API, $Result, $CountResult, $GetExtenions) = $DomainApi->APICall();

?>
<!-- Set up a form to POST the values -->
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="stylesheet" type="text/css" media="screen, projection" href="css/style.css"/>
    </head>
<body>
    <div id="logo"></div>

    <div id="demo" class="bannerColor">
        <?php echo $Error; ?>
        <form method="post" class="whoisform" action="#">
            <p><span style="position:relative;top:-1px;">www.</span>
                <input type="text" name="domain" value="<?php isset($_POST['domain']) ? $_POST['domain'] : '' ?>"/>.
                <select name="tld">
                    <option value="all">All extensions</option>
                    <?php
                    echo $GetExtenions;
                    ?>
                </select>
                <input type="submit" value="Go"/>
            </p>
        </form>
    </div>
<?php

    $CheckDomain = $DomainApi->CheckDomain($_POST['domain']);

if($CheckDomain){
        ?>
        <div id='whoiswait'>
            <div class='outer'>
                <div class='inner'>
                    <img src="img/status.gif" width="50px"><br/>
                    checking <span id='whoistld'></span>
                </div>
            </div>
        </div>

        <table id="whoistable" border="0" width="100%" cellpadding="5" cellspacing="0">
            <thead>
            <tr>
                <th>Domain</th>
                <th>Availability</th>
                <th>WhoIs</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php

                $DomainApi->CheckPost($CountResult, $API, $Result);
                ?>
            </tbody>
        </table>
    <?php
    }
?>
</body>