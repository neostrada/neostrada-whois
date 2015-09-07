<?php
    /**
     * Created by PhpStorm.
     * User: Puya
     * Date: 10-7-2015
     * Time: 08:48
     */

    require_once dirname(__FILE__) . '/neostrada.inc.php';

    class domainAPI extends Neostrada
    {

        public function APICall()
        {
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

            if ($Result['code'] == 429) {

                $Error = '<div class="error">Too Many Requests</div>';

            } elseif ($Result['code'] == 540) {

                $Error = '<div class="error">Invalid API Key or Secret</div>';
            }
            $CountResult = count($Result['extensions']);
            $GetExtenions = $this->GetExtensions($CountResult, $Result);
            return array($Error, $API, $Result, $CountResult, $GetExtenions);
        }

        public function CheckPost($CountResult, $API, $Result)
        {
            $Domain = str_replace('http://', '', $_POST['domain']);
            $Domain = str_replace('www.', '', $Domain);
            $Domain = htmlentities($Domain);
            $Domain = strtolower($Domain);
            $ext = $_POST['tld'];

            if ($ext == 'all') {

                for ($i = 0; $i < $CountResult; $i++) {

                    $this->GetInfo($API, $Domain, $Result['extensions'], $i);

                }
            } else {

                $this->GetInfo($API, $Domain, $Result['extensions']);


            }

            echo "<script type='text/javascript'>
                            if(document.getElementById('whoiswait'))
                            {
                                document.getElementById('whoiswait').style.display = 'none';
                            }
                          </script>";
        }

        /**
         * @param $API
         * @param $domain
         * @param $inputExt
         * @return mixed
         * Return information about the domain and extension.
         */
        public function getDomain($API,$domain, $inputExt)
        {

            $API->prepare('whois', array(
                'domain' => $domain,
                'extension' => $inputExt
            ));
            $API->execute();
            $ResultDomain = $API->fetch();

            echo "<script type='text/javascript'>
                                    if(document.getElementById('whoistld'))
                                    {
                                        document.getElementById('whoistld').innerHTML=\"".$domain.".".$inputExt."\";
                                    }
                                  </script>";

            $data = $ResultDomain;

            return $data;
        }

        /**
         * @param $CountResult
         * @param $Result
         * @return string
         * Count the results for the form dropdown
         */
        public function GetExtensions($CountResult, $Result){

            $Options = '';
            for ($i = 0; $i < $CountResult; $i++)
            {

                $Extension = $Result['extensions'][$i];

                $inputExt = $this->getouterSubstring($Extension, ';', 0);


                $Options .= '<option name = "ext" value="' . $inputExt . '">.' . $inputExt . '</option>' . "\n";
            }
            return $Options;

        }

        /**
         * @param $Post
         * @return bool
         * Validate domain input POST
         */
        public function CheckDomain($Post){

            $void = TRUE;
            if (empty($Post))
            {
                echo 'Invalid domain';
                $void = FALSE;
            } elseif (strlen($Post) < 2)
            {
                echo 'Domain is too short';
                $void = FALSE;
            } elseif (strlen($Post) > 60)
            {
                echo 'Domain is too long';
                $void = FALSE;
            } elseif (!preg_match('/^([a-zA-Z0-9_-]+)$/i', $Post))
            {
                echo 'Special characters not allowed';
                $void = FALSE;
            }
                return $void;
        }

        /**
         * @param $API
         * @param $Domain
         * @param $Result
         * @param bool $i
         * Explode the array and get the extensions
         */

        public function GetInfo($API, $Domain, $Result, $i = FALSE)
        {

            $Extension = $Result[$i];

            $inputExt = $this->getouterSubstring($Extension, ';', 0);

            $Check = $this->getDomain($API,$Domain,$inputExt);


            $this->GetResults($Domain, $inputExt, $i, $Check);

            flush();
        }

        /**
         * @param $string
         * @param $delim
         * @param $block
         * @return string
         * Strip the extension array
         */

        public function getouterSubstring($string, $delim, $block)
        {
            // "foo a foo" becomes: array(""," a ","")
            $string = explode($delim, $string, 2);
            return isset($string[$block]) ? $string[$block] : '';
        }

        /**
         * @param $domein
         * @param $extensie
         * @param $i
         * @param $Check
         * Print the result
         */
        function GetResults($domein, $extensie, $i, $Check)
        {

            $k = ((($i + 1) % 2) == 0) ? 1 : 2;

            if($Check['code'] == 210){

                $getResult = '<td class="whoisrow'.$k.'"><img src="img/check.png" width="16" height="16" alt="check"></td>';
            }elseif($Check['code'] == 211){

                $getResult = '<td class="whoisrow'.$k.'"><img src="img/cross.png" width="16" height="16" alt="cross"></td>';
            }

            echo '<tr><td class="whoisrow'.$k.'">
                <a href="http://www.'.$domein.'.'.$extensie.'" target="_blank">'.$domein.'.'.$extensie.'</a>
              </td>'.$getResult;
            echo  '
              <td class="whoisrow'.$k.'">'.$Check['description']; //begin de laatste kolom

            echo '</td></tr>';
        }
    }