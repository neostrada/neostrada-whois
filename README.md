![Neostrada](https://www.neostrada.nl/asset/nx/images/logo.png)
=================

# neostrada-whois PHP voorbeeldscript
Simpel en snel je eigen who-Is opzetten

## API Key en Secret ##
Om de script te kunnen gebruiken heb je een API Key en Secret nodig.
Log in op https://www.neostrada.nl/inloggen.html en klik vervolgens links op API in het menu

## Aanpassing ##
Pas de volgende code aan in functions onder functie APICall.

```js
/**
 * Your API information can be found after logging in to the website
 */

$API->SetAPIKey('[API_Key]');
$API->SetAPISecret('[API_Secret]');
```

Verander `[API_Key]` en `[API_Secret]` naar de gegevens die je vanuit je Neostrada account hebt meegekregen.

## License ##
[BSD (Berkeley Software Distribution) License](http://www.opensource.org/licenses/bsd-license.php).
Copyright (c) 2012, Avot Media BV

## Support ##
[www.neostrada.nl](https://www.neostrada.nl) - support@neostrada.nl
