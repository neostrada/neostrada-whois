<?php
/**
 * Created by PhpStorm.
 * User: Psarmidani
 * Date: 03-09-15
 * Time: 09:07
 */
require_once dirname(__FILE__) . '/function/neostrada.inc.php';
require_once dirname(__FILE__) . '/function/functions.php';
/**
 * Ininitalize the Neostrada API client
 */

$DomainApi = new domainAPI();
$Error = '';
$API = Neostrada::GetInstance();

/**
 * Your API information can be found after logging in to the website
 */

$API->SetAPIKey('[API_Key]');
$API->SetAPISecret('[API_Secret]');

/**
 * Get all extensions from our website
 */
$API->prepare('extensions');
$API->execute();
$Result = $API->fetch();

/**
 * Check if you have calls left
 */

if($Result['code'] == 429) {

    $Error = '<div class="error">Too Many Requests</div>';

}elseif($Result['code'] == 540){

    $Error = '<div class="error">Invalid API Key or Secret</div>';
}
    $CountResult = count($Result['extensions']);
    $GetExtenions = $DomainApi->GetExtensions($CountResult, $Result);

?>
<!-- Set up a form to POST the values -->
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="stylesheet" type="text/css" media="screen, projection" href="css/style.css"/>
    </head>
<body>
    <img src="http://neo.site/asset/nx/images/logo.png">

    <div id="demo">
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

                $Domain = str_replace('http://', '', $_POST['domain']);
                $Domain = str_replace('www.', '', $Domain);
                $Domain = htmlentities($Domain);
                $Domain = strtolower($Domain);
                $ext = $_POST['tld'];

                if ($ext == 'all') {

                    for ($i = 0; $i < $CountResult; $i++) {

                        $DomainApi->GetInfo($API, $Domain, $Result['extensions'], $i);

                    }
                } else {

                    $DomainApi->GetInfo($API, $Domain, $Result['extensions']);


                }

                echo "<script type='text/javascript'>
                            if(document.getElementById('whoiswait'))
                            {
                                document.getElementById('whoiswait').style.display = 'none';
                            }
                          </script>";
                ?>
            </tbody>
        </table>
    <?php
    }
?>
</body>